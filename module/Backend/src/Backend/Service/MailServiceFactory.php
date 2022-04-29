<?php

namespace Backend\Service;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class MailServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        return new MailService(
            $sm->get('Base\Service\MailService'),
            $sm->get('Base\Manager\ConfigManager'),
            $sm->get('Base\Manager\OptionManager'));
    }

}