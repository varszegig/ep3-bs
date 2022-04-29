<?php

namespace User\Manager;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class UserSessionManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new UserSessionManager(
            $sm->get('Base\Manager\ConfigManager'),
            $sm->get('User\Manager\UserManager'),
            $sm->get('Laminas\Session\SessionManager'));
    }

}