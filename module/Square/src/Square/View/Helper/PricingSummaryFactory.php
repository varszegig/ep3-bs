<?php

namespace Square\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class PricingSummaryFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new PricingSummary(
            $sm->getServiceLocator()->get('Base\Manager\OptionManager'),
            $sm->getServiceLocator()->get('Square\Manager\SquarePricingManager'),
            $sm->getServiceLocator()->get('User\Manager\UserSessionManager'));
    }

}