<?php

namespace Backend\Controller;

use User\Entity\User;
use Booking\Entity\Booking;
use Booking\Entity\Reservation;
use Booking\Table\BookingTable;
use Booking\Table\Booking\BillTable;
use Booking\Table\ReservationTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\Mvc\Controller\AbstractActionController;

class BillingController extends AbstractActionController
{

    public function indexAction()
    {
        $this->authorize('admin.user');

        $serviceManager = @$this->getServiceLocator();
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $reservationManager = $serviceManager->get('Booking\Manager\ReservationManager');
        $userManager = $serviceManager->get('User\Manager\UserManager');

        $users = array();

        $name = $this->params()->fromQuery('bbsf-name');
        $sum = $this->params()->fromQuery('bbsf-sum');
        $dateStart = $this->params()->fromQuery('bbsf-date-start');
        $dateEnd = $this->params()->fromQuery('bbsf-date-end');
        $type = $this->params()->fromQuery('bbsf-type');
        $billingStatus = $this->params()->fromQuery('bbsf-billing-status');

        $search = array(
            'user' => $name,
            'sum' => $sum,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'type' => $type,
            'billingStatus' => $billingStatus,
        );

        $bookings = array();
        $reservations = array();

        if ($dateStart) {
            $dateStart = new \DateTime($dateStart);
        }        

        if ($dateEnd) {
            $dateEnd = new \DateTime($dateEnd);
            $dateEnd = $dateEnd->modify('+1 day');
        }

        if ($name || $sum || $dateStart || $dateEnd || $type || $billingStatus) {
            $filters = $this->backendBillingDetermineFilters($search);

            try {
                $limit = 1000;

                if ($dateStart && $dateEnd) {
                    $reservations = $reservationManager->getInRange($dateStart, $dateEnd, $limit);
                    $bookings = $bookingManager->getByReservations($reservations, $filters['filters']);
                } else {
                    if ($filters['filters']) {
                        $reservations = $reservationManager->getAll();
                        $bookings = $bookingManager->getByReservations($reservations, $filters['filters']);
                    } else {
                        $reservations = $reservationManager->getAll();
                        $bookings = $bookingManager->getByReservations($reservations);      
                    }
                }
                $bookingBillManager = $serviceManager->get('Booking\Manager\Booking\BillManager');
                $bookingBillManager->getByBookings($bookings);
                $bookings = $this->complexFilterBookings($bookings, $filters);
                    if ($reservations) {
                    $reservations = $this->complexFilterReservations($bookings, $reservations);
                }

                $userManager->getByBookings($bookings);
            } catch (\RuntimeException $e) {
                $bookings = array();
                $reservations = array();
            }
        } 
        if (!$dateStart && !$dateEnd) {
            $dateEnd = new \DateTime();
            $dateStart = (clone $dateEnd)->modify('-1 month');
        }

        return array(
            'name' => $name,
            'sum' => $sum,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'type' => $type,
            'billingStatus' => $billingStatus,
            'bookings' => $bookings,
            'reservations' => $reservations,
            'search' => $search,
        );
    }

    protected function complexFilterBookings($bookings, $filters)
    {
        $serviceManager = @$this->getServiceLocator();

        foreach ($filters['filterParts'] as $filterPart) {

            // Filter for billing total
            if ($filterPart[0] == 'billing_total') {

                $bookings = array_filter($bookings, function(Booking $booking) use ($filterPart) {
                    $sum = $booking->getExtra('bills_total');
                    if (!$sum) $sum = 0;
                    switch ($filterPart[1]) {
                        case '=':
                            return ($sum == (int) $filterPart[2]);
                        case '>':
                            return ($sum > (int) $filterPart[2]);
                        case '<':
                            return ($sum < (int) $filterPart[2]);
                        default:
                            return false;
                    }
                });
            }

        }

        return $bookings;
    }    

    protected function complexFilterReservations($bookings, $reservations) {
        $bookingIds = array();
        foreach($bookings as $booking) {
            $bookingIds[] = $booking->need('bid');
        }
        $reservations = array_filter($reservations, function(Reservation $reservation) use ($bookingIds) {
            return (in_array($reservation->need('bid'), $bookingIds));
        });        
        return $reservations;
    }

    public function statsAction()
    {
        $this->authorize('admin.user');

        $db = @$this->getServiceLocator()->get('Laminas\Db\Adapter\Adapter');

        $stats = $db->query(sprintf(
            'SELECT status, status_billing, sum(price) AS sum FROM %s A, %s B ' . 
            'WHERE A.bid = B.bid ' .
            'GROUP BY status, status_billing', 
            BookingTable::NAME, BillTable::NAME),
            Adapter::QUERY_MODE_EXECUTE)->toArray();

        return array(
            'stats' => $stats,
        );
    }

}
