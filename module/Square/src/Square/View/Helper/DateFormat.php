<?php

namespace Square\View\Helper;

use DateTime;
use IntlDateFormatter;
use Laminas\View\Helper\AbstractHelper;

class DateFormat extends AbstractHelper
{

    public function __invoke(DateTime $dateTimeStart, DateTime $dateTimeEnd)
    {
        $view = $this->getView();
        $html = '';

        if ($dateTimeStart->format('Y-m-d') == $dateTimeEnd->format('Y-m-d')) {
            $html .= sprintf('<p>%s<br>%s',
                $view->dateFormat($dateTimeStart, IntlDateFormatter::FULL, null, null, $view->t('eeee, y. MMMM d.')),
                $view->timeRange($dateTimeStart, $dateTimeEnd, '%s to %s'));
        } else {
            $dateStartFormat = $view->dateFormat($dateTimeStart, IntlDateFormatter::FULL, null, null, $view->t('eeee, y. MMMM d.'));
            $timeStartFormat = $view->timeFormat($dateTimeStart);

            $dateEndFormat = $view->dateFormat($dateTimeEnd, IntlDateFormatter::FULL, null, null, $view->t('eeee, y. MMMM d.'));
            $timeEndFormat = $view->timeFormat($dateTimeEnd);

            $html .= sprintf('<p>%s - %s</p>', $dateStartFormat, $timeStartFormat);
            $html .= sprintf('<p style="margin-bottom: 0px; position: relative; top: -8px;">%s</p>', $view->t('until'));
            $html .= sprintf('<p>%s - %s</p>', $dateEndFormat, $timeEndFormat);
        }

        return $html;
    }

}