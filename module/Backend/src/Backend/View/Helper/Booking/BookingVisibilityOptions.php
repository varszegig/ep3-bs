<?php

namespace Backend\View\Helper\Booking;

use Laminas\Stdlib\RequestInterface;
use Booking\Entity\Booking;
use Laminas\View\Helper\AbstractHelper;

class BookingVisibilityOptions extends AbstractHelper
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
        $bookingVisibility = Booking::$visibilityOptions;
        $html .= '<select name="bs-visibility" id="visibility-options" class="select">';
        $selectedVisibility = $this->request->getQuery('bs-visibility');

        $html .= '<option value=""';
        if ($selectedVisibility == '') $html .= ' selected="selected"';
        $html .= '></option>';

        foreach($bookingVisibility as $option) {

                $html .= '<option value="' . $option . '"';
                if ($selectedVisibility == $option) $html .= ' selected="selected"';
                $html .= '>' . $view->t($option) . '</option>';

        }

        return $html;
    }

}