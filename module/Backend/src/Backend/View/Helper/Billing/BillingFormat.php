<?php

namespace Backend\View\Helper\Billing;

use Booking\Entity\Booking;
use Square\Manager\SquareManager;
use Laminas\View\Helper\AbstractHelper;

class BillingFormat extends AbstractHelper
{

    protected $squareManager;

    public function __construct(SquareManager $squareManager)
    {
        $this->squareManager = $squareManager;
    }

    public function __invoke(Booking $booking, $dateStart = null, $dateEnd = null, $search = null)
    {
        $view = $this->getView();
        $html = '';

        $reservations = $booking->needExtra('reservations');

        switch ($booking->need('status')) {
            case 'cancelled':
                $attr = ' class="gray"';
                break;
            default:
                $attr = null;
                break;
        }

        $html .= sprintf('<tr %s>', $attr);

        $html .= sprintf('<td class="status-col right-text">%s</td>',
            $view->t($booking->getStatus()));

        foreach($reservations as $reservation) {
            if (empty($startDate)) $startDate = new \DateTime($reservation->get('date'));
        }
        $endDate = new \DateTime($reservation->get('date'));

        $searchArray = ['bbsf-date-start' => $search['dateStart'],
                    'bbsf-date-end' => $search['dateEnd'],
                    'bbsf-sum' => $search['sum'],
                    'bbsf-name' => $search['user'],
                    'bbsf-type' => $search['type']];
        $editQuery = ['ds' => $reservation->get('date'),
                    'ts' => substr($reservation->get('time_start'), 0, 5),
                    'te' => substr($reservation->get('time_end'), 0, 5),
                    's' => $booking->get('sid'),
                    'r' => $reservation->get('rid')];

        $html .= sprintf('<td headers="%s"><a href="%s" class="unlined gray symbolic symbolic-edit">%s</a></td>',  
            $view->t('No.'), 
            $view->url('backend/booking/edit', [], ['query' => array_merge($editQuery, $searchArray)]),                
            $booking->need('bid'));

        if ($booking->getExtra('user')) {
            $userName = $booking->getExtra('user')->get('alias');
        } else {
            $userName = $booking->need('uid');
        }

        $html .= sprintf('<td headers="%s"><b>%s</b></td>', $view->t('Name'),
            $userName);

        $html .= sprintf('<td headers="%s"><b>%s</b></td>', $view->t('Sum'),
            $view->currencyFormat($booking->getExtra('bills_total')));


        $billingStatus = $booking->need('status_billing');
        $html .= sprintf('<td headers="%s">%s</td>', $view->t('Billing status'),
            $view->t(ucfirst($billingStatus)));

        /* Date and time col */

        $fullStartDate = $view->dateFormat($startDate, \IntlDateFormatter::SHORT, null, null, $view->t('dd.MM.yyyy'));
        $fullEndDate = $view->dateFormat($endDate, \IntlDateFormatter::SHORT, null, null, $view->t('dd.MM.yyyy'));

        if ($fullStartDate == $fullEndDate) {
            $fullDate = $fullStartDate;
        } else {
            $fullDate = $fullStartDate . ' - ' . $fullEndDate;
        }
        $html .= sprintf('<td headers="%s">%s</td>', $view->t('Day'),
            $fullDate);

        // $html .= sprintf('<td>%s</td>',
        //     $view->dateFormat($date, \IntlDateFormatter::MEDIUM, null, null, $view->t('dd.MM.yyyy')));


        $html .= sprintf('<td headers="%s">%s</td>', $view->t('Time'),
            $view->timeRange($reservation->get('time_start'), $reservation->get('time_end'), '%s - %s'));

        /* Square col */

        if ($booking->get('sid')) {
            $squareName = $this->squareManager->get($booking->get('sid'))->get('name');
        } else {
            $squareName = '-';
        }

        $html .= sprintf('<td headers="%s">%s</td>', $view->option('subject.square.type'),
            $squareName);

        /* Notes col */

        $notes = $booking->getMeta('notes');

        if ($notes) {
            if (strlen($notes) > 48) {
                $notes = substr($notes, 0, 48) . '&hellip;';
            }

            $notes = '<span class="small-text">' . $notes . '</span>';
        } else {
            $notes = '-';
        }

        $html .= sprintf('<td class="notes-col" headers="%s">%s</td>', $view->t('Notes'),
            $notes);

        /* Actions col */

        if ($booking->get('status') == 'cancelled') {

            $html .= sprintf('<td class="actions-col no-print"><a href="%s" class="unlined gray symbolic symbolic-edit">%s</a></td>',
                $view->url('backend/booking/bills' , ['bid' => $booking->need('bid')]),
                $view->t('Edit'));

        } else {
            
            $html .= sprintf('<td class="actions-col no-print"><a href="%s" class="unlined gray symbolic symbolic-edit">%s</a></td>',
                $view->url('backend/booking/bills', ['bid' => $booking->need('bid')], 
                    ['query' => $searchArray]),
                $view->t('Edit'));
        }

        $html .= '</tr>';

        return $html;
    }

}
