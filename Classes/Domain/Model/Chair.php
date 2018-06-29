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
 * Chair
 */
class Chair extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * id
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 * parent
	 *
	 * @var int
	 */
	protected $parent = 0;

	/**
	 * nameEn
	 *
	 * @var string
	 */
	protected $nameEn = '';

	/**
	 * nameDe
	 *
	 * @var string
	 */
	protected $nameDe = '';

	/**
	 * Returns the id
	 *
	 * @return int $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Sets the id
	 *
	 * @param int $id
	 * @return void
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * Returns the parent
	 *
	 * @return int $parent
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * Sets the parent
	 *
	 * @param int $parent
	 * @return void
	 */
	public function setParent($parent) {
		$this->parent = $parent;
	}

	/**
	 * Returns the nameEn
	 *
	 * @return string $nameEn
	 */
	public function getNameEn() {
		return $this->nameEn;
	}

	/**
	 * Sets the nameEn
	 *
	 * @param string $nameEn
	 * @return void
	 */
	public function setNameEn($nameEn) {
		$this->nameEn = $nameEn;
	}

	/**
	 * Returns the nameDe
	 *
	 * @return string $nameDe
	 */
	public function getNameDe() {
		return $this->nameDe;
	}

	/**
	 * Sets the nameDe
	 *
	 * @param string $nameDe
	 * @return void
	 */
	public function setNameDe($nameDe) {
		$this->nameDe = $nameDe;
	}

}