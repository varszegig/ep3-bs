<?php

namespace Backend\Controller;

use Booking\Entity\Booking;
use Booking\Entity\Reservation;
use Booking\Table\BookingTable;
use Booking\Table\ReservationTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\Mvc\Controller\AbstractActionController;

class BookingController extends AbstractActionController
{

    public function indexAction()
    {
        $this->authorize('admin.booking');

        $serviceManager = @$this->getServiceLocator();
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $reservationManager = $serviceManager->get('Booking\Manager\ReservationManager');
        $userManager = $serviceManager->get('User\Manager\UserManager');

        $bookings = array();
        $reservations = array();

        $dateStart = $this->params()->fromQuery('date-start');
        $dateEnd = $this->params()->fromQuery('date-end');
        $userSearch = $this->params()->fromQuery('user');
        $squareList = $this->params()->fromQuery('bs-square');
        $bookingStatus = $this->params()->fromQuery('bs-status');
        $billingStatus = $this->params()->fromQuery('bs-billing-status');
        $visibility = $this->params()->fromQuery('bs-visibility');
        $billingTotalOperator = $this->params()->fromQuery('bs-billing-total-operator');
        $billingTotal = $this->params()->fromQuery('bs-billing-total');
        $quantityOperator = $this->params()->fromQuery('bs-quantity-operator');
        $quantity = $this->params()->fromQuery('bs-quantity');
        $dateCreatedOperator = $this->params()->fromQuery('date-created-operator');
        $dateCreated = $this->params()->fromQuery('date-created');
        $notes = $this->params()->fromQuery('bs-notes');
        $bsDateStart = $dateStart;
        $bsDateEnd = $dateEnd;

        $search = array(
            'user' => $userSearch,
            'square' => $squareList,
            'status' => $bookingStatus,
            'billingStatus' => $billingStatus,
            'visibility' => $visibility,
            'billingTotalOperator' => $billingTotalOperator,
            'billingTotal' => $billingTotal,
            'quantityOperator' => $quantityOperator,
            'quantity' => $quantity,
            'dateCreatedOperator' => $dateCreatedOperator,
            'dateCreated' => $dateCreated,
            'notes' => $notes,
         );

        if ($dateStart) {
            $dateStart = new \DateTime($dateStart);
        }

        if ($dateEnd) {
            $dateEnd = new \DateTime($dateEnd);
        }

        if ($dateCreated) {
            $dateCreated = new \DateTime($dateCreated);
        }

        if (($dateStart && $dateEnd) || $search) {
            $filters = $this->backendBookingDetermineFilters($search);
            try {
                $limit = 1000;

                if ($dateStart && $dateEnd) {
                    $reservations = $reservationManager->getInRange($dateStart, $dateEnd, $limit);
                    $bookings = $bookingManager->getByReservations($reservations, $filters['filters']);
                } else {
                    if ($filters['filters']) {
                        $bookings = $bookingManager->getBy($filters['filters'], null, $limit);
                        $reservations = $reservationManager->getByBookings($bookings);
                    }
                }

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

        $search = array(
            'date-start' => $bsDateStart,
            'date-end' => $bsDateEnd,
            'user' => $userSearch,
            'bs-square' => $squareList,
            'bs-status' => $bookingStatus,
            'bs-billing-status' => $billingStatus,
            'bs-visibility' => $visibility,
            'bs-billing-total-operator' => $billingTotalOperator,
            'bs-billing-total' => $billingTotal,
            'bs-quantity-operator' => $quantityOperator,
            'bs-quantity' => $quantity,
            'bs-date-created-operator' => $dateCreatedOperator,
            'date-created' => $dateCreated,
            'bs-notes' => $notes,
         );

        return array(
            'bookings' => $bookings,
            'reservations' => $reservations,
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'user' => $userSearch,
            'bs-square' => $squareList,
            'bs-status' => $bookingStatus,
            'bs-billing-status' => $billingStatus,
            'bs-visibility' => $visibility,
            'bs-billing-total-operator' => $billingTotalOperator,
            'billingTotal' => $billingTotal,
            'quantity-operator' => $quantityOperator,
            'quantity' => $quantity,
            'date-created-operator' => $dateCreatedOperator,
            'dateCreated' => $dateCreated,
            'notes' => $notes,
            'search' => $search,
        );
    }

    protected function complexFilterBookings($bookings, $filters)
    {
        $serviceManager = @$this->getServiceLocator();

        foreach ($filters['filterParts'] as $filterPart) {

            // Filter for billing total
            if ($filterPart[0] == 'billing_total') {
                $bookingBillManager = $serviceManager->get('Booking\Manager\Booking\BillManager');
                $bookingBillManager->getByBookings($bookings);

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
            if ($filterPart[0] == 'notes') {
                $bookings = array_filter($bookings, function(Booking $booking) use ($filterPart) {
                    $notes = strtolower($booking->getMeta('notes'));
                    if (! $notes) return false; 
                    if (strpos($notes, strtolower($filterPart[2])) !== false) return true;
                    else return false;
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

    public function editAction()
    {
        $serviceManager = @$this->getServiceLocator();
        $squareManager = $serviceManager->get('Square\Manager\SquareManager');
        $minInterval = $squareManager->getMinTimeBlock();
        $minTime = $squareManager->getMinStartTime();
        $maxTime = $squareManager->getMaxEndTime() - 3600;

        $sessionUser = $this->authorize('admin.booking, calendar.see-data');

        $params = $this->backendBookingDetermineParams(true);

        $reservation = $booking = null;

        if (! ($this->getRequest()->isPost() || $this->params()->fromQuery('force') == 'new')) {
            switch (count($params['reservations'])) {
                case 0:
                    break;
                case 1:
                    $reservation = current($params['reservations']);
                    $booking = $reservation->getExtra('booking');

                    if ($booking->get('status') == 'subscription') {
                        if (! $params['editMode']) {
                            return $this->forward()->dispatch('Backend\Controller\Booking', ['action' => 'editMode', 'params' => $params]);
                        }
                    }
                    break;
                default:
                    return $this->forward()->dispatch('Backend\Controller\Booking', ['action' => 'editChoice', 'params' => $params]);
            }
        }

        $serviceManager = @$this->getServiceLocator();
        $formElementManager = $serviceManager->get('FormElementManager');

        $editForm = $formElementManager->get('Backend\Form\Booking\EditForm');

        if ($this->getRequest()->isPost()) {
            $editForm->setData($this->params()->fromPost());

            if ($editForm->isValid()) {
                $d = $editForm->getData();

                $conflictedReservation = $this->getConflicts($d);
                if ( $conflictedReservation == null) {
                    /* Process form (note, that reservation and booking are not available here) */

                    if ($d['bf-rid']) {

                        /* Update booking/reservation */

                        $savedBooking = $this->backendBookingUpdate($d['bf-rid'], $d['bf-user'], $d['bf-time-start'], $d['bf-time-end'], $d['bf-date-start'],
                            $d['bf-sid'], $d['bf-status-billing'], $d['bf-quantity'], $d['bf-notes'], $params['editMode']);

                    } else {

                        /* Create booking/reservation */
                        try {
                            $savedBooking = $this->backendBookingCreate($d['bf-user'], $d['bf-time-start'], $d['bf-time-end'], $d['bf-date-start'], $d['bf-date-end'],
                                $d['bf-repeat'], $d['bf-payment'], $d['bf-sid'], $d['bf-status-billing'], $d['bf-quantity'], $d['bf-notes'], $sessionUser->get('alias'));
                        } catch (\Exception $e) {
                            $this->flashMessenger()->addErrorMessage($e->getMessage());
                            return $this->redirect()->toRoute('frontend');
                        }
                    }

                    $this->flashMessenger()->addSuccessMessage('Booking has been saved');
                } else {
                    $conflictedDate = $this->dateFormat($conflictedReservation->get('date'), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy'));
                    $this->flashMessenger()->addErrorMessage(sprintf($this->translate('Booking conflicts with other bookings: %s'), $conflictedDate));
                }

                if ($this->params()->fromPost('bf-edit-user')) {
                    return $this->redirect()->toRoute('backend/user/edit', ['uid' => $savedBooking->get('uid')]);
                } else if ($this->params()->fromPost('bf-edit-bills')) {
                    return $this->redirect()->toRoute('backend/booking/bills', ['bid' => $savedBooking->get('bid')]);
                } else {
                    return $this->redirect()->toRoute('frontend');
                }
            }
        } else {
            if ($booking) {
                $user = $booking->needExtra('user');
                $editForm->setData(array(
                    'bf-rid' => $reservation->get('rid'),
                    'bf-user' => $user->need('alias') . ' (' . $user->need('uid') . ')',
                    'bf-sid' => $booking->get('sid'),
                    'bf-status-billing' => $booking->get('status_billing'),
                    'bf-quantity' => $booking->get('quantity'),
                    'bf-notes' => $booking->getMeta('notes'),
                ));

                if ($booking->get('status') == 'subscription' && $params['editMode'] == 'booking') {
                    $editForm->setData(array(
                        'bf-time-start' => substr($booking->getMeta('time_start', $reservation->get('time_start')), 0, 5),
                        'bf-time-end' => substr($booking->getMeta('time_end', $reservation->get('time_end')), 0, 5),
                        'bf-date-start' => $this->dateFormat($booking->getMeta('date_start', $reservation->get('date')), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                        'bf-date-end' => $this->dateFormat($booking->getMeta('date_end', $reservation->get('date')), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                        'bf-repeat' => $booking->getMeta('repeat'),
                        'bf-payment' => $booking->getMeta('payment'),
                    ));
                } else {
                    $editForm->setData(array(
                        'bf-time-start' => substr($reservation->get('time_start'), 0, 5),
                        'bf-time-end' => substr($reservation->get('time_end'), 0, 5),
                        'bf-date-start' => $this->dateFormat($reservation->get('date'), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                        'bf-date-end' => $this->dateFormat($booking->getMeta('date_end', $reservation->get('date')), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                        'bf-repeat' => $booking->getMeta('repeat'),
                        'bf-payment' => $booking->getMeta('payment'),
                    ));
                }
            } else {
                $timeEnd = $params['dateTimeEnd']->format('H:i');

                if ($timeEnd == '00:00') {
                    $timeEnd = '24:00';
                }

                $squarePricingManager = $serviceManager->get('Square\Manager\SquarePricingManager');
                $endDate = $squarePricingManager->getMaxEndDate($params['dateTimeEnd']);

                $editForm->setData(array(
                    'bf-sid' => $params['square']->get('sid'),
                    'bf-date-start' => $this->dateFormat($params['dateTimeStart'], \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                    'bf-date-end' => $this->dateFormat($endDate, \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')), 
                    'bf-time-start' => $params['dateTimeStart']->format('H:i'),
                    'bf-time-end' => $timeEnd,
                ));
            }
        }

        if ($booking && $booking->getMeta('player-names')) {
            $editForm->get('bf-quantity')->setLabel(sprintf('%s (<a href="%s">%s</a>)',
                $this->translate('Number of players'),
                $this->url()->fromRoute('backend/booking/players', ['bid' => $booking->need('bid')]),
                $this->translate('Who?')));
            $editForm->get('bf-quantity')->setLabelOption('disable_html_escape', true);

            $playerNameNotes = '';
            $playerNames = $booking->getMeta('player-names');

            if ($playerNames) {
                $playerNamesUnserialized = @unserialize($booking->getMeta('player-names'));

                if ($playerNamesUnserialized && is_array($playerNamesUnserialized)) {
                    foreach ($playerNamesUnserialized as $i => $playerName) {
                        $playerNameNotes .= sprintf('<div>%s. %s</div>',
                            $i + 1, $playerName['value']);
                    }
                }
            }

            $editForm->get('bf-quantity')->setOption('notes', $playerNameNotes);
        }

        return $this->ajaxViewModel(array_merge($params, array(
            'editForm' => $editForm,
            'booking' => $booking,
            'reservation' => $reservation,
            'sessionUser' => $sessionUser,
            'minInterval' => $minInterval,
            'minTime' => $minTime,
            'maxTime' => $maxTime,
        )));
    }

    public function editChoiceAction()
    {
        $params = $this->getEvent()->getRouteMatch()->getParam('params');

        return $this->ajaxViewModel($params);
    }

    public function editModeAction()
    {
        $params = $this->getEvent()->getRouteMatch()->getParam('params');

        return $this->ajaxViewModel($params);
    }

    public function editRangeAction()
    {
        $this->authorize('admin.booking, calendar.create-subscription-bookings + calendar.cancel-subscription-bookings');

        $serviceManager = @$this->getServiceLocator();
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $reservationManager = $serviceManager->get('Booking\Manager\ReservationManager');
        $formElementManager = $serviceManager->get('FormElementManager');

        $serviceManager = @$this->getServiceLocator();
        $squareManager = $serviceManager->get('Square\Manager\SquareManager');
        $minInterval = $squareManager->getMinTimeBlock();
        $minTime = $squareManager->getMinStartTime();
        $maxTime = $squareManager->getMaxEndTime() - 3600;

        $params = $this->backendBookingDetermineParams(true);
        $query = $params['query'];
        $query['em'] = $params['editMode'];

        $bid = $this->params()->fromRoute('bid');

        $booking = $bookingManager->get($bid);

        if ($booking->get('status') != 'subscription') {
            throw new \RuntimeException('Time and date range can only be edited on subscription bookings');
        }

        $editTimeRangeForm = $formElementManager->get('Backend\Form\Booking\Range\EditTimeRangeForm');
        $editDateRangeForm = $formElementManager->get('Backend\Form\Booking\Range\EditDateRangeForm');

        if ($this->getRequest()->isPost()) {
            $db = $serviceManager->get('Laminas\Db\Adapter\Adapter');

            $mode = $this->params()->fromQuery('mode');

            if ($mode == 'time') {
                $editTimeRangeForm->setData($this->params()->fromPost());

                if ($editTimeRangeForm->isValid()) {
                    $data = $editTimeRangeForm->getData();

                    $data['bf-sid'] = $booking->need('sid');
                    $data['bf-bid'] = $bid;
                    $data['bf-date-start'] = $booking->needMeta('date_start');
                    $data['bf-date-end'] = $booking->needMeta('date_end');
                    $conflictedReservation = $this->getConflicts($data);
                    if ( $conflictedReservation == null) {
                        $bookingsChain = $bookingManager->getChain($bid);
                        foreach ($bookingsChain as $booking) {
                            try {
                            $bid = $booking->get('bid');
                            $res = $db->query(
                                sprintf('UPDATE %s SET time_start = "%s", time_end = "%s" WHERE bid = %s AND time_start = "%s" AND time_end = "%s"',
                                    ReservationTable::NAME,
                                    $data['bf-time-start'], $data['bf-time-end'], $bid, $booking->needMeta('time_start'), $booking->needMeta('time_end')),
                                Adapter::QUERY_MODE_EXECUTE);
                            } catch (\Exception $e) {}

                            if ($res->getAffectedRows() > 0) {
                                $booking->setMeta('time_start', $data['bf-time-start']);
                                $booking->setMeta('time_end', $data['bf-time-end']);

                                $bookingManager->save($booking);
                            }
                        }
                        $this->flashMessenger()->addSuccessMessage('Booking has been saved');
                    } else {
                        $conflictedDate = $this->dateFormat($conflictedReservation->get('date'), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy'));
                        $this->flashMessenger()->addErrorMessage(sprintf($this->translate('Booking conflicts with other bookings: %s'), $conflictedDate));
                    }

                    return $this->redirect()->toRoute('backend/booking/edit', [], ['query' => $query]);
                }
            } else if ($mode == 'date') {
                $editDateRangeForm->setData($this->params()->fromPost());

                if ($editDateRangeForm->isValid()) {
                    $data = $editDateRangeForm->getData();

                    $data['bf-sid'] = $booking->need('sid');
                    $data['bf-bid'] = $bid;
                    $data['bf-time-start'] = $booking->needMeta('time_start');
                    $data['bf-time-end'] = $booking->needMeta('time_end');
                    $conflictedReservation = $this->getConflicts($data);
                    if ( $conflictedReservation == null) {
                        $dateStart = new \DateTime($data['bf-date-start']);
                        $dateEnd = new \DateTime($data['bf-date-end']);
                        $repeat = $data['bf-repeat'];
                        $payment = $data['bf-payment'];

                        if ($booking->getMeta('payment') == 1)
                        {
                            $bookingsChain = $bookingManager->getChain($bid);
                            foreach ($bookingsChain as $oldBooking) {
                                
                            $bid = $oldBooking->get('bid');
                            $res = $db->query(
                                sprintf('DELETE FROM %s WHERE bid = %s',
                                    BookingTable::NAME, $bid),
                                Adapter::QUERY_MODE_EXECUTE);                            
                            $res = $db->query(
                                sprintf('DELETE FROM %s WHERE bid = %s',
                                    ReservationTable::NAME, $bid),
                                Adapter::QUERY_MODE_EXECUTE);                            
                            }
                            $sessionUser = $this->authorize('admin.booking, calendar.see-data');
                            $savedBooking = $this->backendBookingCreate('(' . $booking->get('uid') . ')', $booking->needMeta('time_start'), $booking->needMeta('time_end'), $data['bf-date-start'], $data['bf-date-end'],
                            $data['bf-repeat'], $data['bf-payment'], $booking->need('sid'), $booking->need('status_billing'), $booking->need('quantity'), $booking->getMeta('notes'), $sessionUser->get('alias'));
                        } else {  
                            $res = $db->query(
                                sprintf('DELETE FROM %s WHERE bid = %s',
                                    ReservationTable::NAME, $bid),
                                Adapter::QUERY_MODE_EXECUTE);
                            if ($res->getAffectedRows() > 0 && $payment == 0) {
                    
                                $reservationManager->createByRange($booking, $dateStart, $dateEnd,
                                    $booking->needMeta('time_start'), $booking->needMeta('time_end'), $repeat);

                                $booking->setMeta('date_start', $dateStart->format('Y-m-d'));
                                $booking->setMeta('date_end', $dateEnd->format('Y-m-d'));                            
                                $booking->setMeta('repeat', $repeat);
                                $booking->setMeta('payment', $payment);
                                $bookingManager->save($booking);
                            }     
                            if ($payment == 1)
                            {
                                $res = $db->query(
                                    sprintf('DELETE FROM %s WHERE bid = %s',
                                        BookingTable::NAME, $bid),
                                    Adapter::QUERY_MODE_EXECUTE);  
                                $sessionUser = $this->authorize('admin.booking, calendar.see-data');
                                $savedBooking = $this->backendBookingCreate('(' . $booking->get('uid') . ')', $booking->needMeta('time_start'), $booking->needMeta('time_end'), $data['bf-date-start'], $data['bf-date-end'],
                                $data['bf-repeat'], $data['bf-payment'], $booking->need('sid'), $booking->need('status_billing'), $booking->need('quantity'), $booking->getMeta('notes'), $sessionUser->get('alias'));
                            }
                        }


                        $this->flashMessenger()->addSuccessMessage('Booking has been saved');
                    } else {
                        $conflictedDate = $this->dateFormat($conflictedReservation->get('date'), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy'));
                        $this->flashMessenger()->addErrorMessage(sprintf($this->translate('Booking conflicts with other bookings: %s'), $conflictedDate));
                    }

                    return $this->redirect()->toRoute('frontend');
                }
            } else {
                throw new \RuntimeException('Invalid edit mode received');
            }
        } else {
            $editTimeRangeForm->setData(array(
                'bf-time-start' => substr($booking->needMeta('time_start'), 0, 5),
                'bf-time-end' => substr($booking->needMeta('time_end'), 0, 5),
            ));

            $editDateRangeForm->setData(array(
                'bf-date-start' => $this->dateFormat($booking->needMeta('date_start'), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                'bf-date-end' => $this->dateFormat($booking->needMeta('date_end'), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                'bf-repeat' => $booking->needMeta('repeat'),
                'bf-payment' => $booking->getMeta('payment'),
            ));
        }

        return $this->ajaxViewModel(array(
            'booking' => $booking,
            'editTimeRangeForm' => $editTimeRangeForm,
            'editDateRangeForm' => $editDateRangeForm,
            'minInterval' => $minInterval,
            'minTime' => $minTime,
            'maxTime' => $maxTime,
            'query' => $query
        ));
    }

    public function deleteAction()
    {
        $sessionUser = $this->authorize([
            'calendar.cancel-single-bookings', 'calendar.delete-single-bookings',
            'calendar.cancel-subscription-bookings', 'calendar.delete-subscription-bookings']);

        $serviceManager = @$this->getServiceLocator();
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $reservationManager = $serviceManager->get('Booking\Manager\ReservationManager');

        $rid = $this->params()->fromRoute('rid');
        $editMode = $this->params()->fromQuery('edit-mode');

        $reservation = $reservationManager->get($rid);
        $booking = $bookingManager->get($reservation->get('bid'));

        switch ($booking->get('status')) {
            case 'single':
                $this->authorize(['calendar.cancel-single-bookings', 'calendar.delete-single-bookings']);
                break;
            case 'subscription':
                $this->authorize(['calendar.cancel-subscription-bookings', 'calendar.delete-subscription-bookings']);
                break;
        }

        if ($this->params()->fromQuery('confirmed') == 'true') {

            if ($editMode == 'reservation') {
                $this->authorize(['calendar.delete-single-bookings', 'calendar.delete-subscription-bookings']);

                $reservationManager->delete($reservation);

                $this->flashMessenger()->addSuccessMessage('Reservation has been deleted');
            } else {

                if ($this->params()->fromQuery('cancel') == 'true') {
                    $this->authorize(['calendar.cancel-single-bookings', 'calendar.cancel-subscription-bookings']);

                    $bookingsChain = $bookingManager->getChain($booking->get('bid'));
                    foreach ($bookingsChain as $booking) {
                        $booking->set('status', 'cancelled');
                        $booking->setMeta('cancellor', $sessionUser->get('alias'));
                        $booking->setMeta('cancelled', date('Y-m-d H:i:s'));
                        $bookingManager->save($booking);
                    }

                    $this->flashMessenger()->addSuccessMessage('Booking has been cancelled');
                } else {
                    $this->authorize(['calendar.delete-single-bookings', 'calendar.delete-subscription-bookings']);
                    $bookingsChain = $bookingManager->getChain($booking->get('bid'));
                    foreach ($bookingsChain as $booking) {
                        $bookingManager->delete($booking);
                    }

                    $this->flashMessenger()->addSuccessMessage('Booking has been deleted');
                }
            }

            return $this->redirect()->toRoute('frontend');
        }

        if ($editMode == 'reservation') {
            $template = 'backend/booking/delete.reservation.phtml';
        } else {
            $template = null;
        }

        return $this->ajaxViewModel(array(
            'rid' => $rid,
        ), null, $template);
    }

    public function statsAction()
    {
        $this->authorize('admin.booking');

        $db = @$this->getServiceLocator()->get('Laminas\Db\Adapter\Adapter');

        $stats = $db->query(sprintf('SELECT status, status_billing, COUNT(status) AS count FROM %s GROUP BY status, status_billing', BookingTable::NAME),
            Adapter::QUERY_MODE_EXECUTE)->toArray();

        return array(
            'stats' => $stats,
        );
    }

    public function billsAction()
    {
        $this->authorize('admin.booking');

        $bid = $this->params()->fromRoute('bid');

        $serviceManager = @$this->getServiceLocator();

        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $bookingBillManager = $serviceManager->get('Booking\Manager\Booking\BillManager');
        $bookingStatusService = $serviceManager->get('Booking\Service\BookingStatusService');
        $userManager = $serviceManager->get('User\Manager\UserManager');

        $booking = $bookingManager->get($bid);
        $bills = $bookingBillManager->getBy(array('bid' => $bid), 'bbid ASC');
        $user = $userManager->get($booking->need('uid'));

        $params = $this->backendBookingDetermineParams(true);
        $query = $params['query'];
        $editMode = $params['editMode'];
        $query['em'] = $editMode;

        $startingFrom = $query['starting-from'];

        if ($this->getRequest()->isGet()) {
            $create = $this->params()->fromQuery('create');

            if ($create) {
                $reservationManager = $serviceManager->get('Booking\Manager\ReservationManager');
                $squareManager = $serviceManager->get('Square\Manager\SquareManager');
                $squarePricingManager = $serviceManager->get('Square\Manager\SquarePricingManager');

                $square = $squareManager->get($booking->get('sid'));
                $squareType = $this->option('subject.square.type');
                $squareName = $this->t($square->need('name'));

                $dateRangeHelper = $serviceManager->get('ViewHelperManager')->get('DateRange');

                $created = false;

                $priceSum = 0;

                foreach($bills as $bill) {
                    $quantity = $bill->get('quantity');
                    $priceSum += $bill->get('price');
                    $time = $bill->get('time');
                    $rate = $bill->get('rate');
                    $gross = $bill->get('gross');
                }

                switch ($create) {
                    case 'default-bill':
                        foreach ($reservationManager->getBy(['bid' => $bid]) as $reservation) {

                            $dateTimeStart = new \DateTime($reservation->get('date') . ' ' . $reservation->get('time_start'));
                            $dateTimeEnd = new \DateTime($reservation->get('date') . ' ' . $reservation->get('time_end'));

                            $pricing = $squarePricingManager->getFinalPricingInRange($dateTimeStart, $dateTimeEnd, $square, $booking->get('quantity'));

                            if ($pricing) {

                                $description = sprintf('%s %s, %s',
                                    $squareType, $squareName,
                                    $dateRangeHelper($dateTimeStart, $dateTimeEnd));

                                $bookingBillManager->save(new Booking\Bill(array(
                                    'bid' => $bid,
                                    'description' => $description,
                                    'quantity' => $booking->get('quantity'),
                                    'time' => $pricing['seconds'],
                                    'price' => $pricing['price'],
                                    'rate' => $pricing['rate'],
                                    'gross' => $pricing['gross'],
                                )));

                                $created = true;
                            }
                            break;
                        }
                        break;
                    case 'cash-payment':
                        $bookingBillManager->save(new Booking\Bill(array(
                            'bid' => $bid,
                            'description' => $this->t('Cash payment'),
                            'quantity' => $quantity,
                            'time' => $time,
                            'price' => - $priceSum,
                            'rate' => $rate,
                            'gross' => $gross,
                        )));

                        $created = true;
                        break;
                    case 'bank-transfer':
                        $bookingBillManager->save(new Booking\Bill(array(
                            'bid' => $bid,
                            'description' => $this->t('Bank transfer'),
                            'quantity' => $quantity,
                            'time' => $time,
                            'price' => - $priceSum,
                            'rate' => $rate,
                            'gross' => $gross,
                        )));

                        $created = true;
                        break;
                }

                if ($created) {
                    $this->flashMessenger()->addSuccessMessage('Booking-Bill position has been created');
                } else {
                    $this->flashMessenger()->addErrorMessage('No Booking-Bill position has been created');
                }

                return $this->redirect()->toRoute('backend/booking/bills', ['bid' => $bid]);
            }

            $delete = $this->params()->fromQuery('delete');

            if ($delete && is_numeric($delete) && isset($bills[$delete])) {
                $bookingBillManager->delete($delete);

                $this->flashMessenger()->addSuccessMessage('Booking-Bill position has been deleted');
                return $this->redirect()->toRoute('backend/booking/bills', ['bid' => $bid]);
            }
        }

        if ($this->getRequest()->isPost()) {

            /* Check and save billing status */

            $billingStatus = $this->params()->fromPost('ebf-status');

            $priceSum = 0;

            foreach($bills as $bill) {
                $quantity = $bill->get('quantity');
                $priceSum += $bill->get('price');
                $time = $bill->get('time');
                $rate = $bill->get('rate');
                $gross = $bill->get('gross');
            }       

            if ($billingStatus == 'pending' && $priceSum == 0) {
                $billingStatus = 'paid';
            }

            if ($billingStatus == 'paid' && $priceSum != 0) {
                $billingStatus = 'pending';
            }

            if ($bookingStatusService->checkStatus($billingStatus)) {
                $booking->set('status_billing', $billingStatus);
                $bookingManager->save($booking);
            } else {
                $this->flashMessenger()->addErrorMessage('Invalid billing status selected');
            }

            /* Check and save known (and new) bills */

            $bills[] = new Booking\Bill(['bid' => $bid]);

            foreach ($bills as $bill) {

                $bbid = $bill->get('bbid', 'new');

                $description = $this->params()->fromPost('ebf-' . $bbid . '-description');
                $description = trim(strip_tags($description));

                if ($description) {
                    $bill->set('description', $description);
                }

                $time = $this->params()->fromPost('ebf-' . $bbid . '-time');

                if ($time && is_numeric($time)) {
                    $bill->set('time', $time * 60);
                }

                $quantity = $this->params()->fromPost('ebf-' . $bbid . '-qua    ntity');

                if ($quantity && is_numeric($quantity)) {
                    $bill->set('quantity', $quantity);
                }

                $price = $this->params()->fromPost('ebf-' . $bbid . '-price');

                $price = filter_var($price, FILTER_SANITIZE_NUMBER_INT);
                if ($price && is_numeric($price)) {
                    $price = $price * 100;
                    $bill->set('price', $price);
                }

                $vatGross = $this->params()->fromPost('ebf-' . $bbid . '-vat-gross');
                $vatRate = $this->params()->fromPost('ebf-' . $bbid . '-vat-rate');

                if (is_numeric($vatGross) && is_numeric($vatRate)) {
                    $bill->set('gross', $vatGross);
                    $bill->set('rate', $vatRate);
                }

                if ($description && $price && is_numeric($vatRate) && is_numeric($vatGross)) {
                    $bookingBillManager->save($bill);
                }
            }

            $save = $this->params()->fromPost('ebf-save');
            $saveAndBack = $this->params()->fromPost('ebf-save-and-back');

            $this->flashMessenger()->addSuccessMessage('Booking-Bill has been saved');

            if (!$query) {
                if ($save) {
                    return $this->redirect()->toRoute('backend/booking/bills', ['bid' => $bid]);
                } else if ($saveAndBack) {
                    return $this->redirect()->toRoute('user/bookings/bills', ['bid' => $bid]);
                }
            } else {
                if ($save) {
                    return $this->redirect()->toRoute('backend/booking/bills', ['bid' => $bid], 
                        ['query' => $query]);
                } else if ($saveAndBack) {
                    if ($startingFrom == 'booking') {
                        $backUrl = 'backend/booking/edit';
                    } else if ($startingFrom == 'billing') {
                        $backUrl = 'backend/billing';
                    } else {
                        $backUrl = 'frontend';
                    }
                    return $this->redirect()->toRoute($backUrl, [], 
                        ['query' => $query]);
                }                
            }
        }

        return array(
            'booking' => $booking,
            'bookingStatusService' => $bookingStatusService,
            'bills' => $bills,
            'user' => $user,
            'query' => $query,
        );
    }

    public function playersAction()
    {
        $this->authorize('admin.booking, calendar.see-data');

        $bid = $this->params()->fromRoute('bid');

        $serviceManager = @$this->getServiceLocator();
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $userManager = $serviceManager->get('User\Manager\UserManager');

        $booking = $bookingManager->get($bid);
        $user = $userManager->get($booking->need('uid'));

        $playerNames = $booking->getMeta('player-names');

        if (! $playerNames) {
            throw new \RuntimeException('This booking has no additional player names');
        }

        $playerNames = @unserialize($playerNames);

        if (! $playerNames) {
            throw new \RuntimeException('Invalid player names data stored in database');
        }

        $players = array();

        foreach ($playerNames as $playerData) {
            $nameData = explode('-', $playerData['name']);
            $playerNumber = $nameData[count($nameData) - 1];

            if (! isset($players[$playerNumber])) {
                $players[$playerNumber] = array();
            }

            $playerDataKey = $nameData[count($nameData) - 2];
            $playerDataValue = $playerData['value'];

            if ($playerDataKey == 'email') {
                $respectiveUser = $userManager->getBy(['email' => $playerDataValue]);

                if ($respectiveUser) {
                    $players[$playerNumber]['user'] = current($respectiveUser);
                    $players[$playerNumber]['userMatch'] = $playerDataKey;
                }
            }

            if ($playerDataKey == 'phone') {
                $respectiveUser = $userManager->getByPhoneNumber($playerDataValue);

                if ($respectiveUser) {
                    $players[$playerNumber]['user'] = $respectiveUser;
                    $players[$playerNumber]['userMatch'] = $playerDataKey;
                }
            }

            $players[$playerNumber][$playerDataKey] = $playerDataValue;
        }

        return array(
            'booking' => $booking,
            'user' => $user,
            'players' => $players,
        );
    }

    private function getConflicts($data) {
        $serviceManager = @$this->getServiceLocator();
        $reservationManager = $serviceManager->get('Booking\Manager\ReservationManager');
        $bookingManager = $serviceManager->get('Booking\Manager\BookingManager');
        $squareManager = $serviceManager->get('Square\Manager\SquareManager');
        $square = $squareManager->get($data['bf-sid']);
        $repeat = $data['bf-repeat'];
        if ($data['bf-rid']) {
            $baseReservation = $reservationManager->get($data['bf-rid']);
            $baseBookingId = $baseReservation->get('bid');
         } else if ($data['bf-bid']) {
            $baseBookingId = $data['bf-bid'];
         } else $baseBookingId = -1;

        if ($square->get('capacity_heterogenic') == 0) {
            $dateStart = new \DateTime($data['bf-date-start']);

            if ($repeat > 0) $dateEnd = $data['bf-date-end'];
            else $dateEnd = $data['bf-date-start'];
            $dateEnd = new \DateTime($dateEnd);
            $reservations = $reservationManager->getByRange($dateStart, $dateEnd, 
                $data['bf-time-start'], $data['bf-time-end']);
            $bookings = $bookingManager->getByReservations($reservations);
            if ($reservations) {
                $reservationsOnTheSameDay = array();
                foreach ($reservations as $reservation) {
                    $date1 = new \DateTime($data['bf-date-start']);
                    $date2 = new \DateTime($reservation->get('date'));
                    $difference = $date1->diff($date2);
                    $days = $difference->days;
                    if ($repeat == 0 || $days % $repeat == 0) {
                        $booking = $reservation->getExtra('booking');
                        $bookingsChain = $bookingManager->getChain($booking->get('bid'));
                        $isTheSame = false;
                        foreach ($bookingsChain as $bookingChain) {
                            if ($bookingChain->get('bid') == $baseBookingId) {
                                $isTheSame = true;
                                break;
                            }
                        }
                        if ($booking->get('sid') == $data['bf-sid'] && $booking->get('status') != 'cancelled' && ! $isTheSame) {
                            return $reservation;
                        }
                    }
                }
            }
            return null;
        } else return null;
    }

}
