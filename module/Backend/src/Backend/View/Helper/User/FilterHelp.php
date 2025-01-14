<?php

namespace Backend\View\Helper\User;

use Laminas\View\Helper\AbstractHelper;

class FilterHelp extends AbstractHelper
{

    public function __invoke($key, $operator, $value, $class = 'usf-filter-snippet')
    {
        $view = $this->getView();

        if ($value instanceof \DateTime) {
            $value = $view->dateFormat($value, \IntlDateFormatter::MEDIUM, null, null, $view->t('dd.MM.yyyy'));
        }

        return sprintf('<div><a href="#" class="unlined gray %s"><code>(%s %s %s)</code></a></div>',
            $class,
            str_replace(' ', '_', strtolower($view->t($key))),
            $operator,
            mb_strtolower($view->t($value)));
    }

}
