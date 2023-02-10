<?php

namespace Backend\View\Helper\Billing;

use Laminas\View\Helper\AbstractHelper;
use Booking\Entity\Booking;
use Booking\Service\BookingStatusService;

class BillingsStats extends AbstractHelper
{

    public function __construct(BookingStatusService $bookingStatusService)
    {
        $this->bookingStatusService = $bookingStatusService;
    }
    public function __invoke($stats)
    {
        $view = $this->getView();
        $bookingStatus = Booking::$statusOptions;
        $billingStatus = $this->bookingStatusService->getStatusTitles();
        $html = '';

        $statTable = array();
        $grandTotal = array();

        foreach(array_keys($bookingStatus) as $bookingStatKey) {
            $grandTotal[$bookingStatKey] = 0;
            foreach (array_keys($billingStatus) as $billingStatKey) {
                $statTable[$bookingStatKey][$billingStatKey] = 0;
            }
        }

        foreach ($stats as $stat) {
            $statTable[$stat['status']][$stat['status_billing']] = $stat['sum'];
        }

        $html .= '<div>';
        $html .= '<h1>' . $view->t('Booking-Statistic') . '</h1>';
        
        $html .= '<div class="separator separator-line"></div>';
        
        $html .= '<table class="bordered-table">';

        $html .= '<tr>';
        $html .= '<th></th>';
        foreach (array_keys($bookingStatus) as $bookingStatKey) {
            $html .= '<th>' . $view->t(ucfirst($bookingStatKey)) . '</th>';
        }
        $html .= '<th><b>' . $view->t('Total') . '</b></th>';
        $html .= '</tr>';
        foreach(array_keys($billingStatus) as $billingStatKey) {
            $html .= '<tr>';
            $html .= '<td>' . $view->t(ucfirst($billingStatKey)) . '</td>';
            $total = 0;
            foreach (array_keys($bookingStatus) as $bookingStatKey) {
                $value = $statTable[$bookingStatKey][$billingStatKey];
                $total += $value;
                $grandTotal[$bookingStatKey] += $value;
                $html .= '<td class="right-text" headers="' . $view->t(ucfirst($bookingStatKey)) . '">';
                if ($value > 0) {
                    $html .= '<a href="'; 
                    $html .= $view->url('backend/billing', [], 
                        ['query' => ['bbsf-type' => $bookingStatus[$bookingStatKey], 
                         'bbsf-billing-status' => $billingStatKey ]]);
                    $html .= '">';
                }
                $html .= $view->currencyFormat($value / 100);
                if ($value > 0) $html .= '</a>';
                $html .= '</td>';
            }
            $html .= '<td class="right-text" headers="' . $view->t('Total') . '"><b>';
            if ($total > 0) {
                $html .= '<a href="'; 
                $html .= $view->url('backend/billing', [], 
                    ['query' => ['bbsf-billing-status' => $billingStatKey ]]);
                $html .= '">';
            }            
            $html .= $view->currencyFormat($total / 100);
            if ($total > 0) $html .= '</a>';
            $html .= '</b></td>';
            $html .= '</tr>';
        }

        $html .= '<tr>';
        $html .= '<td><b>' . $view->t('Total') . '</b></td>';
        $total  = 0;
        foreach (array_keys($bookingStatus) as $bookingStatKey) {
            $value = $grandTotal[$bookingStatKey];
            $html .= '<td class="right-text" headers="' . $view->t(ucfirst($bookingStatKey)) . '"><b>';
            if ($value > 0) {
                $html .= '<a href="'; 
                $html .= $view->url('backend/billing', [], 
                    ['query' => ['bbsf-type' => $bookingStatKey ]]);
                $html .= '">';
            } 
            $html .= $view->currencyFormat($value / 100);
            if ($value > 0) $html .= '</a>';
            $html .= '</b></td>';
            $total += $grandTotal[$bookingStatKey];
        }
        $html .= '<td class="right-text" headers="' . $view->t('Total') . '"><b>' . $view->currencyFormat($total /100) . '</b></td>';
        $html .= '</tr>';
       
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

}