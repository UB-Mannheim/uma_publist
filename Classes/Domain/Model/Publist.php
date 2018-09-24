<?php
namespace UMA\UmaPublist\Domain\Model;


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
 * Publication
 */
class Publist extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

        /**
         * @var \DateTime
         */
        protected $crdate;

        /**
         * @var \DateTime
         */
        protected $tstamp;

        /**
         * @var integer
         */
        protected $sysLanguageUid;

        /**
         * @var integer
         */
        protected $l10nParent;

	/**
	 * ceId
	 *
	 * @var int
	 */
	protected $ceId = 0;


	/**
	 * queryUrl
	 *
	 * @var string
	 */
	protected $queryUrl = '';


       /**
         * @var string
         */
        protected $publications;

	/**
	 * flexformMd5
	 *
	 * @var string
	 */
	protected $flexformMd5 = '';


	/**
	 * excludeExternal
	 *
	 * @var integer
	 */
	protected $excludeExternal = 0;


	/**
	 * filterPublication
	 *
	 * @var string
	 */
	protected $filterPublication = '';


	/**
	 * excludeEprintIds
	 *
	 * @var string
	 */
	protected $excludeEprintIds = '';

    
	/**
	 * filterBwlResearch
	 *
	 * @var string
	 */
	protected $filterBwlResearch = '';

	/**
	 * filterBwlAcademic
	 *
	 * @var string
	 */
	protected $filterBwlAcademic = '';

	/**
	 * filterBwlNational
	 *
	 * @var string
	 */
	protected $filterBwlNational = '';

	/**
	 * filterBwlRefereed
	 *
	 * @var string
	 */
	protected $filterBwlRefereed = '';

	/**
	 * Returns the ceId
	 *
	 * @return int $ceId
	 */
	public function getCeId() {
		return $this->ceId;
	}

	/**
	 * Sets the eprintId
	 *
	 * @param int $eprintId
	 * @return void
	 */
	public function setCeId($ceId) {
		$this->ceId = $ceId;
	}



	/**
	 * Returns the queryUrl
	 *
	 * @return string $queryUrl
	 */
	public function getQueryUrl() {
		return $this->queryUrl;
	}

	/**
	 * Sets the queryUrl
	 *
	 * @param string $queryUrl
	 * @return void
	 */
	public function setQueryUrl($queryUrl) {
		$this->queryUrl = $queryUrl;
	}



        /**
         * Get publications
         *
         * @return string $publications
         */
        public function getPublications() {
                return $this->publications;
        }

        /**
         * Set publications
         *
         * @param string $publications
         * @return void
         */
        public function setPublications($publications) {
                $this->publications = $publications;
        }

        /**
         * Get flexformMd5
         *
         * @return string $flexformMd5
         */
        public function getFlexformMd5() {
                return $this->flexformMd5;
        }

        /**
         * Set flexformMd5
         *
         * @param string $flexformMd5
         * @return void
         */
        public function setFlexformMd5($flexformMd5) {
                $this->flexformMd5 = $flexformMd5;
        }

        /**
         * Get excludeExternal
         *
         * @return integer $excludeExternal
         */
        public function getExcludeExternal() {
                return $this->excludeExternal;
        }

        /**
         * Set excludeExternal
         *
         * @param integer $excludeExternal
         * @return void
         */
        public function setExcludeExternal($excludeExternal) {
		$this->excludeExternal = $excludeExternal;
        }

        /**
         * Get filterPublication
         *
         * @return string $filterPublication
         */
        public function getFilterPublication() {
            return $this->filterPublication;
        }

        /**
         * Set filterPublication
         *
         * @param string $filterPublication
         * @return void
         */
        public function setFilterPublication($filterPublication) {
            $this->filterPublication = $filterPublication;
        }

        /**
         * Get excludeEprintIds
         *
         * @return string $excludeEprintIds
         */
        public function getExcludeEprintIds() {
            return $this->excludeEprintIds;
        }

        /**
         * Set excludeEprintIds
         *
         * @param string $excludeEprintIds
         * @return void
         */
        public function setExcludeEprintIds($excludeEprintIds) {
            $this->excludeEprintIds = $excludeEprintIds;
        }

        /**
         * Get filterBwlResearch
         *
         * @return string $filterBwlResearch
         */
        public function getFilterBwlResearch() {
            return $this->filterBwlResearch;
        }

        /**
         * Set filterBwlResearch
         *
         * @param string $filterBwlResearch
         * @return void
         */
        public function setFilterBwlResearch($filterBwlResearch) {
            $this->filterBwlResearch = $filterBwlResearch;
        }

        /**
         * Get filterBwlAcademic
         *
         * @return string $filterBwlAcademic
         */
        public function getFilterBwlAcademic() {
            return $this->filterBwlAcademic;
        }

        /**
         * Set filterBwlAcademic
         *
         * @param string $filterBwlAcademic
         * @return void
         */
        public function setFilterBwlAcademic($filterBwlAcademic) {
            $this->filterBwlAcademic = $filterBwlAcademic;
        }

        /**
         * Get filterBwlNational
         *
         * @return string $filterBwlNational
         */
        public function getFilterBwlNational() {
            return $this->filterBwlNational;
        }

        /**
         * Set filterBwlNational
         *
         * @param string $filterBwlNational
         * @return void
         */
        public function setFilterBwlNational($filterBwlNational) {
            $this->filterBwlNational = $filterBwlNational;
        }

        /**
         * Get filterBwlRefereed
         *
         * @return string $filterBwlRefereed
         */
        public function getFilterBwlRefereed() {
            return $this->filterBwlRefereed;
        }

        /**
         * Set filterBwlRefereed
         *
         * @param string $filterBwlRefereed
         * @return void
         */
        public function setFilterBwlRefereed($filterBwlRefereed) {
            $this->filterBwlRefereed = $filterBwlRefereed;
        }

}
