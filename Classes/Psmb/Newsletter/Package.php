<?php
namespace Psmb\Newsletter;

use TYPO3\Flow\Package\Package as BasePackage;
use TYPO3\Flow\Configuration\ConfigurationManager;

/**
 * Class Package
 * @package Psmb\Newsletter
 */
class Package extends BasePackage {

    /**
     * @param \TYPO3\Flow\Core\Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $dispatcher->connect('TYPO3\Flow\Configuration\ConfigurationManager', 'configurationManagerReady',
            function (ConfigurationManager $configurationManager) {
                $configurationManager->registerConfigurationType(
                    'Devices',
                    ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT
                );
            }
        );

        $dispatcher->connect('TYPO3\Flow\Configuration\ConfigurationManager', 'configurationManagerReady',
            function (ConfigurationManager $configurationManager) {
                $configurationManager->registerConfigurationType(
                    'OSS',
                    ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT
                );
            }
        );
    }

}