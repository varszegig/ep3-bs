<?php

namespace Backend\Controller;

use Event\Entity\Event;
use Event\Table\EventTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\Mvc\Controller\AbstractActionController;

class EventController extends AbstractActionController
{

    public function indexAction()
    {
        $this->authorize('admin.event');

        $serviceManager = @$this->getServiceLocator();
        $eventManager = $serviceManager->get('Event\Manager\EventManager');

        $dateStartParam = $this->params()->fromQuery('date-start');
        $dateEndParam = $this->params()->fromQuery('date-end');

        $dateStart = null;
        $dateEnd = null;

        $events = array();
        
        if ($dateStartParam && $dateEndParam) {
            try {
                $dateStart = new \DateTime($dateStartParam);
                $dateStart->setTime(0, 0, 0);

                $dateEnd = new \DateTime($dateEndParam);
                $dateEnd->setTime(23, 59, 59);
            } catch (\Exception $e) {
                throw new \RuntimeException('Invalid date');
            }

            $events = $eventManager->getInRange($dateStart, $dateEnd);

            $eventManager->getSecondsPerDay($events);
        }

        $this->redirectBack()->setOrigin('backend/event');

        return array(
            'dateStart' => $dateStart,
            'dateEnd' => $dateEnd,
            'events' => $events,
        );
    }

