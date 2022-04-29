<?php

namespace User\Form;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class EditEmailFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EditEmailForm($sm->getServiceLocator()->get('User\Manager\UserManager'));
    }

}