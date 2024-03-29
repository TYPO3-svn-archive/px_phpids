<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 pixabit GmbH / Pascal Naujoks <pascal.naujoks@pixabit.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'PHPIDS for Typo3' for the 'px_phpids' extension.
 *
 * @author	pixabit GmbH / Pascal Naujoks <pascal.naujoks@pixabit.de>
 * @package	TYPO3
 * @subpackage	tx_pxphpids *
 */
class tx_pxphpids_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_pxphpids_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_pxphpids_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'px_phpids';	// The extension key.
    var $conf;

	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{
        $this->pi_USER_INT_obj=1;
        $this->conf = $conf;
		$this->conf['General.']['exceptions'] = array();
		$version = 'PHPIDS v0.6.3.1 (patched Monitor.php v0.6.4)';
        
		$this->conf['General.']['whitelist'] = explode(',',$this->conf['General.']['whitelist']);
		
		// Should the current page be monitored or is it in the whitelist?
		if(!in_array($GLOBALS['TSFE']->id, $this->conf['General.']['whitelist'])){
		
	        // Hook for importing exceptions from constant editor		
			$this->conf['General.']['exceptions'] = array_merge(explode(',',$this->conf['General.']['exceptions_0']), $this->conf['General.']['exceptions']);
			$this->conf['General.']['exceptions'] = array_merge(explode(',',$this->conf['General.']['exceptions_1']), $this->conf['General.']['exceptions']);
			$this->conf['General.']['exceptions'] = array_merge(explode(',',$this->conf['General.']['exceptions_2']), $this->conf['General.']['exceptions']);		
	        unset($this->conf['General.']['exceptions_0'],$this->conf['General.']['exceptions_1'],$this->conf['General.']['exceptions_2']);
	
			// Settings
	        $this->path = t3lib_extMgm::extPath('px_phpids');       // Define Path to PHP IDS
	        $this->debug = $this->conf['General.']['debug_mode']=='1' ? true : false;   // Debug Mode true or false
	
	        // Set the include path properly for PHPIDS
	        set_include_path(get_include_path().PATH_SEPARATOR.$this->path);
	
	        if (!session_id()) { session_start(); }
	
	        require_once 'IDS/Init.php';
	
	        $content='<h6>'.$version.'</h6>';
	
	        try{
	            /*
	            * Define what to scan
	            *
	            * Please keep in mind what array_merge does and how this might interfer
	            * with your variables_order settings
	            */
	            $request = array(
	                'GET' => $_GET,
	                'POST' => $_POST,
	                'COOKIE' => $_COOKIE
	            );
	
	            $init = IDS_Init::init();
	
	             /**
	             * You can reset the whole configuration
	             * array or merge in own data
	             *
	             * This usage doesn't overwrite already existing values
	             * $config->setConfig(array('General' => array('filter_type' => 'xml')));
	             *
	             * This does (see 2nd parameter)
	             * $config->setConfig(array('General' => array('filter_type' => 'xml')), true);
	             *
	             * or you can access the config directly like here:
	             */
	
	            $init->config['General'] = $this->conf['General.'];
	            $init->config['General']['base_path'] = $this->path.'IDS/';
	
	            $init->config['Logging'] = $this->conf['Logging.'];
	            $init->config['Logging']['table'] = 'tx_pxphpids_log';
	            $init->config['Logging']['recipients'] = $this->conf['Logging.']['email'] ? $this->conf['Logging.']['email'] : $TYPO3_CONF_VARS['BE']['warning_email_addr'];
	
	            $init->config['Caching'] = $this->conf['Caching.'];
	            $init->config['Caching']['table'] = 'tx_pxphpids_cache';            
	
	             // Initiate the PHPIDS and fetch the results
	            $ids = new IDS_Monitor($request, $init);
	            $result = $ids->run();
	
	            /*
	            * Now you can analyze the results:
	            *
	            * In the result object you will find any suspicious
	            * fields of the passed array enriched with additional info
	            *
	            * Note: it is moreover possible to dump this information by
	            * simply echoing the result object, since IDS_Report implemented
	            * a __toString method.
	            */
	            if (!$result->isEmpty()) {
	                $content.='<p class="ok_box">'.$result.'</p>';
	
	                /*
	                * Log the results
	                */
	                require_once 'IDS/Log/File.php';
	                require_once 'IDS/Log/Composite.php';
	                require_once 'IDS/Log/Email.php';
	                require_once 'IDS/Log/Database.php';
	
	                $compositeLog = new IDS_Log_Composite();
	
	                /*
	                 *  Outcomment the following if you don't need them
	                 *  Impacts start at 0 and leads to ~150.
	                 *  1-20: Low Impact -> just log it to a file
	                 *  21-50: Medium Impact -> log it to a database
	                 *  50-99: High Impact -> instantly report it to an admin via email!
	                 *  100+: This is a serious hacking attemp -> knock em out!
	                 */
	
	                if($result->getImpact()>=$this->conf['Impact.']['file_threshold']){
	                    $compositeLog->addLogger(IDS_Log_File::getInstance($init));     // Log Impact into File (IDS/tmp/phpids_log.txt)
	                    $content .='<p>Reporting to File (Threshold: '.$this->conf['Impact.']['file_threshold'].')</p>';
	                }
	
	                if($result->getImpact()>=$this->conf['Impact.']['db_threshold']){
	                    $compositeLog->addLogger(IDS_Log_Database::getInstance($init)); // Log Impact into a Database (tx_pxphpids_log)
	                    $content .='<p>Reporting to DB (Threshold: '.$this->conf['Impact.']['db_threshold'].')</p>';
	                }
	
	                if($result->getImpact()>=$this->conf['Impact.']['email_threshold']){
	                    $compositeLog->addLogger(IDS_Log_Email::getInstance($init));    // Report Impact via E-Mail
	                    $content .='<p>Reporting to E-Mail (Threshold: '.$this->conf['Impact.']['email_threshold'].')</p>';
	                }
	
	                if($result->getImpact()>=$this->conf['Impact.']['die_threshold']){
	                    if($this->debug==false){
	                        $content = '';  // Delete all informations possibly given by PHP IDS
	                    }else{
	                        $content .='<p>Dieing... (Threshold: '.$this->conf['Impact.']['die_threshold'].')</p>';
	                    }					
	                    $content .= '	<p>You have been logged out cause of a possible hacking attemp.</p>
										<p>Your data has been stored and reported.</p>
										<p>If you think this is an error please contact the webmaster of this website.</p>
										<p><i>'.$version.'</i></p>';				
	                    session_destroy();
	                    die($content);
	                }
	
	                $compositeLog->execute($result);
	            } else {
	                $content.='	<p class="red_box">
									<a href="?test=%22><script>eval(window.name)</script>">No attack detected - click for an example attack</a>
									<br />
									You can disable this message by setting "General.debug_mode" to false in the TypoScript Objects of PHPIDS.	
								</p>';				
	            }
	
	        } catch (Exception $e) {
	            $content.='<p class="error_box">An error occurred: '.$e->getMessage().'</p>';
	            $content.='<p class="error_box">Settings in $this->conf<pre>'.print_r($this->conf,true).'</pre></p>';
	            $content.='<p class="error_box">Settings in $init->config<pre>'.print_r($init->config,true).'</pre></p>';
	        }
	        if($this->debug==true){
	            return $this->pi_wrapInBaseClass($content);
	        }
		}
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/px_phpids/pi1/class.tx_pxphpids_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/px_phpids/pi1/class.tx_pxphpids_pi1.php']);
}

?>