<?php

namespace Backend\View\Helper\Booking;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BookingSquareListFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BookingSquareList(
            $sm->getServiceLocator()->get('Square\Manager\SquareManager'),
            $sm->getServiceLocator()->get('Request')
        );
    }

}