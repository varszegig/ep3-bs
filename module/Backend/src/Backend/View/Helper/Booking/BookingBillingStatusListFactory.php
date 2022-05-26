<?php

namespace Backend\View\Helper\Booking;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BookingBillingStatusListFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BookingBillingStatusList(
            $sm->getServiceLocator()->get('Booking\Service\BookingStatusService'),
            $sm->getServiceLocator()->get('Request')
        );
    }

}