<?php

namespace Base\Controller\Plugin;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class CookieFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new Cookie($sm->getServiceLocator()->get('Base\Manager\ConfigManager'));
    }

}