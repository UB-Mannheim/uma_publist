<?php

namespace UMA\UmaPublist\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility as GeneralUtilityCore;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use UMA\UmaPublist\Domain\Repository\PublistRepository;
use UMA\UmaPublist\Controller\PublistController;
use UMA\UmaPublist\Utility\fileReader;
use UMA\UmaPublist\Utility\GeneralUtility;

/**
 * Userfunc to render select-boxes for institutes and chairs from db
 *
 * @package TYPO3
 * @subpackage tx_umapublist
 */
class ItemsProcFunc {


        /**
         * Itemsproc function to extend the selection of templateLayouts in the plugin
         *
         * @param array &$config configuration array
         * @return void
         */
        public function user_templateLayout(array &$config) {
                /** @var \GeorgRinger\News\Utility\TemplateLayout $templateLayoutsUtility */
                $templateLayoutsUtility = GeneralUtilityCore::makeInstance('UMA\\UmaPublist\\Utility\\TemplateLayout');
                $templateLayouts = $templateLayoutsUtility->getAvailableTemplateLayouts($config['row']['pid']);
                foreach ($templateLayouts as $layout) {
                        $additionalLayout = array(
                                $GLOBALS['LANG']->sL($layout[0], TRUE),
                                $layout[1]
                        );
                        array_push($config['items'], $additionalLayout);
                }
        }



	/**
	 * Itemsproc function to redner selectbox for institutes
	 *
	 * @param array &$config configuration array
	 * @return void
	 */
/*
	public function renderInstitutes(array &$config) {

//		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($config);

		// get the institute repository
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtilityCore::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$repository = $objectManager->get('UMA\\UmaPublist\\Domain\\Repository\\InstituteRepository');

		$result = $repository->findAll();

		// copy to config and sort imidily
		foreach ($result as $index => $institute) {
			$config['items'][$index]['0'] = $institute->getNameDe();
			$config['items'][$index]['1'] = $institute->getId();
		}

		return $config;
	}
*/



	/**
	 * Itemsproc function to render selectbox for institutes
	 *
	 * @param array &$config configuration array
	 * @return config
	 */
	public function renderChairs(array &$config) {

//		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($config['row']['pi_flexform']);

		// get the institute repository
		$objectManager = GeneralUtilityCore::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		$repository = $objectManager->get('UMA\\UmaPublist\\Domain\\Repository\\ChairRepository');
		$configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManagerInterface');
		$typoscript = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'umapublist', 'pi1');
		$result = $repository->findAllByInst($config['config']['my_inst'], $typoscript['storagePid']);

		// copy to config and sort imidily
		$resultAssoc = [];
		foreach ($result as $institute) {
			$resultAssoc[$institute->getNameDe().' '.$institute->getId()] = $institute;
		}
		ksort($resultAssoc);
		$resultArray = array_values($resultAssoc);
		foreach ($resultArray as $index => $institute) {
			$config['items'][$index]['0'] = $institute->getNameDe();
			$config['items'][$index]['1'] = $institute->getId();
		}

		return $config;
	}

	/**
	 * Itemsproc function to render selectbox for eprint ids
	 *
	 * @param array &$config configuration array
	 * @return config
	 */
	public function renderEprintIds(array &$config) {
        $connectionPool = GeneralUtilityCore::makeInstance(ConnectionPool::class);
        $ceUid = (int) $config['row']['uid'];
        if(!$ceUid) return $config;
        $settings = GeneralUtility::getSettings($ceUid);
        $settings['excludeEprintIds'] = '';
        $publications = GeneralUtility::getPublicationsForSettings($settings);
        $eprintIds = GeneralUtility::listOfEprintIds($publications);
        $queryBuilderPublications = $connectionPool->getConnectionForTable('tx_umapublist_domain_model_publication')->createQueryBuilder();
        $queryResultPublications = $queryBuilderPublications->select('eprint_id', 'title', 'year', 'bib_type', 'editors', 'creators', 'publication', 'volume', 'rev_number')->from('tx_umapublist_domain_model_publication')
            ->where($queryBuilderPublications->expr()->in('eprint_id', explode(',', $eprintIds)))
            ->execute();
        $resultArray = $queryResultPublications->fetchAll();
        foreach ($resultArray as $publication) {
            $eprintId = $publication['eprint_id'];
            $title = $publication['title'];
            $authors = $publication['creators'] ? $publication['creators'] : $publication['editors'];
            $authors = preg_replace(['#,[^;]*;#', '#,[^;]*$#', '#/#'], ['/', '', ', '], $authors);
            $year = $publication['year'];
            $typeAsText = LocalizationUtility::translate( $publication['bib_type'], 'uma_publist' );
            $name = '[' . $eprintId . '] ' . $authors . ': '. $title . ' (' . $year . ', ' . $typeAsText . ')';
            $selectList[$eprintId] = $name;
        }
        asort($selectList);
        $selectList = array_reverse($selectList, true);
        $i = 1;
        foreach($selectList as $key => $value) {
            $config['items'][$i]['0'] = $value;
            $config['items'][$i]['1'] = $key;
            $i++;
        }

        return $config;
    }

}
