<?php
namespace UMA\UmaPublist\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility as GeneralUtilityCore;
use TYPO3\CMS\Core\Database\ConnectionPool;
use UMA\UmaPublist\Utility\fileReader;
use UMA\UmaPublist\Controller\InstituteController;

/**
 * AdministrationController
 */
class AdministrationController extends BasicPublistController {

	/**
	 * instituteRepository
	 *
	 * @var \UMA\UmaPublist\Domain\Repository\InstituteRepository
	 * @inject
	 */
	protected $instituteRepository = NULL;

	/**
	 * chairRepository
	 *
	 * @var \UMA\UmaPublist\Domain\Repository\ChairRepository
	 * @inject
	 */
	protected $chairRepository = NULL;


	/**
	 * publicationController
	 *
	 * @var \UMA\UmaPublist\Controller\PublicationController
	 * @inject
	 */
	protected $publicationController = NULL;



	/**
	 * publistController
	 *
	 * @var \UMA\UmaPublist\Controller\PublistController
	 * @inject
	 */
	protected $publistController = NULL;


	/**
	 * contentRepository
	 *
	 * @var \UMA\UmaPublist\Domain\Repository\ContentRepository
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
			\TYPO3\CMS\Core\Utility\DebugUtility::debug($this->settings);
			\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->settings);
			$this->errorHandler->setError(1, \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errorZeroStoragePid', 'uma_publist'));
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
//		$instituteController = GeneralUtility::makeInstance('UMA\\UmaPublist\\Controller\\InstituteController', $this->instituteRepository);
		$instituteController->sync($xmlString);
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
//		$chairController = GeneralUtility::makeInstance('UMA\\UmaPublist\\Controller\\ChairController', $this->chairRepository);


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
			\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->settings);
			$this->errorHandler->setError(1, \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('errorZeroStoragePid', 'uma_publist'));
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
			if ((!in_array($publication->getEprintId(), $neededeprintIDs)) || (in_array($publication->getEprintId(), $alreadyKeptEprintIDs))) {
				$this->debugger->add('Publication ' . $publication->getEprintId() . ' not needed or double, delete it from DB');
				$this->publicationController->repositoryRemove($publication);
				$didCleanup = 1;
			}
			else {
				array_push($alreadyKeptEprintIDs, $publication->getEprintId());
			}
		}

		# Den Vorschlaghammer instanzieren / aus der Kiste kramen
		$persistenceManager = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
 		# Mit dem Vorschlaghammer in die Datenbank speichern / Nägel mit Köpfen machen
		$persistenceManager->persistAll();

		return $didCleanup;
	}

	public function cleanupDeletedPublications() {
		$queryBuilder = GeneralUtilityCore::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_umapublist_domain_model_publication');
		$didCleanup = !!$queryBuilder
			->delete('tx_umapublist_domain_model_publication')
			->where($queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT)))
			->execute();

		return $didCleanup;
	}

	public function cleanupAllPublications() {
		$queryBuilder = GeneralUtilityCore::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_umapublist_domain_model_publication');
		if ($GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_umapublist_domain_model_publication', 'deleted = 0'))
			$didCleanup = 1;
		$didCleanup += $this->cleanupDeletedPublications();
		
		return $didCleanup;
	}

}
