<?php

namespace Event\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class EventMetaTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EventMetaTable(EventMetaTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}