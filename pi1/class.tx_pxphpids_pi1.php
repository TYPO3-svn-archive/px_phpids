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
 * Plugin 'PHPIDS' for the 'px_phpids' extension.
 *
 * @author	pixabit GmbH / Pascal Naujoks <pascal.naujoks@pixabit.de>
 * @package	TYPO3
 * @subpackage	tx_pxphpids
 */
class tx_pxphpids_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_pxphpids_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_pxphpids_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'px_phpids';	// The extension key.
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content,$conf)	{
		/*
         * Settings
         */
        $this->path = t3lib_extMgm::extPath('px_phpids');   // Define Path to PHP IDS
        $this->debug = false;  // Debug Mode true or false

        // set the include path properly for PHPIDS
        set_include_path(
            get_include_path()
            . PATH_SEPARATOR
            . $this->path
        );

        if (!session_id()) {
            session_start();
        }

        require_once 'IDS/Init.php';

        $content='<h6>PHPIDS</h6>';

        try{
            /*
            * It's pretty easy to get the PHPIDS running
            * 1. Define what to scan
            *
            * Please keep in mind what array_merge does and how this might interfer
            * with your variables_order settings
            */
            $request = array(
                'GET' => $_GET,
                'POST' => $_POST,
                'COOKIE' => $_COOKIE
            );

            $init = IDS_Init::init($this->path.'IDS/Config/Config.ini');

             /**
             * You can also reset the whole configuration
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

            $init->config['General']['base_path'] = $this->path.'IDS/';
            $init->config['General']['use_base_path'] = true;
            $init->config['Caching']['caching'] = 'none';

             // 2. Initiate the PHPIDS and fetch the results
            $ids = new IDS_Monitor($request, $init);
            $result = $ids->run();

            /*
            * That's it - now you can analyze the results:
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
                 *  Impacts start at 1 and leads to 150.
                 *  1-20: Low Impact -> just log it to a file
                 *  21-50: Medium Impact -> log it to a database
                 *  50-99: High Impact -> instantly report it to an admin via email!
                 *  100+: This is a serious hacking attemp -> knock em out!
                 */

                // Works by default
                if($result->getImpact()>0){
                    $compositeLog->addLogger(IDS_Log_File::getInstance($init));     // Log Impact into File (IDS/tmp/phpids_log.txt)
                }

                if($result->getImpact()>20){
                    // Configure DB-Connection in IDS/Config/Config.ini
                    $compositeLog->addLogger(IDS_Log_Database::getInstance($init)); // Log Impact into a Database (tx_pxphpids_log)
                }

                if($result->getImpact()>50){
                    // Configure E-Mail in IDS/Config/Config.ini
                    $compositeLog->addLogger(IDS_Log_Email::getInstance($init));    // Report Impact via E-Mail
                }

                if($result->getImpact()>100){
                    if($this->debug==false){
                        $content = '';  // Delete all informations possibly given by PHP IDS
                    }
                    $content .= '<p>You have been logged out cause of a possible hacking attemp.</p><p>Your data has been stored and reported.</p><p>If you think this is an error please contact the webmaster of this website.</p>';
                    session_destroy();
                    die($content);
                }

                $compositeLog->execute($result);
            } else {
                $content.='<p class="red_box"><a href="?test=%22><script>eval(window.name)</script>">No attack detected - click for an example attack</a></p>';
            }

        } catch (Exception $e) {
            /*
            * sth went terribly wrong - maybe the
            * filter rules weren't found?
            */
            $content.='<p class="error_box">An error occured: '.$e->getMessage().'</p>';
        }

        if($this->debug==true){
            return $content;
        }
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/px_phpids/pi1/class.tx_pxphpids_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/px_phpids/pi1/class.tx_pxphpids_pi1.php']);
}

?>