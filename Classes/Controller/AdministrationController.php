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


use Unima\Publist4ubma2\Utility\fileReader;
use Unima\Publist4ubma2\Controller\InstituteController;


/**
 * AdministrationController
 */
class AdministrationController extends BasicPublistController {

	/**
	 * instituteRepository
	 *
	 * @var \Unima\Publist4ubma2\Domain\Repository\InstituteRepository
	 * @inject
	 */
	protected $instituteRepository = NULL;

	/**
	 * chairRepository
	 *
	 * @var \Unima\Publist4ubma2\Domain\Repository\ChairRepository
	 * @inject
	 */
	protected $chairRepository = NULL;


	/**
	 * publicationController
	 *
	 * @var \Unima\Publist4ubma2\Controller\PublicationController
	 * @inject
	 */
	protected $publicationController = NULL;



	/**
	 * publistController
	 *
	 * @var \Unima\Publist4ubma2\Controller\PublistController
	 * @inject
	 */
	protected $publistController = NULL;


	/**
	 * contentRepository
	 *
	 * @var \Unima\Publist4ubma2\Domain\Repository\ContentRepository
	 * @inject
	 */
	protected $contentRepository = NULL;



	/**
	 * action listChairs
	 *
	 * @return void
	 */
	public function listChairsAction() {
		$institutes = $this->instituteRepository->findAll();
		$chairs = $this->chairRepository->findAll();
//		$this->debugQuery($institutes);
//		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($institutes);

		// check if storagePid is set
		if ($this->settings['storagePid'] <= 0) {
//			$this->errorHandler->setError(1, 'storagePid <= 0, NOT allowed. Please create a sysFolder in Backend for storing Publicationlists and add the PID to your TypoScript Constants like "plugin.tx_publist4ubma2_pi1.storagePid = PID"');
			$this->errorHandler->setError(1, \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errorZeroStoragePid', 'publist4ubma2'));
			$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
			return;
		}


		$this->view->assign('divisionsUrl', $this->settingsManager->configValue('extMgn/divisionsUrl'));
		$this->view->assign('institutes', $institutes);
		$this->view->assign('chairs', $chairs);
		$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
	}


	/**
	 * action syncChairs
	 *
	 * @return void
	 */
	public function syncChairsAction() {
		$institutesNow = [];
		$xmlString = fileReader::downloadFile($this->settingsManager->configValue('extMgn/divisionsUrl'));
                if ($this->errorHandler->getError()) {
			$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
			return;
		}

	
		$instituteController = new InstituteController($this->objectManager, $this->instituteRepository);
//		$instituteController = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Unima\\Publist4ubma2\\Controller\\InstituteController', $this->instituteRepository);
		$instituteController->sync($xmlString, $this->instituteRepository);
		if ($this->errorHandler->getError()) {
			$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
			return;
		}
		// force to store the Institutes because we need the institutes to sync the chairs
		// Den Vorschlaghammer instanzieren / aus der Kiste kramen
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
 		// Mit dem Vorschlaghammer in die Datenbank speichern / Nägel mit Köpfen machen
		$persistenceManager->persistAll();


		$chairController = new ChairController($this->objectManager, $this->chairRepository);
//		$chairController = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('Unima\\Publist4ubma2\\Controller\\ChairController', $this->chairRepository);


		// get the chairitutes -> the parents of the chairs
		$institutes = $this->instituteRepository->findAll();
		$chairController->sync($xmlString, $institutes);
		if ($this->errorHandler->getError()) {
			$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
			return;
		}


		// if no error, redirect to list
		$this->redirect('listChairs','Administration');

	}



	/**
	 * action listPublications
	 *
	 * @return void
	 */
	public function listPublicationsAction() {
		// check if storagePid is set
		if ($this->settings['storagePid'] <= 0) {
			$this->errorHandler->setError(1, \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errorZeroStoragePid', 'publist4ubma2'));
			$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
			return;
		}

		$publications = $this->publicationController->repositoryFindAll();
		//$this->errorHandler->setError(1, 'Not Impelmentet yet');
		$this->view->assign('publications', $publications);
		$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
	}	



	/**
	 * action listChairs
	 *
	 * @return void
	 */
	public function showCleanupAction() {
		// check if there is any publication in DB
		$publists = $this->publistController->repositoryFindAll();

		if($this->request->hasArgument('myArg')) {
			$myArg = $this->request->getArgument('myArg');
			switch ($myArg) {
				case "syncPublists":
					if (($publists === NULL) || (count($publists) <= 0)) {
						$this->view->assign('errorMsg', "No Publicationlists in DB");
						return;
					}
					$this->publistSync($publists);
					$this->view->assign('infoMsg', "Sync Successful");
					break;
				case "cleanUpPublist":
					if (($publists === NULL) || (count($publists) <= 0)) {
						$this->view->assign('errorMsg', "No Publicationlists in DB");
						return;
					}
					if ($this->cleanUpPublist($publists))
						$this->view->assign('infoMsg', "Cleanup Successful");
					else
						$this->view->assign('infoMsg', "Nothing to cleanup");
					break;
				case "cleanupPublications":
					if (($publists === NULL) || (count($publists) <= 0)) {
						if ($this->cleanupAllPublications())
							$this->view->assign('infoMsg', "Cleanup Successful");
						else
							$this->view->assign('infoMsg', "Nothing to cleanup");
						break;

					} else {
						if( $this->cleanupPublications($publists))
							$this->view->assign('infoMsg', "Cleanup Successful");
						else
							$this->view->assign('infoMsg', "Nothing to cleanup");
						break;
					}
				case "cleanupDeletedPublications":
					if ( $this->cleanupDeletedPublications())
						$this->view->assign('infoMsg', "Cleanup Successful");
					else
						$this->view->assign('infoMsg', "Nothing to cleanup");
					break;
			}

		}

		$this->view->assign('publists', $publists);
		if ($this->errorHandler->getError())
			$this->view->assign('errorMsg', $this->errorHandler->getErrorMsg());
		$this->view->assign('debugMsg', $this->debugger->get());
	}




	public function publistSync($publists) {

		foreach($publists as $publist) {
			if ($publist === NULL)
				continue;
			$this->debugger->add('--------------------------------------------------------------------');
			$this->debugger->add('Sync Publist ' . $publist->getCeId());
			$this->publistController->taskUpdatePublist($publist);
			if ($this->errorHandler->getError()) {
				$this->debugger->add('Error in Publist ' . $publist->getCeId() . ' : ' . $this->errorHandler->getErrorMsg());	
				$this->errorHandler->setError(0,"");
				continue;
			}
			$this->debugger->add('Publist ' . $publist->getCeId() . ' : sucessful');
		}

		# Den Vorschlaghammer instanzieren / aus der Kiste kramen
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
 		# Mit dem Vorschlaghammer in die Datenbank speichern / Nägel mit Köpfen machen
		$persistenceManager->persistAll();

		return;
	}

	public function cleanUpPublist($publists) {
		//$contents = $this->contentRepository->findAll();
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($contents);

		$didCleanup = 0;
		foreach($publists as $publist) {
			if ($publist === NULL)
				continue;

			$isInDB = $this->contentRepository->findFirstByCeId($publist->getCeId());
			if ($isInDB === NULL) {
				// Content Element was deleted
				$this->publistController->repositoryRemove($publist);
				$didCleanup = 1;
			}
		}

		return $didCleanup;
	}



	public function cleanupPublications($publists) {
		$didCleanup = 0;
		$neededeprintIDs = [];
                $alreadyKeptEprintIDs = [];
		foreach($publists as $publist) {
			if ($publist === NULL)
				continue;
			$eprintIds = explode(',',$publist->getPublications());
			$neededeprintIDs = array_unique(array_merge($neededeprintIDs, $eprintIds));
		}
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($neededeprintIDs);

		$publications = $this->publicationController->repositoryFindAll();;
		foreach ($publications as $publication) {
			if ((!in_array($publication->getEprintId(), $neededeprintIDs)) ||
                           (in_array($publication->getEprintId(), $alreadyKeptEprintIDs))) {
				$this->debugger->add('Publication ' . $publication->getEprintId() . ' not needed or double, delete it from DB');
				$this->publicationController->repositoryRemove($publication);
				$didCleanup = 1;
			}
                        else
                           array_push($alreadyKeptEprintIDs, $publication->getEprintId());
		}

		# Den Vorschlaghammer instanzieren / aus der Kiste kramen
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
 		# Mit dem Vorschlaghammer in die Datenbank speichern / Nägel mit Köpfen machen
		$persistenceManager->persistAll();

		return $didCleanup;
	}

	public function cleanupDeletedPublications() {
		$didCleanup = 0;

		if ($GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_publist4ubma2_domain_model_publication', 'deleted = 1'))
			$didCleanup = 1;
		
		return $didCleanup;
	}

	public function cleanupAllPublications() {
		$didCleanup = 0;

		if ($GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_publist4ubma2_domain_model_publication', 'deleted = 0'))
			$didCleanup = 1;
		$didCleanup += $this->cleanupDeletedPublications();
		
		return $didCleanup;
	}

}
