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
        $bookingStatus = $search["status"];
        $billingStatus = $search["billingStatus"];
        $visibility = $search["visibility"];


        if ($userSearch) {
            $matches = [];
            if (preg_match('/\(([0-9]+)\)/', $userSearch, $matches)) {
                $filters[] = sprintf('uid = "%s"', $matches[1]);
                $filterParts[] = array('uid', '=', $matches[1]);
            }            
        }

        if ($square) {
            $filters[] = sprintf('sid = "%s"', $square);
            $filtersParts[] = array('sid', '=', $square);            
        }

        if ($bookingStatus) {
            $filters[] = sprintf('status = "%s"', $bookingStatus);
            $filtersParts[] = array('status', '=', $bookingStatus);
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
            $filtersParts[] = array('status_billing', '=', $value);
        }

        if ($visibility) {
            $filters[] = sprintf('visibility = "%s"', $visibility);
            $filtersParts[] = array('visibility', '=', $visibility);
        }

        // preg_match_all('/\(([^\(\)]+[<=>][^\(\)]+)\)/', $search, $matches);

        // if ($matches) {

            /* Determine filters from matches */

            // foreach ($matches[1] as $match) {
            //     $parts = preg_split('/([<=>])/', $match, -1, PREG_SPLIT_DELIM_CAPTURE);

            //     $key = strtolower(trim($parts[0]));
            //     $operator = trim($parts[1]);
            //     $value = trim($parts[2]);

                
                // Translate keys
                // $key = str_replace(
                //     array(
                //         str_replace(' ', '_', strtolower($controller->t('User ID'))),
                //         strtolower($controller->t('User')),
                //         str_replace(' ', '_', strtolower($controller->t('Square ID'))),
                //         str_replace(' ', '_', strtolower($controller->t('Billing status'))),
                //         strtolower($controller->t('Visibility')),
                //         strtolower($controller->t('Quantity')),
                //         strtolower($controller->t('Created')),
                //         strtolower($controller->t('Status')),
                //     ),
                //     array('uid', 'uid', 'sid', 'status_billing', 'visibility', 'quantity', 'created', 'status'),
                //     $key);

                // Translate values
                // $value = str_replace(
                //     array(
                //         strtolower($controller->t('Single')),
                //         strtolower($controller->t('Subscription')),
                //         strtolower($controller->t('Cancelled')),
                //         strtolower($controller->t('Pending')),
                //         strtolower($controller->t('Paid')),
                //         strtolower($controller->t('Uncollectable')),
                //         strtolower($controller->t('Public')),
                //         strtolower($controller->t('private')),
                //         ),
                //     array('single', 'subscription', 'cancelled', 'pending', 'paid', 'uncollectable', 'public', 'private'),
                //     $value);

                // Transform dates
        //         try {
        //             switch ($key) {
        //                 case 'created':
        //                     if (preg_match('/[0-3]?[0-9]\.[0-1]?[0-9]\.[1-2][0-9]{3}/', $value)) {
        //                         $value = implode('-', array_reverse(explode('.', $value)));
        //                     }

        //                     $value = (new \DateTime($value))->format('Y-m-d');
        //                     break;
        //             }
        //         } catch (\RuntimeException $e) {
        //             break;
        //         }
            
        //         $filterParts[] = array($key, $operator, $value);

        //         if ($key == str_replace(' ', '_', strtolower($controller->t('Billing total')))) {
        //             continue;
        //         }

        //         $filters[] = sprintf('%s %s "%s"', $key, $operator, $value);
        //     }

        // }
        

        return array(
            'search' => $search,
            'filters' => $filters,
            'filterParts' => $filterParts,
        );
    }

}
