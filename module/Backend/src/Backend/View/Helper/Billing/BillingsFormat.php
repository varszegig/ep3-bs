<?php

namespace Backend\View\Helper\Billing;

use Laminas\View\Helper\AbstractHelper;

class BillingsFormat extends AbstractHelper
{

    public function __invoke(array $bookings, $dateStart = null, $dateEnd = null, $search = null)
    {
        $view = $this->getView();
        $html = '';

        $html .= '<table class="bordered-table">';

        $html .= '<tr class="gray">';
        $html .= '<th>&nbsp;</th>';
        $html .= '<th>' . $view->t('No.') . '</th>';
        $html .= '<th>' . $view->t('Name') . '</th>';
        $html .= '<th>' . $view->t('Sum') . '</th>';
        $html .= '<th>' . $view->t('Billing status') . '</th>';
        $html .= '<th>' . $view->t('Day') . '</th>';
        $html .= '<th>' . $view->t('Time') . '</th>';
        $html .= '<th>' . $view->option('subject.square.type') . '</th>';
        $html .= '<th class="notes-col">' . $view->t('Notes') . '</th>';
        $html .= '<th class="no-print">&nbsp;</th>';
        $html .= '</tr>';

        foreach ($bookings as $booking) {
            $html .= $view->backendBillingFormat($booking, $dateStart, $dateEnd, $search);
        }

        $html .= '</table>';

        $html .= '<style type="text/css"> .status-col, .actions-col { border: none !important; } </style>';

        $view->headScript()->appendFile($view->basePath('js/controller/backend/booking/index.min.js'));

        return $html;
    }

}