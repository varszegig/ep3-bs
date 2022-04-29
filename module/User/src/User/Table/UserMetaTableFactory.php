<?php

namespace User\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class UserMetaTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new UserMetaTable(UserMetaTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}