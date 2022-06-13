<?php

namespace Backend\View\Helper\User;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class UserStatusListFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new UserStatusList(
            $sm->getServiceLocator()->get('Request')
        );
    }

}