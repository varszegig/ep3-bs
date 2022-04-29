<?php

namespace Base\Service;

use Laminas\ServiceManager\InitializerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class AbstractServiceInitializer implements InitializerInterface
{

    public function initialize($instance, ServiceLocatorInterface $sm)
    {
        if ($instance instanceof AbstractService) {
            $instance->setTranslator($sm->get('Translator'));
        }
    }

}