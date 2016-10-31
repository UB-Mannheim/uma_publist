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


/**
 * InstitutController
 */
class InstituteController extends AdministrationController {

	public function __construct($objectManager = NULL, $instituteRepository = NULL) {
		// call the parent ...
		parent::__construct();
		// ... and assign the current Object Manager ...
		if ($objectManager) {
			$this->objectManager = $objectManager;
		}
		// ... otherwise stay with the one from parent

		// the same for the repository
		if ($instituteRepository) {
			$this->instituteRepository = $instituteRepository;
		}
	}


	public function sync($xmlString) {
		$institutesNow = [];
		$institutesNow = $this->getInstitutesFromBib($xmlString);
		if (!$this->errorHandler->getError()) {
			// get Institutes Objects from DB
			$institutes = $this->instituteRepository->findAll();

			// go throw the objects and check, if we have old entries (remove/update)
			for ($instItem = 0; $instItem < count($institutes); $instItem ++) {
				$found = 0;
				$institute = $institutes[$instItem];
				// go throw New Institute array
				for ($instNowItem = 0; $instNowItem < count($institutesNow); $instNowItem ++) {
					if ($institute->getId() == $institutesNow[$instNowItem]['id']) {
						$institute->setNameEn($institutesNow[$instNowItem]['nameEn']);
						$institute->setNameDe($institutesNow[$instNowItem]['nameDe']);
						// if we used the them, set them to zero
						$institutesNow[$instNowItem]['id'] = 0;
						$found = 1;
						break;
					}
				}
				if ($found)
					$this->instituteRepository->update($institute);
				else
					$this->instituteRepository->remove($institute);
			}

			// go throw the New Institute array and check, if we there are new entries (add)
			for ($instNowItem = 0; $instNowItem < count($institutesNow); $instNowItem ++) {
				$instituteNow = $institutesNow[$instNowItem];
				// if new entry
				if ($instituteNow['id'] != 0) {
					$newInstitute = $this->objectManager->get('\Unima\Publist4ubma2\Domain\Model\Institute');
					$newInstitute->setId($instituteNow['id']);
					$newInstitute->setNameEn($instituteNow['nameEn']);
					$newInstitute->setNameDe($instituteNow['nameDe']);
					$this->instituteRepository->add($newInstitute);
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
		$newInstitute = $this->objectManager->get('\Unima\Publist4ubma2\Domain\Model\Institute');
		$newInstitute->setId(6000);
		$newInstitute->setNameEn("Debug Test");
		$newInstitute->setNameDe("Debug Test");
		$this->instituteRepository->add($newInstitute);

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
	 * @param \Unima\Publist4ubma2\Domain\Model\Institute $institute
	 * @return void
	 */
	public function deleteAction() {
		$institutes = $this->instituteRepository->findAll();
		if (count($institutes) > 0)
			$this->instituteRepository->remove($institutes[0]);
		else
			$this->errorHandler->setError(1, "No Institutes in DB - nothing to delete");

		$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
/*
		# Den Vorschlaghammer instanzieren / aus der Kiste kramen
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
 		# Mit dem Vorschlaghammer in die Datenbank speichern / Nägel mit Köpfen machen
		$persistenceManager->persistAll();
*/

	}

	static function getInstitutesFromBib($data)
	{
		$institutes = [];
		$institute = array('id' => 0, 'nameEn' => "", 'nameDe' => "");

		// load as XML
		$xml = \simplexml_load_string($data);
		if ($xml === FALSE) {
			$this->errorHandler->setError(1, 'Could not Sync! Seems to be no valid XML in file (parsing institutes)');
			return $institutes;
		}

		// get the institutes
		for ($item = 0; $item < $xml->count(); $item ++) {
			$institute['id'] = 0;
			$current = $xml->subject[$item];
			if ($xml->subject[$item]->parents[0]->item[0] == "divisions") {
				$institute['id'] = (int)($current->subjectid);
				for ($nameItem = 0; $nameItem < count($current->name->item); $nameItem ++) {
					if ((string)($current->name->item[$nameItem]->lang[0]) == "en")
						$institute['nameEn'] = (string)($current->name->item[$nameItem]->name[0]);
					else
						$institute['nameDe'] = (string)($current->name->item[$nameItem]->name[0]);
				}
			}
			if ($institute['id'] > 0)
				array_push($institutes, $institute);
		}

//		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($institutes);	

		return $institutes;
	}




}
