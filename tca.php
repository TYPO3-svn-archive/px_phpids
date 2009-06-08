<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_pxphpids_log"] = array (
	"ctrl" => $TCA["tx_pxphpids_log"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "name,value,page,ip,impact,origin,created"
	),
	"feInterface" => $TCA["tx_pxphpids_log"]["feInterface"],
	"columns" => array (
		"name" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_log.name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"value" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_log.value",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",
			)
		),
		"page" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_log.page",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",
			)
		),
		"ip" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_log.ip",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"impact" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_log.impact",		
			"config" => Array (
				"type"     => "input",
				"size"     => "4",
				"max"      => "4",
				"eval"     => "int",
				"checkbox" => "0",
				"range"    => Array (
					"upper" => "1000",
					"lower" => "10"
				),
				"default" => 0
			)
		),
		"origin" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_log.origin",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"created" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_log.created",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "name;;;;1-1-1, value, page, ip, impact, origin, created")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);



$TCA["tx_pxphpids_cache"] = array (
	"ctrl" => $TCA["tx_pxphpids_cache"]["ctrl"],
	"interface" => array (
		"showRecordFieldList" => "type,created,phpids_data,modified"
	),
	"feInterface" => $TCA["tx_pxphpids_cache"]["feInterface"],
	"columns" => array (
		"type" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_cache.type",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"created" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_cache.created",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"phpids_data" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_cache.phpids_data",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",
			)
		),
		"modified" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:px_phpids/locallang_db.xml:tx_pxphpids_cache.modified",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
	),
	"types" => array (
		"0" => array("showitem" => "type;;;;1-1-1, created, phpids_data, modified")
	),
	"palettes" => array (
		"1" => array("showitem" => "")
	)
);
?>