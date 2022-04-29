<?php

namespace Calendar\Controller\Plugin;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class DetermineSquaresFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new DetermineSquares($sm->getServiceLocator()->get('Square\Manager\SquareManager'));
    }

}