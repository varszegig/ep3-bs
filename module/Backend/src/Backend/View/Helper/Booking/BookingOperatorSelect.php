<?php

namespace Backend\View\Helper\Booking;

use Laminas\Stdlib\RequestInterface;
use Booking\Entity\Booking;
use Laminas\View\Helper\AbstractHelper;

class BookingOperatorSelect extends AbstractHelper
{

    protected $request;
    
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function __invoke($prefix)
    {
        $html = '';
        $operators = array('<', '=', '>');
        $html .= '<select name="' . $prefix . '-operator" id="' . $prefix . '-operator" class="select">';
        $selectedValue = $this->request->getQuery($prefix . '-operator');

        $html .= '<option value=""';
        if ($selectedValue == '') $html .= ' selected="selected"';
        $html .= '></option>';

        foreach($operators as $operator) {

                $html .= '<option value="' . $operator . '"';
                if ($selectedValue == $operator) $html .= ' selected="selected"';
                $html .= '>' . $operator . '</option>';

        }

        $html .= '</select>';

        return $html;
    }

}