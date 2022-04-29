<?php

namespace Square\Manager;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SquarePricingManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new SquarePricingManager(
            $sm->get('Square\Table\SquarePricingTable'),
            $sm->get('Square\Manager\SquareManager'));
    }

}