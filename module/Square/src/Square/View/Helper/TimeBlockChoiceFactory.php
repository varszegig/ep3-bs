<?php

namespace Square\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class TimeBlockChoiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new TimeBlockChoice(
            $sm->getServiceLocator()->get('Booking\Manager\BookingManager'),
            $sm->getServiceLocator()->get('Booking\Manager\ReservationManager'),
            $sm->getServiceLocator()->get('Square\Manager\SquarePricingManager'));
    }

}