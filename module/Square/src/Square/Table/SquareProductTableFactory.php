<?php

namespace Square\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SquareProductTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new SquareProductTable(SquareProductTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}