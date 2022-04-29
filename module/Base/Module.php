<?php

namespace Base;

use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\AutoloaderProviderInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Validator\AbstractValidator;

class Module implements AutoloaderProviderInterface, BootstrapListenerInterface, ConfigProviderInterface
{

    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap(EventInterface $e)
    {
        $serviceManager = $e->getApplication()->getServiceManager();

        /* Check database */

        $dbAdapter = $serviceManager->get('Laminas\Db\Adapter\Adapter');
        $dbConnection = $dbAdapter->getDriver()->getConnection();

        try {
            $dbConnection->connect();
        } catch (\RuntimeException $e) {
            include 'Charon.php';

            Charon::carry('application', 'configuration', 1);
        }

        /* Set global validator translator */

        $translator = $serviceManager->get('Translator');
        AbstractValidator::setDefaultTranslator($translator);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

}