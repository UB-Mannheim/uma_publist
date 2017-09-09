<?php
namespace Unima\Publist4ubma2\Controller;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Sebastian Kotthoff <sebastian.kotthoff@rz.uni-mannheim.de>, Uni Mannheim
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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


use Unima\Publist4ubma2\Utility\xmlUtil;

/**
 * PublicationController
 */
class PublicationController extends BasicPublistController {

	/**
	 * publicationRepository
	 *
	 * @var \Unima\Publist4ubma2\Domain\Repository\PublicationRepository
	 * @inject
	 */
	protected $publicationRepository = NULL;


	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$publications = $this->publicationRepository->findAll();
		$this->view->assign('publications', $publications);
	}


	/**
	 * return a single Publication as array (key-values)
	 */
	public function get($eprint_id, $settings) {
		$publication = $this->publicationRepository->findFirstByEprintId($eprint_id);
		if ($publication === NULL) {
			$this->debugger->add('Eprint_id' . $eprint_id . ' not found in database');
			$this->errorHandler->setError(1, "skip this");
			return 0;
		}

		/* run here the flexform depending stuff */
		if ($settings['useadvancedtypesbytag']) {
			if ( $publication->getUbmaTags() != "") {
				// implement advanced custom types
				$publication->setBibType($this->decodeAdvancedType($publication->getBibType(), $publication->getUbmaTags()));
				// check, if this type is really needed, otherwise drop publication
				$displayTypes = explode(',', $this->settings['advancedtype']);
				if ((!in_array('all', $displayTypes)) AND (!in_array($publication->getBibType(), $displayTypes))) {
					$this->debugger->add('Type ' . $publication->getBibType() . ' not needed, skip ...');
					$this->errorHandler->setError(1, "skip this");
					return 0;
				}
			}
		}

		/* Select the used URL */
		$URL = $publication->getUrl();
		if ($settings['selecturl'] == 0) {
			if (!$URL || ($URL == ""))
				$URL = $publication->getUrlUbmaExtern();
			if (!$URL || ($URL == ""))
				$URL = $publication->getUrlOffical();
		}
		if (!$URL || ($URL == ""))
			$URL = $this->settingsManager->configValue('extMgn/eprintidUrlPrefix') . '/' . $eprint_id;
		$publication->setUsedLinkUrl($URL); 


		return $publication;
	}



	/**
	 * publication is a single publication as an array (key-values)
	 */
	public function add($publication = array()) {

		//$this->errorHandler->setError(1, 'TestError');
		//$pubs = $this->publicationRepository->findAll();

		// check if ther is a eprintid
		if (intval($publication['eprintid']) <= 0)  {
			$this->debugger->add('no valid eprintid in publication, skip this');
			return;
		}

		//check, if DB is already in DB
		 
		$eprintIdIsInDB = $this->publicationRepository->findFirstByEprintId(intval($publication['eprintid']));
		if ($eprintIdIsInDB === NULL) {
			// add to DB
			$this->debugger->add('== Publication ' . $publication['eprintid'] . ' is NOT in DB, add it ==');
			$pub = $this->objectManager->get('Unima\Publist4ubma2\Domain\Model\Publication');
			$pub->setEprintId(intval($publication['eprintid']));
			$newPub = $this->writeProperties($pub, $publication);
			$this->publicationRepository->add($newPub);
		} else {
			// update only
			$this->debugger->add('== Publication ' . $publication['eprintid'] . ' is in DB, update only ==');
			$pub = $eprintIdIsInDB;
			$newPub = $this->writeProperties($pub, $publication);
			$this->publicationRepository->update($newPub);
		}

		return;		
	}


	private function writeProperties($newPub, $publication) {

		$newPub->setEprintId(intval($publication['eprintid']));

		if ($publication['type'])
		{
/*
// Let us do this in function "get()", before display on page, after reading from DB
// Because Publications could used in multiple publication-lists, with different settings of
// "useadvancedtypesbytag"

			if ($this->settings['useadvancedtypesbytag']) {
				if ( $publication['ubma_tags'])
					// implement advanced custom types
					$newPub->setBibType($this->decodeAdvancedType($publication['type'], $publication['ubma_tags']));
				else {
					$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no ubma_tags - use normal type');
					$newPub->setBibType($publication['type']);
				}
				// check, if this type is really needed, otherwise drop publication
				$displayTypes = explode(',', $this->settings['advancedtype']);
				if ((!in_array('all', $displayTypes)) AND (!in_array($newPub->getBibType(), $displayTypes))) {
					$this->debugger->add('Publication ' . $publication['eprintid'] . ' is skipped because of wrong type');
					return;
				}
			} else
*/
				$newPub->setBibType($publication['type']);
		}
		else {
			$newPub->setBibType($publication['notype']);
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no type');
		}

		if ( $publication['ubma_tags'])
			$newPub->setUbmaTags($publication['ubma_tags']);
		else
			$newPub->setUbmaTags("");
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($publication['title']);
		if ($publication['title']) {
			if (is_string($publication['title'])) {
				$newPub->setTitle($publication['title']);
			} else if ($publication['title']['title']) { //e.g. UniMA
				$newPub->setTitle($publication['title']['title']);
			} else if ($publication['title']['item'] and $publication['title']['item']['name']) { //e.g. LMU
				$newPub->setTitle($publication['title']['item']['name']);
			}
		} else {
			$newPub->setTitle("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no title');
		}

		if ($publication['book_title'])
			$newPub->setBookTitle($publication['book_title']);
		else {
			$newPub->setBookTitle("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no book_title');
		}
		if ($publication['abstract']) {
			if (is_array($publication['abstract']) and $publication['abstract']['abstract'])
				$newPub->setAbstract($publication['abstract']['abstract']);
			else 
				$newPub->setAbstract($publication['abstract']);
		} else {
			$newPub->setAbstract("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no abstract');
		}

		if ($publication['official_url'])
			$newPub->setUrlOffical($publication['official_url']);
		else {
			$newPub->setUrlOffical("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no official_url');
		}

		if ($publication['ubma_url_extern'])
			$newPub->setUrlUbmaExtern($publication['ubma_url_extern']);
		else {
			$newPub->setUrlUbmaExtern("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no ubma_url_extern');
		}

		if (is_object($publication['documents']['document'][0])) {
			$document = xmlUtil::xmlToArray($publication['documents']['document'][0]);
			//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($document);
			if ($document['files']['file']['url']) {
				$newPub->setUrl($document['files']['file']['url']);
			} else {
				$newPub->setUrl("");
				$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no document url');
			}
		}
		else {
			$newPub->setUrl("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no document url');
		}


		// select the usedLinkUrl
		$URL = $newPub->getUrl();
		// if select URL = auto
/*
// Let us do this in function "get()", before display on page, after reading from DB
// Because Publications could used in multiple publication-lists, with different settings of
// "selecturl"


		if ($this->settings['selecturl'] == 0) {
			if (!$URL || ($URL == ""))
				$URL = $publication['ubma_url_extern'];
			if (!$URL || ($URL == ""))
				$URL = $publication['official_url'];
		}
		if (!$URL || ($URL == ""))
			$URL = $this->settingsManager->configValue('extMgn/eprintidUrlPrefix') . '/' . $publication['eprintid'];

		$newPub->setUsedLinkUrl($URL);
		// \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($newPub->getUsedLinkUrl());
*/
		if (intval($publication['ubma_date_year']))
			$newPub->setYear(intval($publication['ubma_date_year']));
		else {
			if (intval($publication['date']))
				$newPub->setYear(intval($publication['date']));
			else {
				$newPub->setYear(9999);
				$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no ubma_date_year and no date');
			}
		}

		if ($publication['volume'])
			$newPub->setVolume($publication['volume']);
		else {
			$newPub->setVolume("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no volume');
		}

		if ($publication['publisher'])
			$newPub->setPublisher($publication['publisher']);
		else {
			$newPub->setPublisher("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no publisher');
		}

		if ($publication['number'])
			$newPub->setNumber($publication['number']);
		else {
			$newPub->setNumber("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no number');
		}

		if ($publication['publication'])
			$newPub->setPublication($publication['publication']);
		else {
			$newPub->setPublication("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no publication');
		}

		if ($publication['editors'])
			$newPub->setEditors($this->decodeAuthors($publication['editors']));
		else {
			$newPub->setEditors("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no editors');
		}


		if ($publication['creators'])
			$newPub->setCreators($this->decodeAuthors($publication['creators']));
		else {
			$newPub->setCreators("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no creators');
		}

		if ($publication['corp_creators'])
			$newPub->setCorpCreators($this->decodeCorpCreators($publication['corp_creators']));
		else {
			$newPub->setCorpCreators("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no corp_creators');
		}

		if ($publication['event_location']) {
			$newPub->setEventLocation($publication['event_location']);
		} else {
			$newPub->setEventLocation("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no event_location');
		}

		if ($publication['place_of_pub']) {
			$newPub->setPlaceOfPub($publication['place_of_pub']);
		} else {
			$newPub->setPlaceOfPub("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no place_of_pub');
		}

		if ($publication['ubma_book_editor']) {
			$newPub->setUbmaBookEditor($publication['ubma_book_editor']['family'] . ',' . $publication['ubma_book_editor']['given']);
		} else {
			$newPub->setUbmaBookEditor("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no ubma_book_editor');
		}

		if ($publication['pagerange']) {
			$newPub->setPageRange($publication['pagerange']);
		} else {
			$newPub->setPageRange("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no pagerange');
		}

		if ($publication['issn']) {
			if (is_string($publication['issn'])) {
				$newPub->setIssn($publication['issn']);
			} else if ($publication['issn']['item']) { //e.g. UniRE
				if (is_array($publication['issn']['item'])) {
					$newPub->setIssn(join(' ; ', $publication['issn']['item']));
				} else {
					$newPub->setIssn($publication['issn']['item']);
				}
			}
		} else {
			$newPub->setIssn("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no issn');
		}


		if ($publication['isbn']) {
			$newPub->setIsbn($publication['isbn']);
		} else {
			$newPub->setIsbn("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no isbn');
		}

		if ($publication['ubma_edition']) {
			$newPub->setUbmaEdition($publication['ubma_edition']);
		} else {
			$newPub->setUbmaEdition("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no ubma_edition');
		}

		if ($publication['id_number']) {
			if (is_string($publication['id_number'])) {
				$newPub->setIdNumber($publication['id_number']);
			} else if ($publication['id_number']['item']) {
				if (is_string($publication['id_number']['item'])) {
					$newPub->setIdNumber($publication['id_number']['item']);
				} else if ($publication['id_number']['item']['name']) { //e.g. UniRE
					$newPub->setIdNumber($publication['id_number']['item']['name']);
				}
			}
		} else {
			$newPub->setIdNumber("");
			$this->debugger->add('Publication ' . $publication['eprintid'] . ' has no idNumber');
		}

		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($newPub);

		// at the end, set the bib coin stuff
		$newPub->setUsedCoin($this->encodeCoin($newPub));


		return $newPub;
	}



	private function decodeAdvancedType($bibType, $ubmaTags) {
		if (strpos($ubmaTags, 'pubtype:workshop', 0) !== false)
			return 'workshop_item';
		if (strpos($ubmaTags, 'pubtype:masterthesis', 0) !== false)
			return 'master_thesis';
		if (strpos($ubmaTags, 'pubtype:poster', 0) !== false)
			return 'poster';

		return $bibType;
	}


	private function decodeAuthors($authors) {
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($authors);

		$counter = 0;
		$retAuthors = '';
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($authors);
		foreach ($authors['item'] as $index => $xml) {

			//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($index);
			//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($xml);

			// only names, don't take Mail-"IDs"
			if (is_object($xml)) {
				if ($index > 0)
					$retAuthors = $retAuthors . ';';
				$name = xmlUtil::xmlToArray($xml);
				$author = $name['name'];
				$retAuthors = $retAuthors . $author['family'] . ',' . $author['given'];
			}
			else
			{
				if ($index == "name") {
					if ($counter > 0)
						$retAuthors = $retAuthors . ';';
							$counter ++;

					$author = $xml;
					$retAuthors = $retAuthors . $author['family'] . ',' . $author['given'];
				}
			}
		}

		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($retAuthors);
		return $retAuthors;
	}


	private function decodeCorpCreators($creators) {
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($creators);
		$corpCreators = "";
		if (is_array($creators['item'])) {
			foreach ($creators['item'] as $index => $xml) {
				if ($index > 0)
					$corpCreators .= ';';
				$corpCreators .= $xml;
			}
		} else 
			$corpCreators = $creators['item'];
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($corpCreators);
		return $corpCreators;
	}



	private function encodeCoin($pub) {
		//The return string contains several key-value-pairs separated
		//by the ampersand, which will be replaced by its HTML code
		//in the end globally. The values are encoded by the
		//percentage-encoding through rawurlencode.
		$coin = "";
		$coin .= "url_ver=Z39.88-2004" . "&ctx_ver=Z39.88-2004";
		
		//type and title
		$titleLabel = "atitle";//default is article title
		switch($pub->getBibType()) {
			case "article":
				$coin .= "&rft_val_fmt=" . rawurlencode("info:ofi/fmt:kev:mtx:journal") . "&rft.genre=article";
				break;
			case "preprint":
				$coin .= "&rft_val_fmt=" . rawurlencode("info:ofi/fmt:kev:mtx:journal") . "&rft.genre=preprint";
				break;
			case "book":
				$coin .= "&rft_val_fmt=" . rawurlencode("info:ofi/fmt:kev:mtx:book") . "&rft.genre=book";
				$titleLabel = "btitle";
				break;
			case "book_section":
				$coin .= "&rft_val_fmt=" . rawurlencode("info:ofi/fmt:kev:mtx:book") . "&rft.genre=bookitem";
				break;
			case "workshop_item":
			case "conference_item":
				$coin .= "&rft_val_fmt=" . rawurlencode("info:ofi/fmt:kev:mtx:book") . "&rft.genre=proceeding";
				break;
			case "report":
				$coin .= "&rft_val_fmt=". rawurlencode("info:ofi/fmt:kev:mtx:book") . "&rft.genre=report";
				$titleLabel = "btitle";
				break;
			case "dissertation":
				$coin .= "&rft_val_fmt=" . rawurlencode("info:ofi/fmt:kev:mtx:dissertation");
				$titleLabel = "title";
				break;
			case "encyclopedia_article":
				$coin .= "&rft_val_fmt=" . rawurlencode("info:ofi/fmt:kev:mtx:dc") . "&rft.type=encyclopediaArticle";
				$titleLabel = "title";
				break;
			default:
				/* Remaining cases:
				"habilitation", "thesis", "master_thesis", "journal", "other", "review", "research_paper", "workshop_item", "poster"
				*/
				$coin .= "&rft_val_fmt=" . rawurlencode("info:ofi/fmt:kev:mtx:book");
				$titleLabel = "title";
		}
		$coin .= "&rft." . $titleLabel . "=" . rawurlencode($pub->getTitle());

		//book_title --> btitle
		if ( $pub->getBookTitle() != "") {
			$coin .= "&rft.btitle=" . rawurlencode($pub->getBookTitle());
		}
		//publication --> jtitle
		if ( $pub->getPublication() != "") {
			if ($titleLabel == "atitle") {
				$coin .= "&rft.jtitle=" . rawurlencode($pub->getPublication());
			} else {
				$coin .= "&rft.series=" . rawurlencode($pub->getPublication());
			}
		}
		
		//ubma_date_year -> date
		if ( $pub->getYear() != "" ) {
			$coin .= "&rft.date=" . rawurlencode($pub->getYear());
		}
		//authors
		// field in DB looks like:
		// "Hollink,Laura;Meilicke,Christian;Nikolov,Andriy;Ritze,Dominique"
		if ( $pub->getCreators() != "" ) {
			$creators = explode(';', $pub->getCreators());
			$num = count($creators);
			for ($i = 0; $i < $num; $i ++) {
				$names = explode(',', $creators[$i]);
				if ($i == 0) {//only for the first author
					$coin .= "&rft.aufirst=" . rawurlencode($names[1]);
					$coin .= "&rft.aulast=" . rawurlencode($names[0]);
				}
				$coin .= "&rft.au=" . rawurlencode($names[1] . " " . $names[0]);
			}
		}
		/*
		// old code
		$authors = $pubListArray["$index"][creators];
		$num = count($authors);
		for ($i = 0; $i < $num; $i ++) {
			if ($i == 0) {//only for the first author
				$coin .= "&rft.aufirst=" . rawurlencode($authors["$i"][name][given]);
				$coin .= "&rft.aulast=" . rawurlencode($authors["$i"][name][family]);
			}
			$coin .= "&rft.au=" . rawurlencode($authors["$i"][name][given] . " " . $authors["$i"][name][family]);
		}
		*/



		//aucorp 
		if ( $pub->getCorpCreators() != "" ) {
			$authors = explode(';', $pub->getCorpCreators());
			$num = count($authors);
			for ($i = 0; $i < $num; $i ++) {
				$coin .= "&rft.aucorp=" . rawurlencode($authors["$i"]);
			}
		}
		//Editors cannot be saved in COinS, because such a field is not available,
		//cf. https://forums.zotero.org/discussion/10838/no-editors-in-coins/

		//volume --> volume
		if ( $pub->getVolume() != "" ) {
			$coin .= "&rft.volume=" . rawurlencode($pub->getVolume());
		}

		//number --> issue
		if ( $pub->getNumber() != "" ) {
			$coin .= "&rft.issue=" . rawurlencode($pub->getNumber());
		}

		//pagerange --> pages
		if ( $pub->getPageRange() != "" ) {
			$coin .= "&rft.pages=" . rawurlencode($pub->getPageRange());
		}
		if ( $pub->getPlaceOfPub() != "" ) {
			$coin .= "&rft.place=" . rawurlencode($pub->getPlaceOfPub());
		}
		if ( $pub->getPublisher() != "" ) {
			$coin .= "&rft.pub=" . rawurlencode($pub->getPublisher());
		}
		if ( $pub->getUbmaEdition() != "" ) {
			$coin .= "&rft.edition=" . rawurlencode($pub->getUbmaEdition());
		}
		if ( $pub->getIssn() != "" ) {
			$coin .= "&rft.issn=" . rawurlencode($pub->getIssn());
		}
		if ( $pub->getIsbn() != "" ) {
			$coin .= "&rft.isbn=" . rawurlencode($pub->getIsbn());
		}

		//more fields could be possible in COinS, e.g. language, degree, inst, ...

		return $coin;
	}


	public function repositoryFindAll() {
		return $this->publicationRepository->findAll();
	}

	public function repositoryRemove($publication) {
		return $this->publicationRepository->remove($publication);
	}


}
