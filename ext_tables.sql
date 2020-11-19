#
# Table structure for table 'tx_umapublist_domain_model_institute'
#
CREATE TABLE tx_umapublist_domain_model_institute (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	id int(11) DEFAULT '0' NOT NULL,
	name_en varchar(255) DEFAULT '' NOT NULL,
	name_de varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid)

);

#
# Table structure for table 'tx_umapublist_domain_model_chair'
#
CREATE TABLE tx_umapublist_domain_model_chair (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	id int(11) DEFAULT '0' NOT NULL,
	parent int(11) DEFAULT '0' NOT NULL,
	name_en varchar(255) DEFAULT '' NOT NULL,
	name_de varchar(255) DEFAULT '' NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid)

);


#
# Table structure for table 'tx_umapublist_domain_model_publist'
#
CREATE TABLE tx_umapublist_domain_model_publist (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	ce_id int(11) DEFAULT '0' NOT NULL,
	tsconfig text,
	query_url text,
	publications text NOT NULL,
	flexform_md5 varchar(255) DEFAULT '' NOT NULL,
	exclude_external tinyint(4) DEFAULT '0' NOT NULL,
	filter_bwl_research varchar(100) DEFAULT '' NOT NULL,
	filter_bwl_academic varchar(100) DEFAULT '' NOT NULL,
	filter_bwl_national varchar(100) DEFAULT '' NOT NULL,
	filter_bwl_refereed varchar(100) DEFAULT '' NOT NULL,
	filter_publication varchar(1024) DEFAULT '' NOT NULL,
	exclude_eprint_ids text NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
 KEY language (l10n_parent,sys_language_uid)

);


#
# Table structure for table 'tx_umapublist_domain_model_publication'
#
CREATE TABLE tx_umapublist_domain_model_publication (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	eprint_id int(11) DEFAULT '0' NOT NULL,
	title varchar(1000) DEFAULT '' NOT NULL,
	book_title varchar(255) DEFAULT '' NOT NULL,
	abstract text NOT NULL,
	url_offical varchar(1000) DEFAULT '' NOT NULL,
	url_ubma_extern varchar(1000) DEFAULT '' NOT NULL,
	used_link_url varchar(255) DEFAULT '' NOT NULL,
	url varchar(255) DEFAULT '' NOT NULL,
	year int(11) DEFAULT '0' NOT NULL,
	bib_type varchar(255) DEFAULT '' NOT NULL,
	volume varchar(255) DEFAULT '' NOT NULL,
	publisher varchar(255) DEFAULT '' NOT NULL,
	number varchar(255) DEFAULT '' NOT NULL,
	publication varchar(255) DEFAULT '' NOT NULL,
	editors varchar(255) DEFAULT '' NOT NULL,
	creators varchar(1000) DEFAULT '' NOT NULL,
	corp_creators varchar(255) DEFAULT '' NOT NULL,
	event_location varchar(255) DEFAULT '' NOT NULL,
	event_title varchar(255) DEFAULT '' NOT NULL,
	place_of_pub varchar(255) DEFAULT '' NOT NULL,
	page_range varchar(255) DEFAULT '' NOT NULL,
	issn varchar(255) DEFAULT '' NOT NULL,
	isbn varchar(255) DEFAULT '' NOT NULL,
	doi varchar(255) DEFAULT '' NOT NULL,
	urn varchar(255) DEFAULT '' NOT NULL,
	ubma_edition varchar(255) DEFAULT '' NOT NULL,
	ubma_tags varchar(255) DEFAULT '' NOT NULL,
	ubma_forthcoming tinyint(4) unsigned DEFAULT '0' NOT NULL,
	ubma_university varchar(255) DEFAULT '' NOT NULL,
	used_coin text NOT NULL,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(255) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage int(11) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3ver_move_id int(11) DEFAULT '0' NOT NULL,

	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumblob,

	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
 KEY language (l10n_parent,sys_language_uid)

);


