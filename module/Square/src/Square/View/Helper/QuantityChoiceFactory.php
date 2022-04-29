<?php

namespace Square\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class QuantityChoiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new QuantityChoice($sm->getServiceLocator()->get('Base\Manager\OptionManager'));
    }

}