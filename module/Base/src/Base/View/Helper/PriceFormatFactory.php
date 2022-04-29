<?php

namespace Base\View\Helper;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class PriceFormatFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new PriceFormat($sm->getServiceLocator()->get('Base\Manager\OptionManager'));
    }

}