<?php

namespace Backend\View\Helper\Event;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class EventFormatFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EventFormat($sm->getServiceLocator()->get('Square\Manager\SquareManager'));
    }

}