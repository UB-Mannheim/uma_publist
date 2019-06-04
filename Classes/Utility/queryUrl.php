<?php

namespace UMA\UmaPublist\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use UMA\UmaPublist\Service\ErrorHandler;

class queryUrl {
    /**
     * Generate Query URL
     *
     * @param array $flexform  the flexform
     * @return string queryUrl
     */
    public static function generate($flexform, $bibtex)
    {
        // load the error handler singleton
        $errorHandler = GeneralUtility::makeInstance(ErrorHandler::class);

        $extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['uma_publist']);
        $queryBaseUrl = $extensionConfiguration['queryBaseUrl'];

        $url = "";

        if ($flexform['usedirecturl']) {
            if (!$flexform['directurl'])
                $errorHandler->setError(1, 'No Direct-URL given! (Flexform/expert tab)');
            else
                $url = $flexform['directurl'];
            return $url;
        }

        if ($bibtex > 0) {
            $format = 'BibTeX.bib';
            $formatShort = 'BibTeX';
        } else {
            $format = 'XML.XML';
            $formatShort = 'XML';
        }

        // not working with eprints - we get always the newest, it don't cares about |date
        //$ordering = '|-date';
        $ordering = '|-ubma_date_year';

        // query prefix
        $url = $queryBaseUrl . '/cgi/search/advanced/export_madoc_' . $format . '?screen=Public%3A%3AEPrintSearch&_action_export=1&output=' . $formatShort . '&exp=0|1' . $ordering . '%2Fcreators_name%2Ftitle|archive|-';

        // author name
        if ($flexform['author']) {
            $authors = trim($flexform['author']);
            $authors = str_replace(', ', ',', $authors);
            $authors = str_replace('  ', ' ', $authors);
            $authors = str_replace(',', '%2C', $authors);
            $authors = str_replace(' ', '+', $authors);
            $url = $url . '|contributors_name%2Fcreators_name%2Feditors_name%3Acontributors_name%2Fcreators_name%2F';
            if ($flexform['authorcombi'] == 'and')
            // AND
                $url = $url . 'editors_name%3AAND%3AIN%3A';
            else
            // OR
                $url = $url . 'editors_name%3AANY%3AIN%3A';
            $url = $url . $authors;
        }

        // title
        if ($flexform['title']) {
            $title = str_replace(' ', '+', $flexform['title']);
            $url = $url . '|title_title%3Atitle_title%3AALL%3AIN%3A' . $title;
        }

        // types
        $queryTypes = $flexform['type'];
        // search for all, if we find all, or if advanced-types enabled
        if ($queryTypes) {
            if ((strstr($queryTypes, 'all', false)) || ($flexform['useadvancedtypesbytag']))
                $queryTypes = '';
            else {
                // replace "," with "+"
                $queryTypes = str_replace(',', '+', $queryTypes);
                $url = $url . '|type%3Atype%3AANY%3AEQ%3A' . $queryTypes;
            }
        }

        // year
        if ($flexform['years']) {
            $url = $url . '|ubma_date_year%3Aubma_date_year%3AALL%3AEQ%3A';
        $yearfrom = $flexform['yearfrom'];
        $yeartill = $flexform['yeartill'];
        $selected = $flexform['years'];

        // check, if we have relative years
        // transform them to absolute years
        switch ($selected)
        {
            // last X years
            case 5:
                $selected = 3;
                if (!$flexform['lastyearfrom']) {
                    $errorHandler->setError(1, 'No lastyearfrom given! (Flexform)');
                    return "";
                } else {
                    $yearfrom = intval(date('Y')) - $flexform['lastyearfrom'];
                }
                break;
            // till last X years
            case 6:
                $selected = 2;
                if (!$flexform['lastyeartill']) {
                    $errorHandler->setError(1, 'No lastyeartill given! (Flexform)');
                    return "";
                } else {
                    $yeartill = intval(date('Y')) - $flexform['lastyeartill'];
                }
                break;
            // last X years
            case 7:
                $selected = 4;
                if (!$flexform['lastyearfrom']) {
                    $errorHandler->setError(1, 'No lastyearfrom given! (Flexform)');
                    return "";
                } else {
                    $yearfrom = intval(date('Y')) - $flexform['lastyearfrom'];
                }
                if (!$flexform['lastyeartill']) {
                    $errorHandler->setError(1, 'No lastyeartill given! (Flexform)');
                    return "";
                } else {
                    $yeartill = intval(date('Y')) - $flexform['lastyeartill'];
                }
                break;
        }

        switch ($selected)
        {
            // exact
            case 1:
                if (!$flexform['yearthis']) {
                    $errorHandler->setError(1, 'No yearthis given! (Flexform)');
                    return "";
                } else {
                    $url = $url . $flexform['yearthis'];
                }
                break;
            // till
            case 2:
                if (!$yeartill) {
                    $errorHandler->setError(1, 'No yeartill given! (Flexform)');
                    return "";
                } else {
                    $url = $url . '-' . $yeartill;
                }
            break;
            // from
            case 3:
                if (!$yearfrom) {
                    $errorHandler->setError(1, 'No yearfrom given! (Flexform)');
                    return "";
                } else {
                    $url = $url . $yearfrom . '-';
                }
                break;
            // from to
            case 4:
                if ((!$yearfrom) || (!$yeartill)) {
                    $errorHandler->setError(1, 'No yearfrom or no yeartill given! (Flexform)');
                    return "";
                }
                else
                    $url = $url . $yearfrom . '-' . $yeartill;
                    break;
                }

        }
        // institute
        $institute = "";
        for ($index = 1; $index <= 8; $index += 1) {
            if (($flexform['institute' . $index . '0000']) && ($flexform['chairs' . $index . '0000'])){
                if ($institute)
                    $institute = $institute . '+';
                $institute = $institute . str_replace(',', '+', $flexform['chairs' . $index . '0000']);
            }
        }
        //$group = $flexform[query][institute];
        if ($institute)
            // $flexform[lsSelect]['icombination'] is "ALL" or "ANY"
            $url = $url . '|divisions%3Adivisions%3A' . $flexform['icombination'] . '%3AEQ%3A' . $institute;
            // end
        $url = $url . '|-|eprint_status%3Aeprint_status%3AALL%3AEQ%3Aarchive|metadata_visibility%3Ametadata_visibility%3AALL%3AEX%3Ashow';

        return $url;
    }

}
