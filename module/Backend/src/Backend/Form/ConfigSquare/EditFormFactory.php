<?php

namespace Backend\Form\ConfigSquare;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class EditFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EditForm(
            $sm->getServiceLocator()->get('Square\Manager\SquareGroupManager'));
    }

}
