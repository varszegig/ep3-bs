<?php

namespace Backend\Form\ConfigSquare;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class EditSquareGroupFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EditSquareGroupForm(
            $sm->getServiceLocator()->get('Base\Manager\ConfigManager'));
    }

}