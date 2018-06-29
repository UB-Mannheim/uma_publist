<?php

namespace UMA\UmaPublist\Command;

/* puts a new filed in Task Scheduler Formular "Ordner für die Speicherung"
   Very Cool!
   https://www.schroedermatthias.de/snippets/beispiel-fuer-einen-commandcontroller.html
*/


class FileCommandControllerAdditionalFieldProvider implements \TYPO3\CMS\Scheduler\AdditionalFieldProviderInterface {

    public function getAdditionalFields(array &$taskInfo, $task, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $parentObject) {

    	// init value
        if (empty($taskInfo['pid'])) {
            if ($parentObject->CMD == 'edit') {
                $taskInfo['pid'] = $task->pid;
            } else {
                $taskInfo['pid'] = '';
            }
        }

        // Write the HTML  code for the field
        $fieldCodePid = '<input type="text" name="tx_scheduler[pid]" id="pid" value="' . htmlspecialchars($taskInfo['pid']) . '" size="5" />';
        
        $additionalFields['pid'] = array(
            'code' => $fieldCodePid,
            'label' => 'page_id of folder with data (storagePid)',
            'cshKey' => '_MOD_tools_txschedulerM1',
            'cshLabel' => 'pid'
        );

        return $additionalFields;
    }

    public function saveAdditionalFields(array $submittedData, \TYPO3\CMS\Scheduler\Task\AbstractTask $task) {
        $task->pid = $submittedData['pid'];
    }

    public function validateAdditionalFields(array &$submittedData, \TYPO3\CMS\Scheduler\Controller\SchedulerModuleController $schedulerModule) {

        $submittedData['pid'] = trim($submittedData['pid']);
        if (empty($submittedData['pid'])) {
            $schedulerModule->addMessage($GLOBALS['LANG']->sL('Please enter a storagePid!'), \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
            return FALSE;
        }

        return TRUE;
    }
}


?>
