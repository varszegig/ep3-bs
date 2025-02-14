<?php

namespace Base\Controller\Plugin;

use Laminas\I18n\View\Helper\NumberFormat as NumberFormatHelper;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class NumberFormat extends AbstractPlugin
{

    protected $numberFormatHelper;

    public function __construct(NumberFormatHelper $numberFormatHelper)
    {
        $this->numberFormatHelper = $numberFormatHelper;
    }

    public function __invoke($number, $formatStyle = null, $formatType = null, $locale = null, $decimals = null)
    {
        $numberFormatHelper = $this->numberFormatHelper;

        return $numberFormatHelper($number, $formatStyle, $formatType, $locale, $decimals);
    }

}