<?php

namespace Backend\View\Helper\Square;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class ProductFormatFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new ProductFormat($sm->getServiceLocator()->get('Square\Manager\SquareManager'));
    }

}