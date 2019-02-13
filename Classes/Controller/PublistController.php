<?php
namespace UMA\UmaPublist\Controller;


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

use UMA\UmaPublist\Utility\queryUrl;
use UMA\UmaPublist\Utility\fileReader;
use UMA\UmaPublist\Utility\xmlUtil;
use UMA\UmaPublist\Utility\GeneralUtility;

/**
 * PublistController
 */
class PublistController extends BasicPublistController {


	/**
	 * publistRepository
	 *
	 * @var \UMA\UmaPublist\Domain\Repository\PublistRepository
	 * @inject
	 */
	protected $publistRepository = NULL;


	/**
	 * publicationController
	 *
	 * @var \UMA\UmaPublist\Controller\PublicationController
	 * @inject
	 */
	protected $publicationController = NULL;

	protected $sessionData = array('year' => 0, 'type' => "");

	protected $contentByTypes = [];

	protected $contentByYears = [];

	protected $contentByTypesAndYears = [];


	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {

		//$debugger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('UMA\\UmaPublist\\Service\\DebugCollector');
		$GLOBALS['TSFE']->additionalFooterData['tx_'.$this->request->getControllerExtensionKey()] = '<script type="text/javascript" src="' . \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()) . 'Resources/Public/JavaScript/uma_publist.js"></script>';
		$this->debugger->add('Started PublistController listAction');

		// check, if ContentElement is already in DB
		$cObj = $this->configurationManager->getContentObject();
		$cElementId = $cObj->data['uid'];

        // parse content element's flexform content
        $flexformSettings = GeneralUtility::parseFlexForm($cObj->data['pi_flexform'], 'settings');
        GeneralUtility::getInstitutesAndChairs($flexformSettings, $institutesAssoc, $chairsAssoc);
        if(!$flexformSettings['title'] && !$flexformSettings['author'] && !count($chairsAssoc)) {
            $errorMsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate( 'error.select_title_author_or_department', 'uma_publist' );
            $this->view->assign('errorMsg', $errorMsg);
            $this->errorHandler->setError(1, $errorMsg);
            return;
        }

		$this->checkIfRebuildPage($cElementId);
		if ($this->errorHandler->getError()) {
			$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
			if ($this->settings['debug'])
				$this->view->assign('debugMsg', $this->debugger->get());
			return;
		}

		// get Publist from DB
		$content = $this->getPublicationsFromList($cElementId);
		if ($this->errorHandler->getError()) {
			$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
			if ($this->settings['debug']) {
				$this->view->assign('debugMsg', $this->debugger->get());
			}
			return;
		}
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($content);

		// sorting publications
		$years = $this->listOfYears($content);
		$types = $this->listOfTypes($content);

		$groupedContent = $this->groupContent($content);

		// sort in the same order as the array $years and $types
		//$content = $this->sortPublications($content, $years);

		$this->initSessionData($years, $types, $content);

		$this->debugger->add('all OK');

		$this->view->assignMultiple([
			'data' => $this->configurationManager->getContentObject()->data,
			'content' => $content,
			'groupedContent' => $groupedContent,
			'years' => $years,
			'types' => $types,
			'curYear' => $this->sessionData['year'],
			'curType' => $this->sessionData['type'],
		]);

		if ($this->settings['bibtex'] > 0) {
			$this->view->assign('bibtexturl',  queryUrl::generate($this->settings, 1));
		}

