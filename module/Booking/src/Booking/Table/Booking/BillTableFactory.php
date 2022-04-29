<?php

namespace Booking\Table\Booking;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BillTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BillTable(BillTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}