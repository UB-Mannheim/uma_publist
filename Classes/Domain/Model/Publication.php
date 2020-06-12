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
class Publication extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

    /**
     * @var \DateTime
     */
    protected $crdate;

    /**
     * @var \DateTime
     */
    protected $tstamp;

    /**
     * Get crdate
     *
     * @return \DateTime
     */
    public function getCrdate() {
        return $this->crdate;
    }

    /**
     * Set crdate
     *
     * @param \DateTime $crdate crdate
     * @return void
     */
    public function setCrdate($crdate) {
        $this->crdate = $crdate;
    }

    /**
     * Get Tstamp
     *
     * @return \DateTime
     */
    public function getTstamp() {
        return $this->tstamp;
    }

    /**
     * Set tstamp
     *
     * @param \DateTime $tstamp tstamp
     * @return void
     */
    public function setTstamp($tstamp) {
        $this->tstamp = $tstamp;
    }

    /**
     * eprintId
     *
     * @var int
     */
    protected $eprintId = 0;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $bookTitle;


    /**
     * abstract
     *
     * @var string
     */
    protected $abstract = '';

    /**
     * urlOffical
     *
     * @var string
     */
    protected $urlOffical = '';

    /**
     * urlUbmaExtern
     *
     * @var string
     */
    protected $urlUbmaExtern = '';


    // only dummy, not needed in DB
    // is set after read publication from DB
    /**
     * usedLinkUrl
     *
     * @var string
     */
    protected $usedLinkUrl = '';


    /**
     * url
     *
     * @var string
     */
    protected $url = '';


    /**
     * year
     *
     * @var int
     */
    protected $year = 0;

    /**
     * bibType
     *
     * @var string
     */
    protected $bibType = '';

    /**
     * volume
     *
     * @var string
     */
    protected $volume = '';

    /**
     * publisher
     *
     * @var string
     */
    protected $publisher = '';

    /**
     * number
     *
     * @var string
     */
    protected $number = '';

    /**
     * publication
     *
     * @var string
     */
    protected $publication = '';

    /**
     * editors
     *
     * @var string
     */
    protected $editors = '';

    /**
     * creators
     *
     * @var string
     */
    protected $creators = '';

    /**
     * corpCreators
     *
     * @var string
     */
    protected $corpCreators = '';


    /**
     * ubmaBookeditor
     *
     * @var string
     */
    protected $ubmaBookEditor = '';

    /**
     * eventLocation
     *
     * @var string
     */
    protected $eventLocation = '';

    /**
     * eventTitle
     *
     * @var string
     */
    protected $eventTitle = '';

    /**
     * placeOfPub
     *
     * @var string
     */
    protected $placeOfPub = '';

    /**
     * pageRange
     *
     * @var string
     */
    protected $pageRange = '';

    /**
     * issn
     *
     * @var string
     */
    protected $issn = '';

    /**
     * isbn
     *
     * @var string
     */
    protected $isbn = '';

    /**
     * ubmaEdition
     *
     * @var string
     */
    protected $ubmaEdition = '';

    /**
     * ubmaTags
     *
     * @var string
     */
    protected $ubmaTags = '';

    /**
     * ubmaForthcoming
     *
     * @var bool
     */
    protected $ubmaForthcoming = false;

    /**
     * ubmaUniversity
     *
     * @var string
     */
    protected $ubmaUniversity = '';

    /**
     * usedCoin
     *
     * @var string
     */
    protected $usedCoin = '';

    /**
     * doi
     *
     * @var string
     */
    protected $doi = '';

    /**
     * urn
     *
     * @var string
     */
    protected $urn = '';

    /**
     * Returns the parentPublist
     *
     * @return int $parentPublist
     */
    public function getParentPublist() {
        return $this->parentPublist;
    }

    /**
     * Sets the parentPublist
     *
     * @param int $parentPublist
     * @return void
     */
    public function setParentPublist($parentPublist) {
        $this->parentPublist = $parentPublist;
    }

    /**
     * Returns the eprintId
     *
     * @return int $eprintId
     */
    public function getEprintId() {
        return $this->eprintId;
    }

    /**
     * Sets the eprintId
     *
     * @param int $eprintId
     * @return void
     */
    public function setEprintId($eprintId) {
        $this->eprintId = $eprintId;
    }

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Returns the bookTitle
     *
     * @return string $bookTitle
     */
    public function getBookTitle() {
        return $this->bookTitle;
    }

    /**
     * Sets the bookTitle
     *
     * @param string $bookTitle
     * @return void
     */
    public function setBookTitle($bookTitle) {
        $this->bookTitle = $bookTitle;
    }


    /**
     * Returns the abstract
     *
     * @return string $abstract
     */
    public function getAbstract() {
        return $this->abstract;
    }

    /**
     * Sets the abstract
     *
     * @param string $abstract
     * @return void
     */
    public function setAbstract($abstract) {
        $this->abstract = $abstract;
    }

    /**
     * Returns the urlOffical
     *
     * @return string $urlOffical
     */
    public function getUrlOffical() {
        return $this->urlOffical;
    }

    /**
     * Sets the urlOffical
     *
     * @param string $urlOffical
     * @return void
     */
    public function setUrlOffical($urlOffical) {
        $this->urlOffical = $urlOffical;
    }

    /**
     * Returns the urlUbmaExtern
     *
     * @return string $urlUbmaExtern
     */
    public function getUrlUbmaExtern() {
        return $this->urlUbmaExtern;
    }

    /**
     * Sets the urlUbmaExtern
     *
     * @param string $urlUbmaExtern
     * @return void
     */
    public function setUrlUbmaExtern($urlUbmaExtern) {
        $this->urlUbmaExtern = $urlUbmaExtern;
    }

    /**
     * Returns the usedLinkUrl
     *
     * @return string $usedLinkUrl
     */
    public function getUsedLinkUrl() {
        return $this->usedLinkUrl;
    }

    /**
     * Sets the usedLinkUrl
     *
     * @param string $usedLinkUrl
     * @return void
     */
    public function setUsedLinkUrl($usedLinkUrl) {
        $this->usedLinkUrl = $usedLinkUrl;
    }

    /**
     * Returns the url
     *
     * @return string $url
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Sets the url
     *
     * @param string $url
     * @return void
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * Returns the year
     *
     * @return int $year
     */
    public function getYear() {
        return $this->year;
    }

    /**
     * Sets the year
     *
     * @param int $year
     * @return void
     */
    public function setYear($year) {
        $this->year = $year;
    }

    /**
     * Returns the bibType
     *
     * @return string $bibType
     */
    public function getBibType() {
        return $this->bibType;
    }

    /**
     * Sets the bibType
     *
     * @param string $bibType
     * @return void
     */
    public function setBibType($bibType) {
        $this->bibType = $bibType;
    }

    /**
     * Returns the volume
     *
     * @return string $volume
     */
    public function getVolume() {
        return $this->volume;
    }

    /**
     * Sets the volume
     *
     * @param string $volume
     * @return void
     */
    public function setVolume($volume) {
        $this->volume = $volume;
    }

    /**
     * Returns the publisher
     *
     * @return string $publisher
     */
    public function getPublisher() {
        return $this->publisher;
    }

    /**
     * Sets the publisher
     *
     * @param string $publisher
     * @return void
     */
    public function setPublisher($publisher) {
        $this->publisher = $publisher;
    }

    /**
     * Returns the number
     *
     * @return string $number
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * Sets the number
     *
     * @param string $number
     * @return void
     */
    public function setNumber($number) {
        $this->number = $number;
    }

    /**
     * Returns the publication
     *
     * @return string $publication
     */
    public function getPublication() {
        return $this->publication;
    }

    /**
     * Sets the publication
     *
     * @param string $publication
     * @return void
     */
    public function setPublication($publication) {
        $this->publication = $publication;
    }

    /**
     * Returns the editors
     *
     * @return string $editors
     */
    public function getEditors() {
        return $this->editors;
    }

    /**
     * Sets the editors
     *
     * @param string $editors
     * @return void
     */
    public function setEditors($editors) {
        $this->editors = $editors;
    }

    /**
     * Returns the creators
     *
     * @return string $creators
     */
    public function getCreators() {
        return $this->creators;
    }

    /**
     * Sets the creators
     *
     * @param string $creators
     * @return void
     */
    public function setCreators($creators) {
        $this->creators = $creators;
    }

    /**
     * Returns the corpCreators
     *
     * @return string $corpCreators
     */
    public function getCorpCreators() {
        return $this->corpCreators;
    }

    /**
     * Sets the corpCreators
     *
     * @param string $corpCreators
     * @return void
     */
    public function setCorpCreators($corpCreators) {
        $this->corpCreators = $corpCreators;
    }


    /**
     * Returns the ubmaBookEditor
     *
     * @return string $ubmaBookEditor
     */
    public function getUbmaBookEditor() {
        return $this->ubmaBookEditor;
    }

    /**
     * Sets the ubmaBookEditor
     *
     * @param string $ubmaBookEditor
     * @return void
     */
    public function setUbmaBookEditor($ubmaBookEditor) {
        $this->ubmaBookEditor = $ubmaBookEditor;
    }

    /**
     * Returns the eventLocation
     *
     * @return string $eventLocation
     */
    public function getEventLocation() {
        return $this->eventLocation;
    }

    /**
     * Sets the eventLocation
     *
     * @param string $eventLocation
     * @return void
     */
    public function setEventLocation($eventLocation) {
        $this->eventLocation = $eventLocation;
    }

    /**
     * Returns the eventTitle
     *
     * @return string $eventTitle
     */
    public function getEventTitle() {
        return $this->eventTitle;
    }

    /**
     * Sets the eventTitle
     *
     * @param string $eventTitle
     * @return void
     */
    public function setEventTitle($eventTitle) {
        $this->eventTitle = $eventTitle;
    }

    /**
     * Returns the placeOfPub
     *
     * @return string $placeOfPub
     */
    public function getPlaceOfPub() {
        return $this->placeOfPub;
    }

    /**
     * Sets the placeOfPub
     *
     * @param string $placeOfPub
     * @return void
     */
    public function setPlaceOfPub($placeOfPub) {
        $this->placeOfPub = $placeOfPub;
    }

    /**
     * Returns the pageRange
     *
     * @return string $pageRange
     */
    public function getPageRange() {
        return $this->pageRange;
    }

    /**
     * Sets the pageRange
     *
     * @param string $pageRange
     * @return void
     */
    public function setPageRange($pageRange) {
        $this->pageRange = $pageRange;
    }

    /**
     * Returns the issn
     *
     * @return string $issn
     */
    public function getIssn() {
        return $this->issn;
    }

    /**
     * Sets the issn
     *
     * @param string $issn
     * @return void
     */
    public function setIssn($issn) {
        $this->issn = $issn;
    }

    /**
     * Returns the isbn
     *
     * @return string $isbn
     */
    public function getIsbn() {
        return $this->isbn;
    }

    /**
     * Sets the isbn
     *
     * @param string $uisbn
     * @return void
     */
    public function setIsbn($isbn) {
        $this->isbn = $isbn;
    }

    /**
     * Returns the ubmaTags
     *
     * @return string $ubmaTags
     */
    public function getUbmaTags() {
        return $this->ubmaTags;
    }

    /**
     * Sets the ubmaTags
     *
     * @param string $ubmaTags
     * @return void
     */
    public function setUbmaTags($ubmaTags) {
        $this->ubmaTags = $ubmaTags;
    }

    /**
     * Returns the ubmaEdition
     *
     * @return string $ubmaEdition
     */
    public function getUbmaEdition() {
        return $this->ubmaEdition;
    }

    /**
     * Sets the ubmaEdition
     *
     * @param string $ubmaEdition
     * @return void
     */
    public function setUbmaEdition($ubmaEdition) {
        $this->ubmaEdition = $ubmaEdition;
    }

    /**
     * Returns the ubmaForthcoming
     *
     * @return bool $ubmaForthcoming
     */
    public function isUbmaForthcoming() {
        return $this->ubmaForthcoming;
    }

    /**
     * Returns the ubmaForthcoming
     *
     * @return bool $ubmaForthcoming
     */
    public function getUbmaForthcoming() {
        return $this->ubmaForthcoming;
    }

    /**
     * Sets the ubmaForthcoming
     *
     * @param bool $ubmaForthcoming
     * @return void
     */
    public function setUbmaForthcoming($ubmaForthcoming) {
        $this->ubmaForthcoming = $ubmaForthcoming;
    }

    /**
     * Returns the ubmaUniversity
     *
     * @return string $ubmaUniversity
     */
    public function getUbmaUniversity() {
        return $this->ubmaUniversity;
    }

    /**
     * Sets the ubmaUniversity
     *
     * @param string $ubmaUniversity
     * @return void
     */
    public function setUbmaUniversity($ubmaUniversity) {
        $this->ubmaUniversity = $ubmaUniversity;
    }

    /**
     * Returns the usedCoin
     *
     * @return string $usedCoin
     */
    public function getUsedCoin() {
        return $this->usedCoin;
    }

    /**
     * Sets the usedCoin
     *
     * @param string $usedCoin
     * @return void
     */
    public function setUsedCoin($usedCoin) {
        $this->usedCoin = $usedCoin;
    }

    /**
     * Returns the doi
     *
     * @return string $doi
     */
    public function getDoi() {
        return $this->doi;
    }
    /**
     * Sets the doi
     *
     * @param string $doi
     * @return void
     */
    public function setDoi($doi) {
        $this->doi = $doi;
    }

    /**
     * Returns the urn
     *
     * @return string $urn
     */
    public function getUrn() {
        return $this->urn;
    }
    /**
     * Sets the urn
     *
     * @param string $urn
     * @return void
     */
    public function setUrn($urn) {
        $this->urn = $urn;
    }

}
