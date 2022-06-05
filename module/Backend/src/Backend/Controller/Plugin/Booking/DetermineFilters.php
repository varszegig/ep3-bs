<?php

namespace Backend\Controller\Plugin\Booking;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Db\Sql\Predicate\In;

class DetermineFilters extends AbstractPlugin
{

    public function __invoke($search)
    {
        $controller = $this->getController();

        $matches = array();

        $filters = array();
        $filterParts = array();

        $userSearch = $search["user"];
        $square = $search['square'];
        $bookingStatus = $search['status'];
        $billingStatus = $search['billingStatus'];
        $visibility = $search['visibility'];
        $billingTotalOperator = $search['billingTotalOperator'];
        $billingTotal = $search['billingTotal'];
        $quantity = $search['quantity'];
        $quantityOperator = $search['quantityOperator'];
        $dateCreatedOperator = $search['dateCreatedOperator'];
        $dateCreated = $search['dateCreated'];
        $notes = $search['notes'];


        if ($userSearch) {
            $matches = [];
            if (preg_match('/\(([0-9]+)\)/', $userSearch, $matches)) {
                $filters[] = sprintf('uid = "%s"', $matches[1]);
            }            
        }

        if ($square) {
            $filters[] = sprintf('sid = "%s"', $square);
        }

        if ($bookingStatus) {
            $filters[] = sprintf('status = "%s"', $bookingStatus);
        }

        if ($billingStatus) {
            $value = str_replace(
                array(
                    strtolower($controller->t('Cancelled')),
                    strtolower($controller->t('Pending')),
                    strtolower($controller->t('Paid')),
                    strtolower($controller->t('Uncollectable')),
                    ),
                array('cancelled', 'pending', 'paid', 'uncollectable'),
                $billingStatus);            
            $filters[] = sprintf('status_billing = "%s"', $value);
        }

        if ($visibility) {
            $filters[] = sprintf('visibility = "%s"', $visibility);
        }

        if ($quantity) {
            $filters[] = sprintf('quantity %s "%s"', $quantityOperator, $quantity);
        }

        if ($billingTotalOperator) {
            if (!$billingTotal) $billingTotal = 0;
            $key = 'billing_total';
            $operator =  $billingTotalOperator;
            $value = $billingTotal;
            $filterParts[] = array($key, $operator, $value);

        }

        if ($dateCreatedOperator && $dateCreated) {
            try {
                $value = (new \DateTime($dateCreated))->format('Y-m-d');
            } catch (\RuntimeException $e) {
                $value = '';
            }

            if ($dateCreatedOperator == '=') {
                $nextDay = (new \DateTime($dateCreated))->modify("+1 day")->format('Y-m-d');
                $filters[] = sprintf('created < "%s"', $nextDay);
                $dateCreatedOperator = '>';
            }
            $filters[] = sprintf('created %s "%s"', $dateCreatedOperator, $value);
        }

        if ($notes) {
            $key = 'notes';
            $operator =  '=';
            $value = $notes;
            $filterParts[] = array($key, $operator, $value);

        }

        return array(
            'search' => $search,
            'filters' => $filters,
            'filterParts' => $filterParts,
        );
    }

}