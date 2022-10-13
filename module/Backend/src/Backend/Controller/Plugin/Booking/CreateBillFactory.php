<?php

namespace Backend\Controller\Plugin\Booking;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class CreateBillFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $sm = $sm->getServiceLocator();

        return new CreateBill(
            $sm->get('Base\Manager\OptionManager'),
            $sm->get('ViewHelperManager'),
            $sm->get('Square\Manager\SquarePricingManager'),
            $sm->get('Booking\Manager\Booking\BillManager'),
            $sm->get('User\Manager\UserManager'),
        );
    }

}