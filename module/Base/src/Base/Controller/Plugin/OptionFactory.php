<?php

namespace Base\Controller\Plugin;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class OptionFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new Option($sm->getServiceLocator()->get('Base\Manager\OptionManager'));
    }

}