<?php

namespace Base\Controller\Plugin;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class DateFormatFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new DateFormat($sm->getServiceLocator()->get('ViewHelperManager')->get('DateFormat'));
    }

}