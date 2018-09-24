<?php
namespace UMA\UmaPublist\Hooks;

/*
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


use TYPO3\CMS\Backend\Utility\BackendUtility;
use UMA\UmaPublist\Utility\GeneralUtility;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Backend\View\PageLayoutView;

/**
 * Contains a preview rendering for the page module of list_type "umapublist_pi1"
 */
class DrawItem implements PageLayoutViewDrawItemHookInterface
{

    /**
     * Preprocesses the preview rendering of a content element of type "Custom address element"
     *
     * @param \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent  object
     * @param bool $drawItem Whether to draw the item using the default functionality
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     *
     * @return void
     */
    public function preProcess(
        PageLayoutView &$parentObject,
        &$drawItem,
        &$headerContent,
        &$itemContent,
        array &$row
    )
    {
        if ($row['CType'] === 'list' && $row['list_type'] === 'umapublist_pi1') {
            $headerContent = '';

            // parse content element's flexform content
            $settings = GeneralUtility::parseFlexForm($row['pi_flexform'], 'settings');

            // initialize backend view
            $standaloneView = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Fluid\View\StandaloneView::class);
            $standaloneView->setTemplatePathAndFilename(\TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName('EXT:uma_publist/Resources/Private/Templates/Publist/BeRender.html'));

            $types = array_diff(explode(',', $settings['type']), ['', 'all']);
            $typesAsText = [];
            foreach($types as $type) {
                $typesAsText[] = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate( $type, 'uma_publist' );
            }

            $timespan = GeneralUtility::getTimespanDescription($settings);
            GeneralUtility::getInstitutesAndChairs($settings, $institutesAssoc, $chairsAssoc);

            $variables = [
                'header' => $row['header'],
                'title' => $settings['title'],
                'author' => $settings['author'],
                'publication' => $settings['publication'],
                'excludeEprintIds' => $settings['excludeEprintIds'],
                'bwlResearch' => $settings['bwlResearch'],
                'bwlAcademic' => $settings['bwlAcademic'],
                'bwlNational' => $settings['bwlNational'],
                'bwlRefereed' => $settings['bwlRefereed'],
                'filter' => $settings['exclude_eprint_ids'],
                'types' => $typesAsText,
                'institutes' => $institutesAssoc,
                'chairs' => $chairsAssoc,
                'timespan' => $timespan
            ];
            $standaloneView->assignMultiple($variables);
            $itemContent = $standaloneView->render();
            $drawItem = false;
        }
    }

}
