<?php

namespace Backend\View\Helper\Booking;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BookingVisibilityOptionsFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BookingVisibilityOptions(
            $sm->getServiceLocator()->get('Request')
        );
    }

}