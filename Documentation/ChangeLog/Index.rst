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
	* fix logic bug in "Classes/Controller/PublicationController.php"
	  - publications could could be used by multiple pub-lists, which could have
            different settings
	  - handle advanced types and used url after reading publication from db
	  - storeing "ubmatags" in publication table because it is used in the decission
