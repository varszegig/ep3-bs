<?php

namespace Backend\View\Helper\Billing;

use Laminas\Stdlib\RequestInterface;
use Booking\Service\BookingStatusService;
use Laminas\View\Helper\AbstractHelper;

class BillingBillingStatusList extends AbstractHelper
{

    protected $bookingStatusService;
    protected $request;

    public function __construct(BookingStatusService $bookingStatusService, RequestInterface $request)
    {

        $this->bookingStatusService = $bookingStatusService;
        $this->request = $request;
    }
    
    public function __invoke()
    {
        $html = '';
        $view = $this->getView();
        $billingStatus = $this->bookingStatusService->getStatusTitles();
        $html .= '<select name="bbsf-billing-status" id="billing-status" class="select">';
        $selectedStatus = $this->request->getQuery('bbsf-billing-status');

        $html .= '<option value=""';
        if ($selectedStatus == '') $html .= ' selected="selected"';
        $html .= '></option>';

        foreach(array_keys($billingStatus) as $status) {

                $html .= '<option value="' . $status . '"';
                if ($selectedStatus == $status) $html .= ' selected="selected"';
                $html .= '>' . $view->t($billingStatus[$status]) . '</option>';

        }

        return $html;
    }

}