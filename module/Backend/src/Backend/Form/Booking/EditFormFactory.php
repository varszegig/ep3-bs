<?php

namespace Backend\Form\Booking;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class EditFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EditForm(
            $sm->getServiceLocator()->get('Booking\Service\BookingStatusService'),
            $sm->getServiceLocator()->get('Booking\Service\BookingService'),
            $sm->getServiceLocator()->get('Square\Manager\SquareManager'));
    }

}
