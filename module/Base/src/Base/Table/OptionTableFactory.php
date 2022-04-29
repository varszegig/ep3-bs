<?php

namespace Base\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class OptionTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new OptionTable(OptionTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}