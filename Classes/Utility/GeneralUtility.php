<?php

namespace UMA\UmaPublist\Utility;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility as GeneralUtilityCore;
use TYPO3\CMS\Extbase\Service\FlexFormService;
use \UMA\UmaPublist\Controller\PublicationController;

/**
 * TemplateLayout utility class
 */
class GeneralUtility implements SingletonInterface {

    /**
     * Parser
     * 
     * @var \TYPO3\CMS\Core\Localization\Parser\XliffParser
     */
    protected static $parser = null;

    /**
     * Parsed data from files
     * 
     * @var array
     */
    protected static $parsedData = [];

    /**
     * Institute Repository
     * 
     * @var \UMA\UmaPublist\Domain\Repository\InstituteRepository
     */
    protected static $instituteRepository = null;

    /**
     * Chair Repository
     * 
     * @var \UMA\UmaPublist\Domain\Repository\ChairRepository
     */
    protected static $chairRepository = null;

    public static function extractPublicationsFromXML($data, $settings = [])
    {
        $excludeExternal = array_key_exists('excludeexternal', $settings) ? $settings['excludeexternal'] : false;
        $filterPublication = array_key_exists('publication', $settings) ? $settings['publication'] : '';
        $excludeEprintIds = array_key_exists('excludeEprintIds', $settings) ? $settings['excludeEprintIds'] : '';
        $filterBwlResearch = array_key_exists('bwlResearch', $settings) ? $settings['bwlResearch'] : '';
        $filterBwlAcademic = array_key_exists('bwlAcademic', $settings) ? $settings['bwlAcademic'] : '';
        $filterBwlNational = array_key_exists('bwlNational', $settings) ? $settings['bwlNational'] : '';
        $filterBwlRefereed = array_key_exists('bwlRefereed', $settings) ? $settings['bwlRefereed'] : '';

        $publications = [];

        $filterPublicationRegExp = '~'.preg_replace(['#\s*\|\s*#', '#([$^.*+?(){}[\]~])#'], ['|', '\\\\$1'], trim($filterPublication)).'~i';
        $excludeEprintIdArray = array_filter(explode(',', $excludeEprintIds), 'is_numeric');
        $filterBwlResearchArray = array_diff(explode(',', $filterBwlResearch), ['']);
        $filterBwlAcademicArray = array_diff(explode(',', $filterBwlAcademic), ['']);
        $filterBwlNationalArray = array_diff(explode(',', $filterBwlNational), ['']);
        $filterBwlRefereedArray = array_diff(explode(',', $filterBwlRefereed), ['']);

        $xml = \simplexml_load_string($data);
        if ($xml === FALSE) {
            return $publications;
        }

        for ($item = 0; $item < $xml->count(); $item ++) {

            $pub = xmlUtil::xmlToArray($xml->eprint[$item]);

            if (($excludeExternal) && ($pub['ubma_external'] == "TRUE"))
                continue;

            if($filterPublication) {
                if(!preg_match($filterPublicationRegExp, $pub['publication'])) {
                    continue;
                }
            }

            if($excludeEprintIds) {
                if(in_array($pub['eprintid'], $excludeEprintIdArray)) {
                    continue;
                }
            }

            if($filterBwlResearchArray) {
                if(!((in_array('unspecified', $filterBwlResearchArray) && (!array_key_exists('ubma_bwl_research', $pub) || !$pub['ubma_bwl_research'])) || in_array($pub['ubma_bwl_research'], $filterBwlResearchArray))) {
                    continue;
                }
            }

            if($filterBwlAcademicArray) {
                if(!((in_array('unspecified', $filterBwlAcademicArray) && (!array_key_exists('ubma_bwl_academic', $pub) || !$pub['ubma_bwl_academic'])) || in_array($pub['ubma_bwl_academic'], $filterBwlAcademicArray))) {
                    continue;
                }
            }

            if($filterBwlNationalArray) {
                if(!((in_array('unspecified', $filterBwlNationalArray) && (!array_key_exists('ubma_bwl_national', $pub) || !$pub['ubma_bwl_national'])) || in_array($pub['ubma_bwl_national'], $filterBwlNationalArray))) {
                    continue;
                }
            }

            if($filterBwlRefereedArray) {
                if(!((in_array('unspecified', $filterBwlRefereedArray) && (!array_key_exists('ubma_bwl_refereed', $pub) || !$pub['ubma_bwl_refereed'])) || in_array($pub['ubma_bwl_refereed'], $filterBwlRefereedArray))) {
                    continue;
                }
            }

            $publicationController = GeneralUtilityCore::makeInstance(PublicationController::class);
            $publicationController->add($pub);
            array_push($publications, $pub);

        }

        return $publications;
    }

