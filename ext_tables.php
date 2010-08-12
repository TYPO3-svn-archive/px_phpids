<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
$TCA['tx_pxphpids_log'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_log',		
		'label'     => 'uid',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_pxphpids_log.gif',
	),
);

$TCA['tx_pxphpids_cache'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_cache',		
		'label'     => 'uid',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_pxphpids_cache.gif',
	),
);


if (TYPO3_MODE == 'BE') {
	t3lib_extMgm::addModulePath('tools_txpxphpidsM1', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
		
	t3lib_extMgm::addModule('tools', 'txpxphpidsM1', '', t3lib_extMgm::extPath($_EXTKEY) . 'mod1/');
}
@include t3lib_extMgm::extPath($_EXTKEY).'ext_tables_advanced.php';
?>