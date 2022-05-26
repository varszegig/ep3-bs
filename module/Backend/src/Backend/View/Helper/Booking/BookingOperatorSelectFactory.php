<?php

namespace Backend\View\Helper\Booking;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BookingOperatorSelectFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BookingOperatorSelect(
            $sm->getServiceLocator()->get('Request')
        );
    }

}