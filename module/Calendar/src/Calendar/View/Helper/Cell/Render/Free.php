<?php

namespace Calendar\View\Helper\Cell\Render;

use Square\Entity\Square;
use Laminas\View\Helper\AbstractHelper;
use Square\Manager\SquarePricingManager;

class Free extends AbstractHelper
{
    
    public function __invoke($user, $userBooking, array $reservations, array $cellLinkParams, Square $square)
    {
        $view = $this->getView();

        $serviceManager = $view->getHelperPluginManager()->getServiceLocator();
        
        $squarePricingManager = $serviceManager->get('Square\Manager\SquarePricingManager');
        $dateTimeStart = new \DateTime($cellLinkParams["query"]["ds"] . ' ' . $cellLinkParams["query"]["ts"]);
        $rangeTimeStart = new \DateTime($cellLinkParams["query"]["ds"] . ' ' . "0:00");
        $dateTimeEnd = new \DateTime($cellLinkParams["query"]["ds"] . ' ' . $cellLinkParams["query"]["te"]);
        $rangeTimeEnd = new \DateTime($cellLinkParams["query"]["ds"] . ' ' . "23:00");

        if ($user && $user->getMeta('club-card')) {
            $priceType = 3;
        } else {
            $priceType = 1;
        }
        $pricing = $squarePricingManager->
            getFinalPricingInRange($dateTimeStart, $dateTimeEnd, $square, 1, $priceType);
        $minMaxPrice = $squarePricingManager->
            getMinMaxPricingInRange($rangeTimeStart, $rangeTimeEnd, $square, 1, $priceType);
        $minPrice = $minMaxPrice["minPrice"];
        $maxPrice = $minMaxPrice["maxPrice"]; 
        
	    $labelFree = $square->getMeta('label.free', $this->view->t('Free'));
        $priceClass = "cc-free";
        if ($pricing) {
            $price = $pricing["price"];
            $labelFree = $view->currencyFormat($price / 100);
            if ($price == $minPrice) {
                $priceClass = "cc-free-min";
            }
            if ($price == $maxPrice) {
                $priceClass = "cc-free-max";
            }
        } else {
            $priceClass = "cc-free-hidden";
        }


        if ($user && $user->can('calendar.see-data, calendar.create-single-bookings, calendar.create-subscription-bookings')) {
            return $view->calendarCellRenderFreeForPrivileged($reservations, $cellLinkParams, $square, $priceType);
        } else if ($user) {
            if ($userBooking) {
                $cellLabel = $view->t('Your Booking');
                $cellGroup = ' cc-group-' . $userBooking->need('bid');

                return $view->calendarCellLink($cellLabel, $view->url('square', [], $cellLinkParams), 'cc-own' . $cellGroup);
            } else {
                return $view->calendarCellLink($labelFree, $view->url('square', [], $cellLinkParams), $priceClass);
            }
        } else {
            return $view->calendarCellLink($labelFree, $view->url('square', [], $cellLinkParams), $priceClass);
        }
    }

}
