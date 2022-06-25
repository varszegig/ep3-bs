<?php

namespace Base\View\Helper\Layout;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class HeaderUserFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new HeaderUser(
            $sm->getServiceLocator()->get('User\Manager\UserSessionManager'),
        );
    }

}