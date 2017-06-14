.. include:: ../Includes.txt


.. _changelog:

ChangeLog
=========

0.0.1 -> 0.0.2
--------------
2016-11-10
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
2016-11-14
  -  add some code to make it more compatible with TYPO3 7.6
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
2016-11-17
  - BUGFIX: in "Classes/Controller/PublicationController.php"
    - publications could be used by multiple pub-lists, which could have different settings
    - handle advanced types and used url after reading publication from db
    - storing "ubmatags" in publication table because it is used in the decision

0.0.4 -> 0.0.5
--------------
2016-11-17
  - BUGFIX: in "Unima\Publist4ubma2\Controller\PublicationController::decodeAuthors():"
    - had to remove [$index] from variable
  - BUGFIX: in "Classes/ViewHelpers/RenderNamesWithAndRdfaSchemaViewHelper.php"
    - wrong usage of strpos

0.0.5 -> 0.0.6
--------------
2016-11-21
  - set correct comments in Configuration/TypoScript/constants.txt
    - https://wiki.typo3.org/TypoScript_Constants#Preparing_for_the_constant_editor

0.0.6 -> 0.0.7
--------------
2016-11-21
  - repair path in Resources/Private/Layouts/Backend/Default.html (from Css/administration.css to CSS/administration.css)
  - BUGFIX: fix wrong use of strpos() in viewhelper classes

0.0.7 -> 0.0.8
--------------
2016-11-21
  - add template for bib with "coins"

0.0.8 -> 0.0.9
--------------
2017-01-11
  - BUGFIX: single creators were parsed wrongly in Classes/Controller/PublicationController.php
  - add deletion of double publications in the cleanup function cleanupPublications() AdministrationController.php

0.0.9 -> 0.1.0
--------------
2017-03-22
  - BUGFIX: fix two problems in encodeCoin() in Controller/PublicationController.php (wrong date and wrong coding of "&")

0.1.0 -> 0.1.1
--------------
2017-06-02
  - translate the word "and" in RenderNames - ViewHelpers (German/English)
  - changing state from "alpha" to "beta"
