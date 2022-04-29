<?php

namespace Base\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ConfigFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new Config($sm->getServiceLocator()->get('Base\Manager\ConfigManager'));
    }

}