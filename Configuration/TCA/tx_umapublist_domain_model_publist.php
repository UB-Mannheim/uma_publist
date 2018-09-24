<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist',
		'label' => 'ce_id',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'ce_id,query_url,publications,flexform_md5,exclude_external,',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('uma_publist') . 'Resources/Public/Icons/tx_umapublist_domain_model_publist.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, ce_id, query_url, publications, flexform_md5,exclude_external,filter_publication,exclude_eprint_ids',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, ce_id, query_url, publications, flexform_md5, exclude_external, filter_publication, exclude_eprint_ids, filter_bwl_research, filter_bwl_academic, filter_bwl_national, filter_bwl_refereed, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
	
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_umapublist_domain_model_publication',
				'foreign_table_where' => 'AND tx_umapublist_domain_model_publication.pid=###CURRENT_PID### AND tx_umapublist_domain_model_publication.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),

		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
	
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'ce_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist.ceid',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),

		'query_url' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist.query_url',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'publications' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist.publications',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			),
		),
		'flexform_md5' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist.ffmd5',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'exclude_external' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist.exclude_external',
			'config' => array(
				'type' => 'check',
			),
		),
		'filter_publication' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist.filter_publication',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 1024,
				'eval' => 'trim',
			),
		),
		'exclude_eprint_ids' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist.exclude_eprint_ids',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim',
			),
		),
		'filter_bwl_research' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist.filter_bwl_research',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 100,
				'eval' => 'trim',
			),
		),
		'filter_bwl_academic' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist.filter_bwl_academic',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 100,
				'eval' => 'trim',
			),
		),
		'filter_bwl_national' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist.filter_bwl_national',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 100,
				'eval' => 'trim',
			),
		),
		'filter_bwl_refereed' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:uma_publist/Resources/Private/Language/locallang_db.xlf:tx_umapublist_domain_model_publist.filter_bwl_refereed',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 100,
				'eval' => 'trim',
			),
		),

	),
);
