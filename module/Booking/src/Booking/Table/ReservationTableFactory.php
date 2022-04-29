<?php

namespace Booking\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ReservationTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new ReservationTable(ReservationTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}