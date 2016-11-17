<?php
namespace Unima\Publist4ubma2\Task;


   
class Cleanup extends \TYPO3\CMS\Scheduler\Task\AbstractTask {

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     * @inject
     */
    protected $objectManager;


    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;
 
 
    /**
     * @var array
     */
    protected $settings = array();
 
 
    /**
     * Settings aus dem CibfigurationManager ziehen und zuweisen
     */
    public function initialize() {
 
	$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
	$this->configurationManager = $this->objectManager->get('TYPO3\CMS\Extbase\Configuration\ConfigurationManager');

	$configurationArray = array(
	        'persistence' => array(
	                'storagePid' => $this->pid
	        )
	);
        $this->settings = $this->configurationManager->setConfiguration($configurationArray); 

    }

	public function execute() {
		$this->initialize();

	        $administrationController= $this->objectManager->get('Unima\\Publist4ubma2\\Controller\\AdministrationController');
	        $publistRepository= $this->objectManager->get('Unima\\Publist4ubma2\\Domain\\Repository\\PublistRepository');

		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($this->pid);

		$publists = $publistRepository->findAll();
		//\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($publists);

		if (($publists === NULL) || (count($publists) <= 0)) 
			return FALSE;

		$administrationController->publistSync($publists);
		$administrationController->cleanUpPublist($publists);
		$administrationController->cleanupPublications($publists);
		$administrationController->cleanupDeletedPublications();
      		 //dein code mit fehlerabfragen etc.
	         return TRUE; //ende gut, alles gut?
	}
}
?>
