<?php

namespace Base\Manager;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ConfigManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $configLocaleListener = $sm->get('Base\Manager\Listener\ConfigLocaleListener');

        $configManager = new ConfigManager($sm->get('Config'));

        $eventManager = $configManager->getEventManager();
        $eventManager->attach($configLocaleListener);

        $configManager->prepare();

        return $configManager;
    }

}