<?php

namespace Backend\View\Helper\User;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class UserOperatorSelectFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new UserOperatorSelect(
            $sm->getServiceLocator()->get('Request')
        );
    }

}