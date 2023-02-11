<?php

namespace Backend\View\Helper\Billing;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BillingFormatFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BillingFormat($sm->getServiceLocator()->get('Square\Manager\SquareManager'));
    }

}