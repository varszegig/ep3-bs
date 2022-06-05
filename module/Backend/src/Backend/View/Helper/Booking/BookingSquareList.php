<?php

namespace Backend\View\Helper\Booking;

use Laminas\Stdlib\RequestInterface;
use Square\Manager\SquareManager;
use Laminas\View\Helper\AbstractHelper;

class BookingSquareList extends AbstractHelper
{

    protected $squareManager;
    protected $request;

    public function __construct(SquareManager $squareManager, RequestInterface $request)
    {

        $this->squareManager = $squareManager;
        $this->request = $request;
    }
    
    public function __invoke()
    {
        $html = '';
        $view = $this->getView();
        $squares = $this->squareManager->getAll();
        $html .= '<select name="bs-square" id="square" class="select">';
        $selectedSquare = $this->request->getQuery('bs-square');

        $html .= '<option value=""';
        if ($selectedSquare == '') $html .= ' selected="selected"';
        $html .= '></option>';

        foreach($squares as $square) {

                $squareId = $square->need('sid');
                $html .= '<option value="' . $squareId . '"';
                if ($selectedSquare == $squareId) $html .= ' selected="selected"';
                $html .= '>' . $square->need('name') . '</option>';

        }

        return $html;
    }

}