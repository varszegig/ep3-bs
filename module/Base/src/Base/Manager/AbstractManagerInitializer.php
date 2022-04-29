<?php

namespace Base\Manager;

use Laminas\ServiceManager\InitializerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class AbstractManagerInitializer implements InitializerInterface
{

    public function initialize($instance, ServiceLocatorInterface $sm)
    {
        if ($instance instanceof AbstractManager) {
            $instance->setTranslator($sm->get('Translator'));
        }
    }

}