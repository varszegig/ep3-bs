<?php

namespace Base\View\Helper;

use Laminas\I18n\View\Helper\CurrencyFormat;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class CurrencyFormatFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
        $configManager = $sm->getServiceLocator()->get('Base\Manager\ConfigManager');

        $locale = $configManager->need('i18n.locale');
        $currency = $configManager->need('i18n.currency');

        $currencyFormat = new CurrencyFormat();
        $currencyFormat->setCurrencyCode($currency);
        $currencyFormat->setLocale($locale);
        if ($currency == 'HUF') $currencyFormat->setShouldShowDecimals(false);

        return $currencyFormat;
    }

}