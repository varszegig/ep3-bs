<?php

namespace Base\View\Helper;

use Laminas\I18n\View\Helper\DateFormat;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class DateFormatFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $configManager = $sm->getServiceLocator()->get('Base\Manager\ConfigManager');
        
        $locale = $configManager->need('i18n.locale');

        $dateFormat = new DateFormat();
        $dateFormat->setLocale($locale);

        return $dateFormat;
    }

}