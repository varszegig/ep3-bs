<?php

namespace Base\View\Helper\Layout;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class HeaderLocaleChoiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new HeaderLocaleChoice(
            $sm->getServiceLocator()->get('Base\Manager\ConfigManager'),
            $sm->getServiceLocator()->get('Request')->getUri());
    }

}