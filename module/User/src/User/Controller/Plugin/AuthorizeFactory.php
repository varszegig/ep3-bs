<?php

namespace User\Controller\Plugin;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class AuthorizeFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new Authorize($sm->getServiceLocator()->get('User\Manager\UserSessionManager'));
    }

}