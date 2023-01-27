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

        if ($userSearch) {
            $matches = [];
            if (preg_match('/\(([0-9]+)\)/', $userSearch, $matches)) {
                $filters[] = sprintf('uid = "%s"', $matches[1]);
            }            
        }

        if ($billingTotal) {
            $key = 'billing_total';
            $operator =  '>';
            $value = $billingTotal;
            $filterParts[] = array($key, $operator, $value);
        } else $billingTotal = 0;


        return array(
            'search' => $search,
            'filters' => $filters,
            'filterParts' => $filterParts,
        );
    }

}