<?php

namespace Event\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class EventTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EventTable(EventTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}