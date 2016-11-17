<?php
namespace Unima\Publist4ubma2\Domain\Repository;

/**
 * The repository for Contents
 */
class ContentRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	// Repository wide initialization
	// https://wiki.typo3.org/Default_Orderings_and_Query_Settings_in_Repository#Default_Orderings_and_Query_Settings_TYPO3_6.0_-_6.2_.28extbase_6.0_-_6.2.29
	public function initializeObject() {
	        /** @var $querySettings \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings */
	        $querySettings = $this->objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
	        // go for $defaultQuerySettings = $this->createQuery()->getQuerySettings(); if you want to make use of the TS persistence.storagePid with defaultQuerySettings(), see #51529 for details

	        // don't add the pid constraint
	        $querySettings->setRespectStoragePage(FALSE);
	        $this->setDefaultQuerySettings($querySettings);
	}


	public function findFirstByCeId($CeId) {

		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectSysLanguage(FALSE);
		if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 7000000)
			$query->getQuerySettings()->setLanguageUid(0);
		else
			$query->getQuerySettings()->setSysLanguageUid(0);
	        $query->getQuerySettings()->setRespectStoragePage(FALSE);

		$query->matching(
			$query->equals("uid", $CeId)
		);
                $result = $query->execute()->getFirst();
		return $result;

	}

   
}
