<?php

namespace Booking\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BookingTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BookingTable(BookingTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}