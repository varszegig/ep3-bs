<?php

namespace User\Table;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class UserTableFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new UserTable(UserTable::NAME, $sm->get('Laminas\Db\Adapter\Adapter'));
    }

}