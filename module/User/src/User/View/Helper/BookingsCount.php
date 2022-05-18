<?php

namespace User\View\Helper;

use Booking\Entity\Booking;
use Laminas\View\Helper\AbstractHelper;

class BookingsCount extends AbstractHelper
{

    protected $count;
    public function __invoke(array $bookings)
    {
        $view = $this->getView();

        $this->count = 0;
        $html = '<p>';
        foreach($bookings as $booking) $this->countBookings($booking);
        if ($this->count == 0) {
            $html .= sprintf($view->t('You have not booked any %s yet.'), $view->option('subject.square.type.plural'));
        } elseif ($this->count == 1) {
            $html .= sprintf($view->t('You have already booked one %s.'), $view->option('subject.square.type'));
        } else {
            $html .= sprintf($view->t('You have already booked %s %s.'), $this->count, $view->option('subject.square.type.plural'));
        }
        $html .= '</p>';

        return $html;
    }

    private function countBookings(Booking $booking) {
        $reservations = $booking->needExtra('reservations');

        $this->count += count($reservations);
    }

}