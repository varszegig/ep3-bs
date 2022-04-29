<?php

namespace Event\Manager;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class EventManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EventManager(
            $sm->get('Event\Table\EventTable'),
            $sm->get('Event\Table\EventMetaTable'),
            $sm->get('Base\Manager\ConfigManager')->need('i18n.locale'));
    }

}