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

