<?php

namespace Backend\View\Helper\Booking;

use Laminas\Stdlib\RequestInterface;
use Booking\Entity\Booking;
use Laminas\View\Helper\AbstractHelper;

class BookingStatusList extends AbstractHelper
{

    protected $request;

    public function __construct(RequestInterface $request)
    {

        $this->request = $request;
    }
    
    public function __invoke()
    {
        $html = '';
        $view = $this->getView();
        $bookingStatus = Booking::$statusOptions;
        $html .= '<select name="bs-status" id="booking-status" class="select">';
        $selectedStatus = $this->request->getQuery('bs-status');

        $html .= '<option value=""';
        if ($selectedStatus == '') $html .= ' selected="selected"';
        $html .= '></option>';

        foreach($bookingStatus as $status) {

                $html .= '<option value="' . $status . '"';
                if ($selectedStatus == $status) $html .= ' selected="selected"';
                $html .= '>' . $view->t($status) . '</option>';

        }

        return $html;
    }

}