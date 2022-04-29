<?php

namespace User\Manager;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class UserManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new UserManager(
            $sm->get('User\Table\UserTable'),
            $sm->get('User\Table\UserMetaTable'));
    }

}