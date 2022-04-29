<?php

namespace Booking\Manager;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BookingManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BookingManager(
            $sm->get('Booking\Table\BookingTable'),
            $sm->get('Booking\Table\BookingMetaTable'));
    }

}