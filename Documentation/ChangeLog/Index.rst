.. include:: ../Includes.txt


.. _changelog:

ChangeLog
=========

0.0.1 -> 0.0.2
--------------
10.11.2016
::
	* Repair a little typo in Resources/Private/Partials/YearItem.html
	* "[0]" shows up in yearMenu -> fixing in Classes/Controller/PublistController.php, check for "0" now
.. code-block:: php
 <?php
       private function listOfYears($content) {
                $years = array();
                foreach ($content as $publication) {
                        $year = $publication->getYear();
                        if ((!in_array($year, $years)) && ($year > 0))
                                array_push($years, $publication->getYear());
                }
                

0.0.2 -> 0.0.3
--------------
14.11.2016
::
	* Compatible with Typo3 7.6 (first Try)
	- In Repositories setSysLanguageUid() is not available in Typo3 7.6 so put:
.. code-block:: php
 <?php 
                if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 7000000)
                        $query->getQuerySettings()->setLanguageUid(0);
                else
                        $query->getQuerySettings()->setSysLanguageUid(0);

	- fix "$className "\Unima\Publist4ubma2\Domain\Model\Institute" must not start with a  backslash.

