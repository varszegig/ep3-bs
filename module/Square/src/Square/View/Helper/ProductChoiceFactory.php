<?php

namespace Square\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ProductChoiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new ProductChoice($sm->getServiceLocator()->get('Square\Manager\SquareProductManager'));
    }

}