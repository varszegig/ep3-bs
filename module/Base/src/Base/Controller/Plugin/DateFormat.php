<?php

namespace Base\Controller\Plugin;

use DateTime;
use IntlDateFormatter;
use Laminas\I18n\View\Helper\DateFormat as DateFormatHelper;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class DateFormat extends AbstractPlugin
{

    protected $dateFormatHelper;

    public function __construct(DateFormatHelper $dateFormatHelper)
    {
        $this->dateFormatHelper = $dateFormatHelper;
    }

    public function __invoke($dateTime, $dateType = IntlDateFormatter::MEDIUM, $timeType = IntlDateFormatter::NONE, $locale = null, $pattern=null)
    {
        if (! $dateTime) {
            return null;
        }
        
        if (! ($dateTime instanceof DateTime)) {
            $dateTime = new DateTime($dateTime);
        } 

     
        $dateFormatHelper = $this->dateFormatHelper;

        return $dateFormatHelper($dateTime, $dateType, $timeType, $locale, $pattern);
    }

}