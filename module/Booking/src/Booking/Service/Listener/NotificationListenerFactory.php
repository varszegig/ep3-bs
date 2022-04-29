<?php

namespace Booking\Service\Listener;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class NotificationListenerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new NotificationListener(
            $sm->get('Base\Manager\OptionManager'),
            $sm->get('Booking\Manager\ReservationManager'),
            $sm->get('Square\Manager\SquareManager'),
            $sm->get('User\Manager\UserManager'),
            $sm->get('User\Service\MailService'),
            $sm->get('Backend\Service\MailService'),
            $sm->get('ViewHelperManager')->get('DateFormat'),
            $sm->get('ViewHelperManager')->get('DateRange'),
            $sm->get('Translator'));
    }

}
