<?php

namespace Square\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SquarePricingTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new SquarePricingTable(SquarePricingTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}