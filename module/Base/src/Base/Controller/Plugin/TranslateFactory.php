<?php

namespace Base\Controller\Plugin;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class TranslateFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new Translate($sm->getServiceLocator()->get('Translator'));
    }

}