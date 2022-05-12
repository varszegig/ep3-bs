<?php

namespace Base\View\Helper;

use DateTime;
use IntlDateFormatter;
use Laminas\View\Helper\AbstractHelper;

class DateRange extends AbstractHelper
{

    public function __invoke(DateTime $dateTimeStart, DateTime $dateTimeEnd)
    {
        $view = $this->getView();

        if ($dateTimeStart->format('Y-m-d') == $dateTimeEnd->format('Y-m-d')) {

            return sprintf('%s, %s',
                $view->dateFormat($dateTimeStart, IntlDateFormatter::MEDIUM, null, null, $view->t('dd.MM.yyyy')),
                $view->timeRange($dateTimeStart, $dateTimeEnd, '%s to %s'));

        } else {

            $formatStart = $view->dateFormat($dateTimeStart, IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT, null, $view->t('dd.MM.yyyy'));
            $formatEnd = $view->dateFormat($dateTimeEnd, IntlDateFormatter::MEDIUM, IntlDateFormatter::SHORT, null, $view->t('dd.MM.yyyy'));

            $locale = $view->config('i18n.locale');

            if ($locale == 'de_DE' || $locale == 'de-DE') {
                $formatEnd .= ' Uhr';
            }

            return sprintf($view->t('%s to %s'), $formatStart, $formatEnd);
        }
    }

}