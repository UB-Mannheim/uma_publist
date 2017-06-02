.. include:: ../Includes.txt


.. _changelog:

ChangeLog
=========

0.0.1 -> 0.0.2
--------------
10.11.2016
  - Repair a little typo in Resources/Private/Partials/YearItem.html
  -  "[0]" shows up in yearMenu -> fixing in Classes/Controller/PublistController.php, check for "0" now

    .. code-block:: php
       <?php
       private function listOfYears($content) {
                $years = array();
                foreach ($content as $publication) {
                        $year = $publication->getYear();
                        if ((!in_array($year, $years)) && ($year > 0))
                                array_push($years, $publication->getYear());
                }
	?>                

0.0.2 -> 0.0.3
--------------
14.11.2016
  -  add some code which for making it mor combatible with Typo3 7.6
  - in Repositories we have to decide now, instead off: "$query->getQuerySettings()->setSysLanguageUid(0);" we have to use:

    .. code-block:: php
       <?php
                if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 7000000)
                        $query->getQuerySettings()->setLanguageUid(0);
                else
                        $query->getQuerySettings()->setSysLanguageUid(0);
       ?>

0.0.3 -> 0.0.4
--------------
17.11.2016
  - BUGFIX: in "Classes/Controller/PublicationController.php"
    - publications could could be used by multiple pub-lists, which could have different settings
    - handle advanced types and used url after reading publication from db
    - storeing "ubmatags" in publication table because it is used in the decission

0.0.4 -> 0.0.5
--------------
17.11.2016
  - BUGFIX: in "Unima\Publist4ubma2\Controller\PublicationController::decodeAuthors():"
    - had to remove [$index] from variable
  - BUGFIX: in "Classes/ViewHelpers/RenderNamesWithAndRdfaSchemaViewHelper.php"
    - wrong usage of strpos

0.0.5 -> 0.0.6
--------------
21.11.2016
  - set correct comments in Configuration/TypoScript/constants.txt
    - https://wiki.typo3.org/TypoScript_Constants#Preparing_for_the_constant_editor

0.0.6 -> 0.0.7
--------------
21.11.2016
  - repair path in Resources/Private/Layouts/Backend/Default.html (from Css/administration.css to CSS/administration.css)
  - BUGFIX: fix wrong use of strpos() in viewhelper clases

0.0.7 -> 0.0.8
--------------
21.11.2016
  - add template for bib with "coins"

0.0.8 -> 0.0.9
--------------
11.01.2017
  - BUGFIX: single creators were parst wrong in Classes/Controller/PublicationController.php
  - add deletion of double publications in the cleanup function cleanupPublications() AdministrationController.php

0.0.9 -> 0.1.0
--------------
22.03.2017
  - BUGFIX: fix two problems in encodeCoin() in Controller/PublicationController.php (wrong date and wrong coding of "&")

0.1.0 -> 0.1.1
--------------
02.06.2017
  - translate the word "and" in RenderNames - ViewHelpers (german/english)
  - changing state from "alpha" to "beta"



