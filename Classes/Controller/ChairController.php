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


/**
 * InstitutController
 */
class ChairController extends AdministrationController {

	public function __construct($objectManager = NULL, $chairRepository = NULL) {
		// call the parent ...
		parent::__construct();
		// ... and assign the current Object Manager ...
		if ($objectManager) {
			$this->objectManager = $objectManager;
		}
		// ... otherwise stay with the one from parent

		if ($chairRepository) {
			$this->chairRepository = $chairRepository;
		}
	}


	public function sync($xmlString, $institutes) {

		$instList = [];
		// transform to list/array
		foreach ($institutes as $institute)
			array_push($instList, $institute->getId());
//                \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($instList);


		$chairsNow = $this->getChairsFromBib($xmlString, $instList);
		if (!$this->errorHandler->getError()) {

			// get Chairs Objects from DB
			$chairs = $this->chairRepository->findAll();


			// go throw the objects and check, if we have old entries (remove/update)
			for ($chairItem = 0; $chairItem < count($chairs); $chairItem ++) {
				$found = 0;
				$chair = $chairs[$chairItem];
				// go throw New Chair array
				for ($chairNowItem = 0; $chairNowItem < count($chairsNow); $chairNowItem ++) {
					if ($chair->getId() == $chairsNow[$chairNowItem]['id']) {
						$chair->setNameEn($chairsNow[$chairNowItem]['nameEn']);
						$chair->setNameDe($chairsNow[$chairNowItem]['nameDe']);
						$chair->setParent($chairsNow[$chairNowItem]['parent']);
						// if we used the them, set them to zero
						$chairsNow[$chairNowItem]['id'] = 0;
						$found = 1;
//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($chair);
//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($chairsNow[$chairNowItem]['parent']);
						break;
					}
				}
				if ($found)
					$this->chairRepository->update($chair);
				else
					$this->chairRepository->remove($chair);
			}

			// go throw the New Chair array and check, if we there are new entries (add)
			for ($chairNowItem = 0; $chairNowItem < count($chairsNow); $chairNowItem ++) {
				$chairNow = $chairsNow[$chairNowItem];
				// if new entry
				if ($chairNow['id'] != 0) {
					$newChair = $this->objectManager->get('UMA\UmaPublist\Domain\Model\Chair');
					$newChair->setId($chairNow['id']);
					$newChair->setNameEn($chairNow['nameEn']);
					$newChair->setNameDe($chairNow['nameDe']);
					$newChair->setParent($chairNow['parent']);
					$this->chairRepository->add($newChair);
				}
			}
		}
	}

	// for debug

	/**
	 * action add
	 *
	 * @return void
	 */
	public function addAction() {
		$newChair = $this->objectManager->get('UMA\UmaPublist\Domain\Model\Chair');
		$newChair->setId(6000);
		$newChair->setNameEn("Debug Test");
		$newChair->setNameDe("Debug Test");
		$newChair->setParent("6000");
		$this->chairRepository->add($newChair);

		$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
/*
		# Den Vorschlaghammer instanzieren / aus der Kiste kramen
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
 		# Mit dem Vorschlaghammer in die Datenbank speichern / Nägel mit Köpfen machen
		$persistenceManager->persistAll();
*/

	}


	/**
	 * action delete
	 *
	 * @return void
	 */
	public function deleteAction() {
		$chairs = $this->chairRepository->findAll();
		if (count($chairs) > 0)
			$this->chairRepository->remove($chairs[0]);
		else
			$this->errorHandler->setError(1, "No Chairs in DB - nothing to delete");

		$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
/*
		# Den Vorschlaghammer instanzieren / aus der Kiste kramen
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
 		# Mit dem Vorschlaghammer in die Datenbank speichern / Nägel mit Köpfen machen
		$persistenceManager->persistAll();
*/

	}


	static function getChairsFromBib($data, $instList)
	{

		$chairs = [];
		$chair = array('id' => 0, 'nameEn' => "", 'nameDe' => "", 'parent' => 0);

		// load as XML
		$xml = \simplexml_load_string($data);
		if ($xml === FALSE) {
			$this->errorHandler->setError(1, 'Could not Sync! Seems to be no valid XML in file (parsing chairs)');
			return $chairs;
		}

		// get the chairs
		for ($item = 0; $item < $xml->count(); $item ++) {
			$chair['id'] = 0;
			$current = $xml->subject[$item];
			// if parent is an institute => we grabbed a chair
			if (in_array($xml->subject[$item]->parents[0]->item[0], $instList)) {
				$chair['id'] = (int)($current->subjectid[0]);
				for ($nameItem = 0; $nameItem < count($current->name->item); $nameItem ++) {
					if ((string)($current->name->item[$nameItem]->lang[0]) == "en")
						$chair['nameEn'] = (string)($current->name->item[$nameItem]->name[0]);
					else
						$chair['nameDe'] = (string)($current->name->item[$nameItem]->name[0]);
				}
				$chair['parent'] = (int)($current->parents[0]->item[0]);
			}
			if ($chair['id'] > 0)
				array_push($chairs, $chair);
		}

//		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($chairs);	
		return $chairs;
	}



}