		if ($this->settings['debug']) {
			$this->view->assign('debugMsg', $this->debugger->get());
		}
	}


	private function getPublicationsFromList($cElementId) {
		$publist = $this->publistRepository->findFirstByCEid($cElementId);
		if ($publist === NULL) {
			$this->errorHandler->setError(1, "Unable to find publication list in DB for content element " . $cElementId);
			return 0;
		}
		$publications = $publist->getPublications();
		if ($publications == '') {
			$this->errorHandler->setError(1, "No publications in publication list");
			return 0;
		}
		$content = array();
		$publicationList = explode(',', $publications);
		foreach ($publicationList as $publicationString) {
			$eprint_id = intval($publicationString);
			if ($eprint_id == 0) {
				$this->debugger->add('Could not read eprint_id from ' . $publicationString);
				continue;
			}
			$publication = $this->publicationController->get($eprint_id, $this->settings);
			if ($this->errorHandler->getError()) {
				// clear the error and skip
				$this->errorHandler->setError(0, "");
				continue;
			}
			array_push($content, $publication);
		}
		if (count($content) <= 0) {
			$this->errorHandler->setError(1, "No valid publications found");
			return 0;
		}
		return $content;
	}


	private function checkIfRebuildPage($cElementId) {
		//md5sum of flexform:
		$newMd5 = md5(implode($this->settings));

		$isInDB = $this->publistRepository->findFirstByCEid($cElementId);
		// if a Content element is there, check the md5sum
		if ($isInDB === NULL) {
			$this->debugger->add('No Publist in DB, load it ...');
			$this->updatePublist($cElementId, $newMd5, $isInDB);
			return;
		}
		if ($newMd5 != $isInDB->getFlexformMd5()) {
			$this->debugger->add('FlexForm Changed, reload Publist ...');
			$this->updatePublist($cElementId, $newMd5, $isInDB);
			return;
		}
		if (($this->settings['usecache'] == 0) && ($this->settings['forceUseCache'] == 0)) {
			$this->debugger->add('Using cache is disabled, reload Publist ...');
			$this->updatePublist($cElementId, $newMd5, $isInDB);
			return;
		}

		$this->debugger->add('No need to reload Publist, taking it from DB ...');
		return;
	}



	// running from Frontend, Flexform Settings readable
	private function updatePublist($cElementId, $md5sum, $publist)
	{

		$url = queryUrl::generate($this->settings, 0);
		if ($this->errorHandler->getError())
			return;

		$this->debugger->add('Query URL: ' . $url);

		$xmlString = fileReader::downloadFile($url);
		if ($this->errorHandler->getError())
			return;

		$publications = $this->extractPublicationsFromXML($xmlString, $this->settings);
		if ($this->errorHandler->getError())
			return;

        // store/update Publist in Repository
        $isNewPublist = false;
        if ($publist === NULL) {
            // add to DB
            $publist = $this->objectManager->get('UMA\UmaPublist\Domain\Model\Publist');
            $publist->setCeId($cElementId);
            $isNewPublist = true;
        }
        $this->debugger->add('== Publist ' . $cElementId . ($isNewPublist ? ' is NOT in DB, add it' : ' is in DB, update only') . ' ==');
        $publist->setQueryUrl($url);
        $publist->setExcludeExternal($this->settings['excludeexternal']);
        $publist->setFilterPublication($this->settings['publication']);
        $publist->setExcludeEprintIds($this->settings['excludeEprintIds']);
        $publist->setFilterBwlResearch($this->settings['bwlResearch']);
        $publist->setFilterBwlAcademic($this->settings['bwlAcademic']);
        $publist->setFilterBwlNational($this->settings['bwlNational']);
        $publist->setFilterBwlRefereed($this->settings['bwlRefereed']);
        $publist->setFlexformMd5($md5sum);
        $publist->setPublications($this->listOfEprintIds($publications));
        $isNewPublist ? $this->publistRepository->add($publist) : $this->publistRepository->update($publist);

		# Den Vorschlaghammer instanzieren / aus der Kiste kramen
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
 		# Mit dem Vorschlaghammer in die Datenbank speichern / Nägel mit Köpfen machen
		$persistenceManager->persistAll();

	}

	// running from Backend (task Scheduler), Flexform Settings not Reachable
	public function taskUpdatePublist($publist)
	{
		$url = $publist->getQueryUrl();

		$xmlString = fileReader::downloadFile($url);
		if ($this->errorHandler->getError())
			return;

        $settings = [
            'excludeexternal' => $publist->getExcludeExternal(),
            'publication' => $publist->getFilterPublication(),
            'excludeEprintIds' => $publist->getExcludeEprintIds(),
            'bwlResearch' => $publist->getFilterBwlResearch(),
            'bwlAcademic' => $publist->getFilterBwlAcademic(),
            'bwlNational' => $publist->getFilterBwlNational(),
            'bwlRefereed' => $publist->getFilterBwlRefereed()
        ];

		$publications = $this->extractPublicationsFromXML($xmlString, $settings);
		if ($this->errorHandler->getError())
			return;

		$publist->setPublications($this->listOfEprintIds($publications));
		$this->publistRepository->update($publist);

		return;
	}



	private function extractPublicationsFromXML($data, $settings = [])
	{
        $excludeExternal = array_key_exists('excludeexternal', $settings) ? $settings['excludeexternal'] : false;
        $filterPublication = array_key_exists('publication', $settings) ? $settings['publication'] : '';
        $excludeEprintIds = array_key_exists('excludeEprintIds', $settings) ? $settings['excludeEprintIds'] : '';
        $filterBwlResearch = array_key_exists('bwlResearch', $settings) ? $settings['bwlResearch'] : '';
        $filterBwlAcademic = array_key_exists('bwlAcademic', $settings) ? $settings['bwlAcademic'] : '';
        $filterBwlNational = array_key_exists('bwlNational', $settings) ? $settings['bwlNational'] : '';
        $filterBwlRefereed = array_key_exists('bwlRefereed', $settings) ? $settings['bwlRefereed'] : '';

		//$debugger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('UMA\\UmaPublist\\Service\\DebugCollector');
		$publications = [];

        $filterPublicationRegExp = '~'.preg_replace(['#\s*\|\s*#', '#([$^.*+?(){}[\]~])#'], ['|', '\\\\$1'], trim($filterPublication)).'~i';
        $excludeEprintIdArray = array_filter(explode(',', $excludeEprintIds), 'is_numeric');
        $filterBwlResearchArray = array_diff(explode(',', $filterBwlResearch), ['']);
        $filterBwlAcademicArray = array_diff(explode(',', $filterBwlAcademic), ['']);
        $filterBwlNationalArray = array_diff(explode(',', $filterBwlNational), ['']);
        $filterBwlRefereedArray = array_diff(explode(',', $filterBwlRefereed), ['']);

		$xml = \simplexml_load_string($data);
		if ($xml === FALSE) {
				$this->errorHandler->setError(1, 'Could load XML from Bib (not valid xml?)');
				return $publications;
		}

		if ($xml->count() <= 0) {
				$errorMsg = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate( 'error.no_publications_found', 'uma_publist' );
				$this->errorHandler->setError(1, $errorMsg);
				return $publications;
		}
		$this->debugger->add('Found ' . $xml->count() . ' items in xml');

		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->settings);

		for ($item = 0; $item < $xml->count(); $item ++) {

			$pub = xmlUtil::xmlToArray($xml->eprint[$item]);

			// check if we have to exclude external Publications
			if (($excludeExternal) && ($pub['ubma_external'] == "TRUE"))
				continue;

            if($filterPublication) {
                if(!preg_match($filterPublicationRegExp, $pub['publication'])) {
                    continue;
                }
            }

            if($excludeEprintIds) {
                if(in_array($pub['eprintid'], $excludeEprintIdArray)) {
                    continue;
                }
            }

            if($filterBwlResearchArray) {
                if(!((in_array('unspecified', $filterBwlResearchArray) && (!array_key_exists('ubma_bwl_research', $pub) || !$pub['ubma_bwl_research'])) || in_array($pub['ubma_bwl_research'], $filterBwlResearchArray))) {
                    continue;
                }
            }

            if($filterBwlAcademicArray) {
                if(!((in_array('unspecified', $filterBwlAcademicArray) && (!array_key_exists('ubma_bwl_academic', $pub) || !$pub['ubma_bwl_academic'])) || in_array($pub['ubma_bwl_academic'], $filterBwlAcademicArray))) {
                    continue;
                }
            }

            if($filterBwlNationalArray) {
                if(!((in_array('unspecified', $filterBwlNationalArray) && (!array_key_exists('ubma_bwl_national', $pub) || !$pub['ubma_bwl_national'])) || in_array($pub['ubma_bwl_national'], $filterBwlNationalArray))) {
                    continue;
                }
            }

            if($filterBwlRefereedArray) {
                if(!((in_array('unspecified', $filterBwlRefereedArray) && (!array_key_exists('ubma_bwl_refereed', $pub) || !$pub['ubma_bwl_refereed'])) || in_array($pub['ubma_bwl_refereed'], $filterBwlRefereedArray))) {
                    continue;
                }
            }

			$this->publicationController->add($pub);
			array_push($publications, $pub);

		}

		return $publications;
	}



	private function listOfEprintIds($publications) {
		$listOfIDs = "";
		foreach ($publications as $publication) {
			$listOfIDs .=  $publication['eprintid'] . ",";
		}
		$listOfIDs = rtrim($listOfIDs, ",");
		return $listOfIDs;
	}

	private function groupContent($content) {
		// get types from flexform
		if ($this->settings['useadvancedtypesbytag']) {
			$confTypes = explode(',', $this->settings['advancedtype']);
		}
		else {
			$confTypes = explode(',', $this->settings['type']);
		}

		// init grouped content with flexform types to establish correct order
		if($this->settings['splittypes']) {
			foreach($confTypes as $type) {
				$groupedContent[$type] = [];
			}
		}

		foreach($content as $publication) {
			$type = $publication->getBibType();
			$year = $publication->getYear();
			if($this->settings['splittypes'] && $this->settings['splityears'] && $type && $year) {
				// group by types, then years, add letter y to year index to allow access via FLUID
				$groupedContent[$type]['y'.$year][] = $publication;
			}
			else if($this->settings['splittypes'] && $type) {
				// group by types only
				$groupedContent[$type][] = $publication;
			}
			else if($this->settings['splityears'] && $year) {
				// group by years, add letter y to year index to allow access via FLUID
				$groupedContent['y'.$year][] = $publication;
			}
		}
		return $groupedContent;
	}

	private function listOfYears($content) {
		$years = array();
		foreach ($content as $publication) {
			$year = $publication->getYear();
			if ((!in_array($year, $years)) && ($year > 0))
				array_push($years, $publication->getYear());
		}

		if ($this->settings['ordering'] == 0)
			// latest first
			rsort($years);
		else
			// oldest first
			sort($years);

		return $years;
	}

	private function listOfTypes($content) {
		$types = array();
		$tmpTypes = array();
		foreach ($content as $publication) {
			if (!in_array($publication->getBibType(), $tmpTypes))
				array_push($tmpTypes, $publication->getBibType());
		}

		// sort types like in flexform selected
		if ($this->settings['useadvancedtypesbytag'])
			$confTypes = explode(',', $this->settings['advancedtype']);
		else
			$confTypes = explode(',', $this->settings['type']);

		// first put conftypes in
		foreach ($confTypes as $confType) {
			if (in_array($confType, $tmpTypes))
				array_push($types, $confType);
		}
		// now put rest of available Types
		foreach ($tmpTypes as $tmpType) {
			if (!in_array($tmpType, $types))
				array_push($types, $tmpType);
		}
		return $types;
	}


