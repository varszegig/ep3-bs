<?php

namespace Booking\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BookingMetaTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BookingMetaTable(BookingMetaTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}