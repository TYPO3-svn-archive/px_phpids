<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Pascal Naujoks <pascal.naujoks@pixabit.de>
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


	// DEFAULT initialization of a module [BEGIN]
unset($MCONF);
require_once('conf.php');
require_once($BACK_PATH.'init.php');
require_once($BACK_PATH.'template.php');

$LANG->includeLLFile('EXT:px_phpids/mod1/locallang.xml');
require_once(PATH_t3lib.'class.t3lib_scbase.php');
$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]



/**
 * Module 'PHP IDS' for the 'px_phpids' extension.
 *
 * @author	Pascal Naujoks <pascal.naujoks@pixabit.de>
 * @package	TYPO3
 * @subpackage	tx_pxphpids
 */
class  tx_pxphpids_module1 extends t3lib_SCbase {
				var $pageinfo;

				/**
				 * Initializes the Module
				 * @return	void
				 */
				function init()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					parent::init();

					/*
					if (t3lib_div::_GP('clear_all_cache'))	{
						$this->include_once[] = PATH_t3lib.'class.t3lib_tcemain.php';
					}
					*/
				}

				/**
				 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
				 *
				 * @return	void
				 */
				function menuConfig()	{
					global $LANG;
					$this->MOD_MENU = Array (
						'function' => Array (
							'1' => $LANG->getLL('function1'),
							'2' => $LANG->getLL('function2'),
							'3' => $LANG->getLL('function3'),
                            #'4' => $LANG->getLL('function4'),
						)
					);
					parent::menuConfig();
				}

				/**
				 * Main function of the module. Write the content to $this->content
				 * If you chose "web" as main module, you will need to consider the $this->id parameter which will contain the uid-number of the page clicked in the page tree
				 *
				 * @return	[type]		...
				 */
				function main()	{
					global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;

					// Access check!
					// The page will show only if there is a valid page and if this page may be viewed by the user
					$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
					$access = is_array($this->pageinfo) ? 1 : 0;

					if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id))	{

							// Draw the header.
						$this->doc = t3lib_div::makeInstance('mediumDoc');
						$this->doc->backPath = $BACK_PATH;
						$this->doc->form='<form action="" method="POST">';

							// JavaScript
						$this->doc->JScode = '
							<script language="javascript" type="text/javascript">
								script_ended = 0;
								function jumpToUrl(URL)	{
									document.location = URL;
								}
							</script>
						';
						$this->doc->postCode='
							<script language="javascript" type="text/javascript">
								script_ended = 1;
								if (top.fsMod) top.fsMod.recentIds["web"] = 0;
							</script>
						';

						$headerSection = '<img alt="'.$LANG->getLL('title').'" title="'.$LANG->getLL('title').'" src="moduleicon.gif"/> <i>PHP Intrusion Detection System</i>';

						$this->content.=$this->doc->startPage($LANG->getLL('title'));
						$this->content.=$this->doc->header($LANG->getLL('title'));
						$this->content.=$this->doc->spacer(5);
						$this->content.=$this->doc->section('',$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,'SET[function]',$this->MOD_SETTINGS['function'],$this->MOD_MENU['function'])));
						$this->content.=$this->doc->divider(5);


						// Render content:
						$this->moduleContent();

						// ShortCut
						if ($BE_USER->mayMakeShortcut())	{
							$this->content.=$this->doc->spacer(20).$this->doc->section('',$this->doc->makeShortcutIcon('id',implode(',',array_keys($this->MOD_MENU)),$this->MCONF['name']));
						}

						$this->content.=$this->doc->spacer(10);
					} else {
							// If no access or if ID == zero

						$this->doc = t3lib_div::makeInstance('mediumDoc');
						$this->doc->backPath = $BACK_PATH;

						$this->content.=$this->doc->startPage($LANG->getLL('title'));
						$this->content.=$this->doc->header($LANG->getLL('title'));
						$this->content.=$this->doc->spacer(5);
						$this->content.=$this->doc->spacer(10);
					}
				}

				/**
				 * Prints out the module HTML
				 *
				 * @return	void
				 */
				function printContent()	{
					$this->content.=$this->doc->endPage();
					echo $this->content;
				}

				/**
				 * Generates the module content
				 *
				 * @return	void
				 */
    function moduleContent()	{
		global $LANG,$TYPO3_CONF_VARS;

        $content .= '
             <style>
                #ext-px-phpids-mod1-index-php .typo3-mediumDoc { width: 100%; }
                #ext-px-phpids-mod1-index-php .typo3-mediumDoc form { padding:10px; }
                #ext-px-phpids-mod1-index-php p { margin-bottom:1.5em; font-style:italic; }
                TABLE.maintable TH { padding: 0px 2px 0px 2px; }
                TABLE.maintable TH A { color: #000000; text-decoration: none; }
                TABLE.maintable TH A:HOVER { color: #555555; }
                TABLE.maintable TD { padding: 0px 2px 0px 2px; }
                TABLE.maintable TD A { color: #555555; text-decoration: underline; }
                TABLE.maintable TD A:HOVER { color: #000000; }
             </style>
        ';

		switch((string)$this->MOD_SETTINGS['function'])	{

			case 1: // Show records from px_phpids_log
					if (isset($_REQUEST["order"]) && $_REQUEST["order"]!='') {
						$req_ord = substr(urldecode($_REQUEST["order"]),0,strpos(urldecode($_REQUEST["order"]),' '));
						$req_dir = trim(substr(urldecode($_REQUEST["order"]),strpos(urldecode($_REQUEST["order"]),' ')));
						$order = urldecode($_REQUEST["order"]);
					} else {
						$req_ord = 'created';
						$req_dir = 'DESC';
						$order = "created DESC";
					}

                    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'tx_pxphpids_log', 'DELETED=0', '', $order, '');
                    if ($GLOBALS["TYPO3_DB"]->sql_num_rows($res)>0) {
						if ($req_ord != false) {
							if ($req_dir == 'DESC') {
								$direction = urlencode(' ASC');
								$arrow = '<img src="arrow_up.gif" alt="UP" />';
							} else {
								$direction = urlencode(' DESC');
								$arrow = '<img src="arrow_down.gif" alt="DOWN" />';
							}
						}

						$content .= '
									<table cellpadding="0" cellspacing="0" border="1" width="100%" class="maintable">
									 <tr>
										<th><a href="index.php?order=name'.($req_ord=='name' ? $direction : urlencode(' ASC')).'">Name '.($req_ord=='name' ? $arrow : '').'</a></th>
										<th><a href="index.php?order=value'.($req_ord=='value' ? $direction : urlencode(' ASC')).'">Value '.($req_ord=='value' ? $arrow : '').'</a></th>
										<th><a href="index.php?order=page'.($req_ord=='page' ? $direction : urlencode(' ASC')).'">Page '.($req_ord=='page' ? $arrow : '').'</a></th>
										<th><a href="index.php?order=ip'.($req_ord=='ip' ? $direction : urlencode(' ASC')).'">IP '.($req_ord=='ip' ? $arrow : '').'</a></th>
										<th><a href="index.php?order=origin'.($req_ord=='paymenttitle' ? $direction : urlencode(' ASC')).'">Origin '.($req_ord=='origin' ? $arrow : '').'</a></th>
										<th><a href="index.php?order=created'.($req_ord=='created' ? $direction : urlencode(' ASC')).'">Created '.($req_ord=='created' ? $arrow : '').'</a></th>
                                        <th><a href="index.php?order=impact'.($req_ord=='impact' ? $direction : urlencode(' DESC')).'">Impact '.($req_ord=='impact' ? $arrow : '').'</a></th>
									 </tr>
						';

						while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
							$content .= '
									 <tr>
										<td>'.htmlspecialchars($row["name"]).'&nbsp;</td>
										<td>'.htmlspecialchars($row["value"]).'&nbsp;</td>
										<td><a href="http://'.$_SERVER['SERVER_NAME'].$row["page"].'" target="_blank" title="'.$LANG->getLL('open_link').'">'.htmlspecialchars($row["page"]).'</a>&nbsp;</td>
										<td align="right">&nbsp;<a href="http://www.ip2location.com/'.$row["ip"].'" target="_blank" title="'.$LANG->getLL('attack_from').'">'.$row["ip"].'</a></td>
										<td align="right">&nbsp;<a href="http://www.ip2location.com/'.$row["origin"].'" target="_blank" title="'.$LANG->getLL('attack_to').'">'.$row["origin"].'</td>
										<td align="right">&nbsp;'.$row["created"].'</td>
                                        <td style="text-align:right; font-size:1.5em; background-color:'.$this->giveColor($row["impact"]).';">&nbsp;'.$row["impact"].'</td>
									 </tr>
							';
						}

						$content .= '
										 </table>
						';
					} else {
						$content .= '<p>'.$LANG->getLL('sql_no_data').'</p>';
                        $content .= '<p>'.$LANG->getLL('function1_text_check').'</p>';
					}

                $this->content.=$this->doc->section($LANG->getLL('function1'),$content,0,1);
				break;

			case 2: // Function to set all recors in tx_pxphpids_log to deleted=1
                if((string)$_REQUEST['del']=='true'){
                    $res = $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tx_pxphpids_log', '1', array('deleted'=>1));
                    if($res){
                        $content.=$LANG->getLL('function2_text_del_ok');
                    }else{
                        $content.=$LANG->getLL('sql_exec_error').mysql_error();
                    }
                }else{
                    $content.='<p>'.$LANG->getLL('function2_text').'</p>';
                    $content.='[ <a href="index.php?del=true">Yes</a> ]';
                }

				$this->content.=$this->doc->section($LANG->getLL('function2'),$content,0,1);
				break;

			case 3: // Function to truncate the tx_pxphpids_cache table
                if((string)$_REQUEST['del']=='true'){
                    $sql = 'TRUNCATE tx_pxphpids_cache';
					$res = mysql(TYPO3_db,$sql);
                    if($res){
                        $content.=$LANG->getLL('function3_text_del_ok');
                    }else{
                        $content.=$LANG->getLL('sql_exec_error').mysql_error();
                    }
                }else{
                    $content.='<p>'.$LANG->getLL('function3_text').'</p>';
                    $content.='[ <a href="index.php?del=true">Yes</a> ]';
                }

				$this->content.=$this->doc->section($LANG->getLL('function3'),$content,0,1);
				break;

            case 4: // Function to show the settings
                $content.='<p>'.$LANG->getLL('function4_text').'</p>';
                $this->content.=$this->doc->section($LANG->getLL('function4'),$content,0,1);
                break;
		}
	}

    function giveColor($impact){
        if($impact>=100) return '#ff0000; font-weight:bold';
        if($impact>=50)  return '#BD143C; font-weight:bold';
        if($impact>=25)  return '#ED254D';
        if($impact>=10)  return '#FCE6E6';
        return 'transparent';
    }
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/px_phpids/mod1/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/px_phpids/mod1/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_pxphpids_module1');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>