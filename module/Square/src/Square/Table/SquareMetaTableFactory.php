<?php

namespace Square\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SquareMetaTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new SquareMetaTable(SquareMetaTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}