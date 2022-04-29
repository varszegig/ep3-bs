<?php

namespace Base\View\Helper;

use Laminas\View\Helper\AbstractHelper;

class TimeRange extends AbstractHelper
{

    public function __invoke($timeStart, $timeEnd, $wording = 'from %s to %s', $timezone = null)
    {
        $view = $this->getView();

        return sprintf($view->translate($wording),
            $view->timeFormat($timeStart, false, $timezone, false),
            $view->timeFormat($timeEnd, true, $timezone, true));
    }

}