<?php

namespace Backend\View\Helper\Billing;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BillingsStatsFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BillingsStats(
            $sm->getServiceLocator()->get('Booking\Service\BookingStatusService'),
        );
    }

}