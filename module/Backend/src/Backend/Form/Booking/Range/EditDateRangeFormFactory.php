<?php

namespace Backend\Form\Booking\Range;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class EditDateRangeFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new EditDateRangeForm(
            $sm->getServiceLocator()->get('Booking\Service\BookingService'));   
    }

}
