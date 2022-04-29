<?php

namespace Square\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SquareTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new SquareTable(SquareTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}