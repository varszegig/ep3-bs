<?php

namespace Backend\View\Helper\Billing;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BillingBillingStatusListFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BillingBillingStatusList(
            $sm->getServiceLocator()->get('Booking\Service\BookingStatusService'),
            $sm->getServiceLocator()->get('Request')
        );
    }

}