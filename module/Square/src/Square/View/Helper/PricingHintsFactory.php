<?php

namespace Square\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class PricingHintsFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new PricingHints(
            $sm->getServiceLocator()->get('Base\Manager\OptionManager'),
            $sm->getServiceLocator()->get('Square\Manager\SquarePricingManager'),
            $sm->getServiceLocator()->get('User\Manager\UserSessionManager'));
    }

}