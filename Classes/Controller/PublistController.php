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

use Unima\Publist4ubma2\Utility\queryUrl;
use Unima\Publist4ubma2\Utility\fileReader;
use Unima\Publist4ubma2\Utility\xmlUtil;

/**
 * PublistController
 */
class PublistController extends BasicPublistController {


	/**
	 * publistRepository
	 *
	 * @var \Unima\Publist4ubma2\Domain\Repository\PublistRepository
	 * @inject
	 */
	protected $publistRepository = NULL;


	/**
	 * publicationController
	 *
	 * @var \Unima\Publist4ubma2\Controller\PublicationController
	 * @inject
	 */
	protected $publicationController = NULL;


	protected $sessionData = array('year' => 0, 'type' => "");


	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {

		$years = array();
		$types = array();

                //$debugger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Unima\\Publist4ubma2\\Service\\DebugCollector');
		$this->debugger->add('Started PublistController listAction');

		// check, if ContentElement is already in DB
		$cObj = $this->configurationManager->getContentObject();
		$cElementId = $cObj->data['uid'];

		$this->checkIfRebuildPage($cElementId);
                if ($this->errorHandler->getError()) {
			$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
			if ($this->settings['debug'])
				$this->view->assign('debugMsg', $this->debugger->get());
			return;
		}

		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($years);

		// get Publist from DB
		$content = $this->getPublicationsFromList($cElementId);
                if ($this->errorHandler->getError()) {
			$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
			if ($this->settings['debug'])
				$this->view->assign('debugMsg', $this->debugger->get());
			return;
		}
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($content);

		// sorting publications
		$years = $this->listOfYears($content);
		$types = $this->listOfTypes($content);
		// sort in the same order as the array $years and $types
		//$content = $this->sortPublications($content, $years);

		$this->initSessionData($years, $types, $content);


		$this->debugger->add('all OK');

		$this->view->assign('content', $content);
		$this->view->assign('years', $years);
		$this->view->assign('types', $types);
		$this->view->assign('curYear', $this->sessionData['year']);
		$this->view->assign('curType', $this->sessionData['type']);

		if ($this->settings['debug'])
			$this->view->assign('debugMsg', $this->debugger->get());
	}


	private function getPublicationsFromList($cElementId) {
		$publist = $this->publistRepository->findFirstByCEid($cElementId);
		if ($publist === NULL) {
			$this->errorHandler->setError(1, "Error, not able to find Plubication List in DB for content Element " . $cElementId);
			return 0;
		}
		$publications = $publist->getPublications();
		if ($publications == '') {
			$this->errorHandler->setError(1, "Error, no Publications in Publist");
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
			$publication = $this->publicationController->get($eprint_id);
			if ($this->errorHandler->getError()) {
				// clear the error and skip
				$this->errorHandler->setError(0, "");
				continue;
			}
			array_push($content, $publication);
		}
		if (count($content) <= 0) {
			$this->errorHandler->setError(1, "Error, no valid publications found.");
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
		if ($this->settings['usecache'] == 0) {
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

		$url = queryUrl::generate($this->settings);
                if ($this->errorHandler->getError())
			return;

		$this->debugger->add('Query URL: ' . $url);

		$xmlString = fileReader::downloadFile($url);
                if ($this->errorHandler->getError())
			return;

		$publications = [];
		$publications = $this->extractPublicationsFromXML($xmlString, $this->settings['excludeexternal']);
                if ($this->errorHandler->getError())
			return;

		// store/update Publist in Repository
		if ($publist === NULL) {
			// add to DB
			$this->debugger->add('== Publist ' . $cElementId . ' is NOT in DB, at it ==');
			$publist = $this->objectManager->get('\Unima\Publist4ubma2\Domain\Model\Publist');
			$publist->setCeId($cElementId);
			$publist->setQueryUrl($url);
			$publist->setExcludeExternal($this->settings['excludeexternal']);
			$publist->setFlexformMd5($md5sum);
			$publist->setPublications($this->listOfEprintIds($publications));
			$this->publistRepository->add($publist);
		}
		else
		{
			$this->debugger->add('== Publist ' . $cElementId . ' is in DB, update only ==');
			$publist->setQueryUrl($url);
			$publist->setExcludeExternal($this->settings['excludeexternal']);
			$publist->setFlexformMd5($md5sum);
			$publist->setPublications($this->listOfEprintIds($publications));
			$this->publistRepository->update($publist);
		}

		# Den Vorschlaghammer instanzieren / aus der Kiste kramen
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
 		# Mit dem Vorschlaghammer in die Datenbank speichern / Nägel mit Köpfen machen
		$persistenceManager->persistAll();

	}

	// running from Backend (task Scheduler), Felxform Settings not Reachable
	public function taskUpdatePublist($publist)
	{
		$url = $publist->getQueryUrl();

		$xmlString = fileReader::downloadFile($url);
                if ($this->errorHandler->getError())
			return;

		$publications = [];
		$publications = $this->extractPublicationsFromXML($xmlString, $publist->getExcludeExternal());
                if ($this->errorHandler->getError())
			return;

		$publist->setPublications($this->listOfEprintIds($publications));
		$this->publistRepository->update($publist);

		return;
	}



	private function extractPublicationsFromXML($data, $excludeExternal)
	{
//		                $debugger = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Unima\\Publist4ubma2\\Service\\DebugCollector');
		$publications = [];

                $xml = \simplexml_load_string($data);
                if ($xml === FALSE) {
                        $this->errorHandler->setError(1, 'Could load XML from Bib (not valid xml?)');
                        return $publications;
                }

		if ($xml->count() <= 0) {
                        $this->errorHandler->setError(1, 'No Publications in XML');
                        return $publications;
                }
		$this->debugger->add('Found ' . $xml->count() . ' items in xml');


		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->settings);

		for ($item = 0; $item < $xml->count(); $item ++) {

			$pub = xmlUtil::xmlToArray($xml->eprint[$item]);

			// check if we have to exclude external Publications
			if (($excludeExternal) && ($pub['ubma_external'] == "TRUE"))
				continue;

//			\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($pub);

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



	private function listOfYears($content) {
		$years = array();
		foreach ($content as $publication) {
			if (!in_array($publication->getYear(), $years))
				array_push($years, $publication->getYear());
		}

		if ($this->settings['ordering'] == 0)
			// lates first
			rsort($years);
		else
			// oldest first
			sort($years);


		return $years;
	}

	private function listOfTypes($content) {
		$types = array();
		$tmpTypes = array();
		$confTypes = array();
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

		// check, if type is avauilable this year
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

