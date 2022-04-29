<?php

namespace Base\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class VersionFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new Version(
            $sm->getServiceLocator()->get('Base\Manager\ConfigManager'));
    }

}