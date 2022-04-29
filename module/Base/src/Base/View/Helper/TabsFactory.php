<?php

namespace Base\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class TabsFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new Tabs( $sm->getServiceLocator()->get('Request')->getUri()->getPath() );
    }

}