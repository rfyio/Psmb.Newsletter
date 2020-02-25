<?php
namespace Psmb\Newsletter;

use Neos\Flow\Package\Package as BasePackage;
use Neos\Flow\Configuration\ConfigurationManager;

/**
 * Class Package
 * @package Psmb\Newsletter
 */
class Package extends BasePackage {

    /**
     * @param \Neos\Flow\Core\Bootstrap $bootstrap The current bootstrap
     * @return void
     */
    public function boot(\Neos\Flow\Core\Bootstrap $bootstrap) {
        $dispatcher = $bootstrap->getSignalSlotDispatcher();

        $dispatcher->connect('Neos\Flow\Configuration\ConfigurationManager', 'configurationManagerReady',
            function (ConfigurationManager $configurationManager) {
                $configurationManager->registerConfigurationType(
                    'Devices',
                    ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT
                );
            }
        );

        $dispatcher->connect('Neos\Flow\Configuration\ConfigurationManager', 'configurationManagerReady',
            function (ConfigurationManager $configurationManager) {
                $configurationManager->registerConfigurationType(
                    'OSS',
                    ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT
                );
            }
        );
    }

}