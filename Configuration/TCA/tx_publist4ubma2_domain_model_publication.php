<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication',
		'label' => 'eprint_id',
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
		'searchFields' => 'eprint_id,title,abstract,url_offical,url_ubma_extern,used_link_url,url,year,bib_type,volume,publisher,number,publication,editors,creators,corp_creators,ubma_book_editor,event_location,place_of_pub,page_range,issn,isbn,ubma_tags,ubma_edition,book_title,used_coin,id_number',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('publist4ubma2') . 'Resources/Public/Icons/tx_publist4ubma2_domain_model_publication.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, eprint_id, title, abstract, url_offical, url_ubma_extern, used_link_url, url, year, bib_type, volume, publisher, number, publication, editors, creators, corp_creators, ubma_book_editor, event_location, place_of_pub, page_range, issn, isbn, ubma_tags, ubma_edition, book_title, used_coin, id_number',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, eprint_id, title, abstract, url_offical, url_ubma_extern, used_link_url, url, year, bib_type, volume, publisher, number, publication, editors, creators, corp_creators, ubma_book_editor, event_location, place_of_pub, page_range, issn, isbn, ubma_tags, ubma_edition, book_title, used_coin,id_number , --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, starttime, endtime'),
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
				'foreign_table' => 'tx_publist4ubma2_domain_model_publication',
				'foreign_table_where' => 'AND tx_publist4ubma2_domain_model_publication.pid=###CURRENT_PID### AND tx_publist4ubma2_domain_model_publication.sys_language_uid IN (-1,0)',
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

		'eprint_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.eprint_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'eprint_id_url' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.eprint_id_url',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'book_title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.bookTitle',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),

		'abstract' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.abstract',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 15,
				'eval' => 'trim'
			)
		),
		'url_offical' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.url_offical',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'url_ubma_extern' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.url_ubma_extern',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'used_link_url' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.usedLinkUrl',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'url' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.url',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'year' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.year',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'bib_type' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.bib_type',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'volume' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.volume',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'publisher' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.publisher',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'number' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.number',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'publication' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.publication',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'editors' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.editors',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'creators' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.creators',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'corp_creators' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.corpCreators',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),

		'ubma_book_editor' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.ubma_book_editor',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'event_location' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.eventLocation',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'place_of_pub' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.placeOfPub',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'page_range' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.pageRange',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'issn' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.issn',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'isbn' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.isbn',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'ubma_tags' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.ubmaTags',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'ubma_edition' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.ubmaEdition',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'used_coin' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.usedCoin',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 5,
				'eval' => 'trim'
			),
		),
		'id_number' => array(
                        'exclude' => 1,
                        'label' => 'LLL:EXT:publist4ubma2/Resources/Private/Language/locallang_db.xlf:tx_publist4ubma2_domain_model_publication.idNumber',
                        'config' => array(
                                'type' => 'input',
                                'size' => 30,
                                'eval' => 'trim'
                        ),
                ),
	),
);
