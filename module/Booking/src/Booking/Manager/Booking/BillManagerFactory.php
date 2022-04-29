<?php

namespace Booking\Manager\Booking;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BillManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new BillManager($sm->get('Booking\Table\Booking\BillTable'));
    }

}