<?php

namespace Square\Manager;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SquareManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $configManager = $sm->get('Base\Manager\ConfigManager');

        $locale = $configManager->need('i18n.locale');

        return new SquareManager(
            $sm->get('Square\Table\SquareTable'),
            $sm->get('Square\Table\SquareMetaTable'),
            $locale);
    }

}