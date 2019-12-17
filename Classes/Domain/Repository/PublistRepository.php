<?php
namespace UMA\UmaPublist\Domain\Repository;


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
 * The repository for Publists
 */
class PublistRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

    /****
     * Initialize repository wide settings
     *
     */
    public function initializeObject() {
        $querySettings = $this->objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);

        // don't add the pid constraint
        $querySettings->setRespectStoragePage(FALSE);

        $this->setDefaultQuerySettings($querySettings);
    }

	public function findAll() {
		// sort by ascending "id"
		$orderings = array(
			'ceId' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
		);

		$query = $this->createQuery();
		$query->setOrderings($orderings);

		$query->getQuerySettings()->setRespectSysLanguage(FALSE);
		$query->getQuerySettings()->setLanguageUid(0);

		$result = $query->execute();
		return $result;
	}

	public function findFirstByCEId($ceid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectSysLanguage(FALSE);
		$query->getQuerySettings()->setLanguageUid(0);

		$query->matching(
			$query->equals("ceId", $ceid)
		);

		$result = $query->execute()->getFirst();
		return $result;
	}

	public function findFirstByTsconfig($tsConfig) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectSysLanguage(FALSE);
		$query->getQuerySettings()->setLanguageUid(0);

		$query->matching(
			$query->equals("tsconfig", $tsConfig)
		);

		$result = $query->execute()->getFirst();
		return $result;
	}

}
