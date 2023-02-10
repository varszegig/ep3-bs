<?php

namespace Backend\Controller\Plugin\Billing;

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
        $billingTotal = $search["sum"];
        $bookingStatus = $search["type"];
        $billingStatus = $search['billingStatus'];

        if ($userSearch) {
            $matches = [];
            if (preg_match('/\(([0-9]+)\)/', $userSearch, $matches)) {
                $filters[] = sprintf('uid = "%s"', $matches[1]);
            }            
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

        if ($billingTotal) {
            $key = 'billing_total';
            $operator =  '>';
            $value = $billingTotal;
            $filterParts[] = array($key, $operator, $value);
        } else $billingTotal = 0;

        if ($bookingStatus) {
            $filters[] = sprintf('status = "%s"', $bookingStatus);
        }

        return array(
            'search' => $search,
            'filters' => $filters,
            'filterParts' => $filterParts,
        );
    }

}