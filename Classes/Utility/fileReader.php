<?php

namespace UMA\UmaPublist\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use UMA\UmaPublist\Service\ErrorHandler;

class fileReader {

    /**
     * Find all ids from given ids and level
     *
     * @param string $url   Link to XML
     * @return string xmlcode
     */
    public static function downloadFile($url)
    {
        $errorHandler = GeneralUtility::makeInstance(ErrorHandler::class);
        $content = "";
        $fp = fopen($url, "rb");
        if (!$fp) {
            $errorHandler->setError(1, 'Could not download file ' . $url);
        } else {
            // set timeout for $fp to 5s
            stream_set_timeout($fp, 5);
            while (!feof($fp))
                $content .= fread($fp, 2048);
            fclose($fp);
        }

        return $content;
    }

}
