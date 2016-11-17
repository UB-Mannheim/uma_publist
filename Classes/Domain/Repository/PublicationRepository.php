<?php
namespace Unima\Publist4ubma2\Domain\Repository;


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
 * The repository for Publications
 */
class PublicationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	public function findAll() {
		// sort by ascending "id"
		$orderings = array(
			'eprintId' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
		);

		$query = $this->createQuery();
		$query->setOrderings($orderings);

		$query->getQuerySettings()->setRespectSysLanguage(FALSE);
                if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 7000000)
                        $query->getQuerySettings()->setLanguageUid(0);
		else
			$query->getQuerySettings()->setSysLanguageUid(0);

		$result = $query->execute();
		return $result;
	}

	public function findFirstByEprintId($eprintId) {

		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectSysLanguage(FALSE);
                if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 7000000)
                        $query->getQuerySettings()->setLanguageUid(0);
                else
                        $query->getQuerySettings()->setSysLanguageUid(0);


		$query->matching(
			$query->equals("eprint_id", $eprintId)
		);
                $result = $query->execute()->getFirst();
		return $result;

	}

}