    public static function listOfEprintIds($publications) {
        $listOfIDs = '';
        foreach ($publications as $publication) {
            $listOfIDs .=  $publication['eprintid'] . ',';
        }
        $listOfIDs = rtrim($listOfIDs, ',');
        return $listOfIDs;
    }

    public static function getSettings($ceUid) {
        $connectionPool = GeneralUtilityCore::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getConnectionForTable('tt_content')->createQueryBuilder();
        $queryResult = $queryBuilder->select('pi_flexform')->from('tt_content')
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($ceUid)))
            ->execute();
        $row = $queryResult->fetch();
        if($row) {
            $ffs = GeneralUtilityCore::makeInstance(FlexFormService::class);
            $flexform = $ffs->convertFlexFormContentToArray($row['pi_flexform']);
            if(is_array($flexform) && array_key_exists('settings', $flexform)) {
                return $flexform['settings'];
            }
        }
        return [];
    }

    public static function getPublicationsForSettings($settings) {
        $queryUrl = queryUrl::generate($settings, 0);
        $xmlString = fileReader::downloadFile($queryUrl);
        $publications = self::extractPublicationsFromXML($xmlString, $settings);
        return $publications;
    }

    /**
     * Get translation from a specified file by its key
     *
     * @param string $key
     * @param string $filename
     * @return string
     */
    public static function getTranslationFromFile($key, $filename = 'locallang.xlf') {
        // get backend language file
        $llFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('uma_publist').'Resources/Private/Language/'.$filename;
        if(array_key_exists($filename, self::$parsedData)) {
            $parsedData = &self::$parsedData[$filename];
        }
        else {
            if(!self::$parser) {
                self::$parser = new \TYPO3\CMS\Core\Localization\Parser\XliffParser();
            }
            self::$parsedData[$filename] = self::$parser->getParsedData($llFile, $GLOBALS['LANG']->lang);
            $parsedData = &self::$parsedData[$filename];
        }
        $translation = $parsedData[$GLOBALS['LANG']->lang][$key][0]['target'];
        return $translation;
    }

    /**
     * Get timespan description from content element settings
     *
     * @param array $settings
     * @return string
     */
    public static function getTimespanDescription($settings) {
        $yearsIndex = $settings['years'];
        $years = self::getTranslationFromFile('pi1_flexform.years.'.$yearsIndex, 'locallang_be.xlf');
        switch($yearsIndex) {
            case 0:
                $years = '';
                break;
            case 1:
                $years .= ' '.$settings['yearthis'];
                break;
            case 2:
                $years .= ' '.$settings['yeartill'];
                break;
            case 3:
                $years .= ' '.$settings['yearfrom'];
                break;
            case 4:
                $years = str_replace(['X', 'Y'], [$settings['yearfrom'], $settings['yeartill']], $years);
                break;
            case 5:
                $years = str_replace('X', $settings['lastyearfrom'], $years);
                break;
            case 6:
                $years = str_replace('Y', $settings['lastyeartill'], $years);
                break;
            case 7:
                $years = str_replace(['X', 'Y'], [$settings['lastyearfrom'], $settings['lastyeartill']], $years);
        }
        // remove terms in parentheses
        $years = preg_replace('#\([^\)]*\)#', '', $years);
    }

    /**
     * Get institutes and chairs in associative arrays (with id as key) from content element settings
     *
     * @param array $settings
     * @param array $institutesAssoc
     * @param array $chairsAssoc
     */
    public static function getInstitutesAndChairs($settings, &$institutesAssoc, &$chairsAssoc) {
        // instantiate institute and chair repositoriesi
        if(!self::$instituteRepository || !self::$chairRepository) {
            $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\ObjectManager::class);
            self::$instituteRepository = $objectManager->get(\UMA\UmaPublist\Domain\Repository\InstituteRepository::class);
            self::$chairRepository = $objectManager->get(\UMA\UmaPublist\Domain\Repository\ChairRepository::class);
        }
        $institutesAssoc = [];
        $chairsAssoc = [];
        foreach($settings as $key => $value) {
            if(($value == 1) && preg_match('#^institute([0-9]+)$#', $key, $matches)) {
                $instituteId = (int) $matches[1];
                $institute = self::$instituteRepository->findOneById((int) $instituteId);
                if($institute) {
                    $institutesAssoc[$instituteId] = $institute;
                }
            }
            if(preg_match('#^chairs([0-9]+)$#', $key, $matches)) {
                $instituteId = (int) $matches[1];
                if(array_key_exists($instituteId, $institutesAssoc)) {
                    $chairIds = explode(',', $value);
                }
                if(is_array($chairIds)) {
                    foreach($chairIds as $chairId) {
                        $chair = self::$chairRepository->findOneById((int) $chairId);
                        if($chair) {
                            $chairsAssoc[$chairId] = $chair;
                        }
                    }
                }
            }
        }
    }
}
