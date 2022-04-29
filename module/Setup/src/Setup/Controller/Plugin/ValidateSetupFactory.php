<?php

namespace Setup\Controller\Plugin;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ValidateSetupFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new ValidateSetup(
            $sm->getServiceLocator(),
            $sm->getServiceLocator()->get('Laminas\Db\Adapter\Adapter'));
    }

}