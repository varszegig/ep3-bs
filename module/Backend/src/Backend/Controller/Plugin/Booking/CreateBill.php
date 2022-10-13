<?php

namespace Backend\Controller\Plugin\Booking;

use Booking\Entity\Booking;
use Base\Manager\OptionManager;
use Square\Entity\Square;
use Square\Manager\SquarePricingManager;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Booking\Entity\Booking\Bill;
use Booking\Manager\Booking\BillManager;
use User\Manager\UserManager;

class CreateBill extends AbstractPlugin
{

    protected $optionManager;
    protected $viewHelperManager;
    protected $squarePricingManager;
    protected $billManager;
    protected $userManager;

    public function __construct(
        OptionManager $optionManager, 
        ServiceLocatorInterface $viewHelperManager,
        SquarePricingManager $squarePricingManager,
        BillManager $billManager,
        UserManager $userManager
        )
    {
        $this->optionManager = $optionManager;
        $this->viewHelperManager = $viewHelperManager;
        $this->squarePricingManager = $squarePricingManager;
        $this->billManager = $billManager;
        $this->userManager = $userManager;
    }

    public function __invoke($booking, $dateStart, $dateEnd, $timeStart, $timeEnd, $square, $quantity, $repeat = 1)
    {
        $controller = $this->getController();
        $bills = array();
        $user = $this->userManager->get($booking->need('uid'));

        $startTimeParts = explode(':', $timeStart);

        $endTimeParts = explode(':', $timeEnd);
        $walkingDate = clone $dateStart;
        $walkingDate->setTime(0, 0, 0);
        if ($dateEnd) {
            $type = 2;
            $walkingDateLimit = clone $dateEnd;
        } else {
            $type = 1;
            if ($user->getMeta('club-card')) $type = 3;
            $walkingDateLimit = clone $dateStart;
        }
        
        $walkingDateLimit->setTime(0, 0, 0);   
        $price = 0; 
        while ($walkingDate <= $walkingDateLimit) {
            $dateTimeStart = clone $walkingDate;
            $dateTimeStart->setTime($startTimeParts[0], $startTimeParts[1], 0);
            $dateTimeEnd = clone $walkingDate;
            $dateTimeEnd->setTime($endTimeParts[0], $endTimeParts[1], 0);
            $pricing = $this->squarePricingManager->getFinalPricingInRange($dateTimeStart, $dateTimeEnd, $square, $quantity, $type);
            $price += $pricing['price'];
            $walkingDate->modify('+' . $repeat. ' day');
        }        

        if ($pricing) {
            $squareType = $this->optionManager->need('subject.square.type');
            $squareName = $controller->t($square->need('name'));

            /** @var $dateRangeHelper DateRange  */
            $dateRangeHelper = $this->viewHelperManager->get('DateRange');

            $dateTimeStart = clone $dateStart;
            $dateTimeStart->setTime($startTimeParts[0], $startTimeParts[1], 0);
            if ($dateEnd) {
                $dateTimeEnd = clone $dateEnd;
                $dateTimeEnd->setTime($endTimeParts[0], $endTimeParts[1], 0);
            } else {
                $dateTimeEnd = clone $dateStart;
                $dateTimeEnd->setTime($endTimeParts[0], $endTimeParts[1], 0);
            }
            $description = sprintf('%s %s, %s',
                $squareType, $squareName,
                $dateRangeHelper($dateTimeStart, $dateTimeEnd));

            if ($dateEnd) {
                $description .= ' ' . sprintf($controller->t('%s-%s'), $timeStart, $timeEnd);
            }

            $bookingBill = new Bill(array(
                'description' => $description,
                'quantity' => $quantity,
                'time' => $pricing['seconds'],
                'price' => $price,
                'rate' => $pricing['rate'],
                'gross' => $pricing['gross'],
            ));

            array_unshift($bills, $bookingBill);
        }

        if ($bills) {
            $extraBills = array();

            foreach ($bills as $bill) {
                if (! ($bill instanceof Bill)) {
                    throw new RuntimeException('Invalid bills array passed');
                }

                $bill->set('bid', $booking->need('bid'));

                $this->billManager->save($bill);

                $extraBills[$bill->need('bid')] = $bill;
            }

            $booking->setExtra('bills', $extraBills);
        }
    }

}