/*

	// sort in the same order as the array $years
	// this is NEEDED, that the fluid is working correctly
	private function _sortPublications($content, $years, $types) {
		$tmpPublications = array();

		if ($this->settings['splittypes'] > $this->settings['splityears']) {
			// we first have to sort Year, than Type, that Type is correct
			$tmpPublications = $this->sortPublicationsYear($content, $years);
			$tmpPublications = $this->sortPublicationsType($content, $types);

		}
		else {
			// we first have to sort Type, than Year, that Year is correct
			$tmpPublications = $this->sortPublicationsType($content, $types);
			$tmpPublications = $this->sortPublicationsYear($content, $years);
		}

		return $tmpPublications;

	}


	private function _sortPublicationsYear($publications, $years) {
		$tmpPublications = array();

		foreach ($years as $year) {
			foreach ($publications as $publication) {
				if ($publication->getYear() == $year)
					array_push($tmpPublications, $publication);
			}
		}
		return $tmpPublications;
	}
	private function _sortPublicationsType($publications, $types) {
		$tmpPublications = array();

		foreach ($types as $type) {
			foreach ($publications as $publication) {
				if ($publication->getBibType() == $type)
					array_push($tmpPublications, $publication);
			}
		}
		return $tmpPublications;
	}
*/

	// Session + SessionVariables
	private function initSessionData($years, $types, $publications) {
		$this->sessionData['year'] = $years[0];
		if ($this->request->hasArgument('year')) {
			$year = $this->request->getArgument('year');
			if (isset($year) && ($year != 0))
				$this->sessionData['year'] = $year;
		}

		// check, if type is available this year
		if (($this->settings['splityears'] == 2 ) && ($this->settings['splittypes'] == 2)) {
			$found = 0;
			foreach( $types as $tmptype) {
				foreach ( $publications as $publication) {
					if (($tmptype == $publication->getBibType()) && ($this->sessionData['year'] == $publication->getYear())) {
						$this->sessionData['type'] = $tmptype;
						$found = 1;
						break;
					}
				}
				if ($found)
					break;
			}

		} else
			$this->sessionData['type'] = $types[0];

		// override type with session data
		if ($this->request->hasArgument('type')) {
			$type = $this->request->getArgument('type');
			if (isset($type) && ($type != ""))
				$this->sessionData['type'] = $type;
		}

	}



	// Repository-Wrappers
	public function repositoryFindAll() {
		return $this->publistRepository->findAll();
	}
	public function repositoryRemove($publist) {
		return $this->publistRepository->remove($publist);
	}


}

