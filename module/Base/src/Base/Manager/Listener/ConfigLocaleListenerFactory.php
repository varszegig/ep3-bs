<?php

namespace Base\Manager\Listener;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ConfigLocaleListenerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new ConfigLocaleListener($sm->get('Request'));
    }

}