<?php

namespace Base\Controller\Plugin;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class RedirectBackFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new RedirectBack($sm->getServiceLocator()->get('Base\Manager\ConfigManager'));
    }

}