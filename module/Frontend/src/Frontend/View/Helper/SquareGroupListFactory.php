<?php

namespace Frontend\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class SquareGroupListFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new SquareGroupList(
            $sm->getServiceLocator()->get('Square\Manager\SquareGroupManager'), 
            $sm->getServiceLocator()->get('Square\Manager\SquareManager'),
            $sm->getServiceLocator()->get('Request'));
    }

}