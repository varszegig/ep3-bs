<?php

namespace Backend\Form\ConfigSquare;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class EditProductFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EditProductForm(
            $sm->getServiceLocator()->get('Base\Manager\ConfigManager'),
            $sm->getServiceLocator()->get('Square\Manager\SquareManager'));
    }

}