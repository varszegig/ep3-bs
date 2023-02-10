<?php

namespace Backend\View\Helper\Booking;

use Booking\Entity\Reservation;
use Square\Manager\SquareManager;
use Laminas\View\Helper\AbstractHelper;

class BookingFormat extends AbstractHelper
{

    protected $squareManager;

    public function __construct(SquareManager $squareManager)
    {
        $this->squareManager = $squareManager;
    }

    public function __invoke(Reservation $reservation, $dateStart = null, $dateEnd = null, $search = null)
    {
        $view = $this->getView();
        $html = '';

        $booking = $reservation->needExtra('booking');

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

        $html .= sprintf('<td headers="%s">%s</td>',  $view->t('No.'),
            $booking->need('bid'));

        if ($booking->getExtra('user')) {
            $userName = $booking->getExtra('user')->get('alias');
        } else {
            $userName = $booking->need('uid');
        }

        $html .= sprintf('<td headers="%s"><b>%s</b></td>', $view->t('Name'),
            $userName);

        $billingStatus = $booking->need('status_billing');
        $html .= sprintf('<td headers="%s">%s</td>', $view->t('Billing status'),
            $view->t(ucfirst($billingStatus)));

        /* Date and time col */

        $date = new \DateTime($reservation->get('date'));

        $fullDate = $view->dateFormat($date, \IntlDateFormatter::FULL, null, null, $view->t('eeee, y. MMMM d.'));
        $fullDateParts = explode(', ', $fullDate);

        $html .= sprintf('<td headers="%s">%s</td>', $view->t('Day'),
            $fullDateParts[0]);

        // $html .= sprintf('<td>%s</td>',
        //     $view->dateFormat($date, \IntlDateFormatter::MEDIUM, null, null, $view->t('dd.MM.yyyy')));


        $html .= sprintf('<td headers="%s">%s</td>', $view->t('Time'),
            $view->timeRange($reservation->get('time_start'), $reservation->get('time_end'), '%s to %s'));

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
        $queryArray = ['ds' => $date->format('Y-m-d'),
                       'ts' => substr($reservation->get('time_start'), 0, 5),
                       'te' => substr($reservation->get('time_end'), 0, 5),
                       's' => $booking->get('sid'),
                       'r' => $reservation->get('rid'),
                       'starting-from' => 'booking'];

        if ($booking->get('status') == 'cancelled') {

            $html .= sprintf('<td class="actions-col no-print"><a href="%s" class="unlined gray symbolic symbolic-edit">%s</a></td>',
                $view->url('backend/booking/edit', [], ['query' => array_merge($queryArray, $search)]),
                $view->t('Edit'));

        } else {

            $html .= sprintf('<td class="actions-col no-print"><a href="%s" class="unlined gray symbolic symbolic-edit">%s</a></td>',
                $view->url('backend/booking/edit', [], ['query' => array_merge($queryArray, $search)]),
                $view->t('Edit'));
        }

        $html .= '</tr>';

        return $html;
    }

}
