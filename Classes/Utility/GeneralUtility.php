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

   /**
    * Parse flexform
    *
    * @param string $flexform
    * @param string $baseKey
    * @return array
    */
   public static function parseFlexForm($flexform, $baseKey = '') {
        $flexFormArray = \TYPO3\CMS\Core\Utility\GeneralUtility::xml2array($flexform);
        if(!is_array($flexFormArray)) return [];
        $flexFormSettings = [];
        foreach ( $flexFormArray['data'] as $sheet => $data ) {
            foreach ( $data as $lang => $value ) {
                foreach ( $value as $key => $val ) {
                    $parts = explode('.', $key);
                    $i = 0;
                    $head = &$flexFormSettings;
                    foreach ($parts as $part) {
                        if ($i == count($parts) - 1) {
                            $head[$part] = $val['vDEF'];
                        }
                        else {
                            if (!array_key_exists($part, $head)) {
                                $head[$part] = [];
                            }
                            $head = &$head[$part];
                        }
                        $i++;
                    }
                }
            }
        }
        return $baseKey ? $flexFormSettings[$baseKey] : $flexFormSettings;
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
        // remove terms in parantheses
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
