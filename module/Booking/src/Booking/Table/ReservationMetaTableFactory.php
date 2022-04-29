<?php

namespace Booking\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ReservationMetaTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new ReservationMetaTable(ReservationMetaTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}