    public function editAction()
    {
        $this->authorize('admin.event');

        $serviceManager = @$this->getServiceLocator();
        $eventManager = $serviceManager->get('Event\Manager\EventManager');
        $formElementManager = $serviceManager->get('FormElementManager');
        $squareManager = $serviceManager->get('Square\Manager\SquareManager');
        $minInterval = $squareManager->getMinTimeBlock();
        $minTime = $squareManager->getMinStartTime();
        $maxTime = $squareManager->getMaxEndTime() - 3600;        

        $eid = $this->params()->fromRoute('eid');

        if ($eid) {
            $event = $eventManager->get($eid);

            $eventManager->getSecondsPerDay($event);
        } else {
            $event = null;
        }

        $editForm = $formElementManager->get('Backend\Form\Event\EditForm');

        if ($this->getRequest()->isPost()) {
            $editForm->setData($this->params()->fromPost());

            if ($editForm->isValid()) {
                $data = $editForm->getData();
                
                $repeat = $data['ef-repeat'];

                $dateStart = new \DateTime($data['ef-date-start']);
                $dateEnd = new \DateTime($data['ef-date-end']);

                $walkingDate = clone $dateStart;
                $walkingDate->setTime(0, 0, 0);
                if ($repeat > 0) {
                    $walkingDateLimit = new \DateTime($data['ef-repeat-end']);
                } else {
                    $walkingDateLimit = clone $dateStart;
                }
                $walkingDateLimit->setTime(0, 0, 0);

                if (! $event) {
                    $event = new Event();
                } else {
                    $walkingDateLimit = clone $dateStart;
                }

                while ($walkingDate <= $walkingDateLimit) {



                    $locale = $this->config('i18n.locale');

                    $event->setMeta('name', $data['ef-name'], $locale);
                    $event->setMeta('description', $data['ef-description'], $locale);
                    if ($repeat > 0) {
                        $event->setMeta('repeat', $repeat, $locale);
                        $event->setMeta('repeat_end', $walkingDateLimit->format('Y-m-d'), $locale);
                    }

                    $dateStart = new \DateTime($data['ef-date-start']);

                    $timeStartParts = explode(':', $data['ef-time-start']);

                    $dateStart->setTime($timeStartParts[0], $timeStartParts[1], 0);

                    $dateEnd = new \DateTime($data['ef-date-end']);

                    $timeEndParts = explode(':', $data['ef-time-end']);

                    $dateEnd->setTime($timeEndParts[0], $timeEndParts[1], 0);

                    $event->set('datetime_start', $dateStart->format('Y-m-d H:i:s'));
                    $event->set('datetime_end', $dateEnd->format('Y-m-d H:i:s'));

                    $sid = $data['ef-sid'];

                    if ($sid == 'null') {
                        $sid = null;
                    }

                    $event->set('sid', $sid);

                    $capacity = $data['ef-capacity'];

                    if (! $capacity) {
                        $capacity = null;
                    }

                    $event->set('capacity', $capacity);

                    $event->setMeta('notes', $data['ef-notes']);
                    
                    $eventManager->save($event);

                    if ($repeat > 0) {
                            $data['ef-date-start'] = $dateStart->modify('+' . $repeat. ' day')->format('Y-m-d H:i:s');
                            $data['ef-date-end'] = $dateEnd->modify('+' . $repeat. ' day')->format('Y-m-d H:i:s');                        
            
                            $walkingDate->modify('+' . $repeat. ' day');
                            $event = new Event();
                    } else {
                        $walkingDate->modify('+' . 1 . ' day');
                    }

                }

                $this->flashMessenger()->addSuccessMessage('Event has been saved');

                return $this->redirectBack()->toOrigin();
            }
        } else {
            if ($event) {
                $editForm->setData(array(
                    'ef-name' => $event->getMeta('name'),
                    'ef-description' => $event->getMeta('description'),
                    'ef-date-start' => $this->dateFormat($event->needExtra('datetime_start'), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                    'ef-time-start' => $event->needExtra('datetime_start')->format('H:i'),
                    'ef-date-end' => $this->dateFormat($event->needExtra('datetime_end'), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                    'ef-time-end' => $event->needExtra('datetime_end')->format('H:i'),
                    'ef-sid' =>  $event->get('sid'),
                    'ef-capacity' =>  $event->get('capacity', 0),
                    'ef-repeat' => $event->getMeta('repeat'),
                    'ef-repeat-end' => $this->dateFormat(new \DateTime($event->getMeta('repeat_end')), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                    'ef-notes' =>  $event->getMeta('notes'),
                ));
            } else {
                $params = $this->backendBookingDetermineParams();
                $query = $params['query'];
                $editForm->setData(array(
                    'ef-date-start' => $this->dateFormat(new \DateTime($query['ds']), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                    'ef-time-start' => $params['dateTimeStart']->format('H:i'),
                    'ef-date-end' => $this->dateFormat(new \DateTime($query['ds']), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                    'ef-time-end' => $params['dateTimeEnd']->format('H:i'),
                    'ef-sid' =>  $params['square']->get('sid'),
                    'ef-capacity' => 0,
                    'ef-repeat' => 0,
                    'ef-repeat-end' => $this->dateFormat(new \DateTime(), \IntlDateFormatter::MEDIUM, null, null, $this->t('dd.MM.yyyy')),
                ));
            }
        }

        return array(
            'event' => $event,
            'editForm' => $editForm,
            'minInterval' => $minInterval,
            'minTime' => $minTime,
            'maxTime' => $maxTime,            
        );
    }

    public function editChoiceAction()
    {
        $this->authorize('admin.event');

        $params = $this->backendBookingDetermineParams();

        $serviceManager = @$this->getServiceLocator();
        $eventManager = $serviceManager->get('Event\Manager\EventManager');

        $events = $eventManager->getInRange($params['dateTimeStart'], $params['dateTimeEnd']);

        $eventManager->getSecondsPerDay($events);

        return $this->ajaxViewModel(array(
            'events' => $events,
        ));
    }

    public function deleteAction()
    {
        $this->authorize('admin.event');

        $serviceManager = @$this->getServiceLocator();
        $eventManager = $serviceManager->get('Event\Manager\EventManager');

        $eid = $this->params()->fromRoute('eid');

        $event = $eventManager->get($eid);

        if ($this->params()->fromQuery('confirmed') == 'true') {

            $eventManager->delete($event);

            $this->flashMessenger()->addSuccessMessage('Event has been deleted');

            return $this->redirectBack()->toOrigin();
        }

        return array(
            'event' => $event,
        );
    }

    public function statsAction()
    {
        $this->authorize('admin.event');

        $db = @$this->getServiceLocator()->get('Laminas\Db\Adapter\Adapter');

        $stats = $db->query(sprintf('SELECT status, COUNT(status) AS count FROM %s GROUP BY status', EventTable::NAME),
            Adapter::QUERY_MODE_EXECUTE)->toArray();

        return array(
            'stats' => $stats,
        );
    }

}
