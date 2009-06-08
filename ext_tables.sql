#
# Table structure for table 'tx_pxphpids_log'
#
CREATE TABLE tx_pxphpids_log (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	name tinytext NOT NULL,
	value text NOT NULL,
	page text NOT NULL,
	ip tinytext NOT NULL,
	impact int(11) DEFAULT '0' NOT NULL,
	origin tinytext NOT NULL,
	created tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_pxphpids_cache'
#
CREATE TABLE tx_pxphpids_cache (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	type tinytext NOT NULL,
	created tinytext NOT NULL,
	phpids_data text NOT NULL,
	modified tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);