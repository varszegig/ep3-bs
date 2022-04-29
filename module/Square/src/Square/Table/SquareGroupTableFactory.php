<?php

namespace Square\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SquareGroupTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new SquareGroupTable(SquareGroupTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}