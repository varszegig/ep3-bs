<?php

namespace User\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class LastBookingsFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $serviceManager = $sm->getServiceLocator();

        return new LastBookings(
            $serviceManager->get('Booking\Manager\BookingManager'),
            $serviceManager->get('Booking\Manager\ReservationManager'),
            $serviceManager->get('Square\Manager\SquareManager'));
    }

}