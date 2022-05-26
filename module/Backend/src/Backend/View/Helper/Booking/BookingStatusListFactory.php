<?php

namespace Backend\View\Helper\Booking;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BookingStatusListFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BookingStatusList(
            $sm->getServiceLocator()->get('Request')
        );
    }

}