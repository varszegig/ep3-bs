<?php

namespace Base\View\Helper;

use Laminas\I18n\View\Helper\NumberFormat;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class NumberFormatFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $configManager = $sm->getServiceLocator()->get('Base\Manager\ConfigManager');

        $locale = $configManager->need('i18n.locale');

        $numberFormat = new NumberFormat();
        $numberFormat->setLocale($locale);

        return $numberFormat;
    }

}