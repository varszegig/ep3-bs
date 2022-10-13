<?php

namespace Backend\View\Helper\User;

use Laminas\Stdlib\RequestInterface;
use User\Entity\User;
use Laminas\View\Helper\AbstractHelper;

class UserStatusList extends AbstractHelper
{

    protected $request;

    public function __construct(RequestInterface $request)
    {

        $this->request = $request;
    }
    
    public function __invoke()
    {
        $html = '';
        $view = $this->getView();
        $userStatus = User::$statusOptions;
        $html .= '<select name="usf-status" id="user-status" class="select">';
        $selectedStatus = $this->request->getQuery('usf-status');

        $html .= '<option value=""';
        if ($selectedStatus == '') $html .= ' selected="selected"';
        $html .= '></option>';

        foreach($userStatus as $key => $status) {

                $html .= '<option value="' . $key . '"';
                if ($selectedStatus == $status) $html .= ' selected="selected"';
                $html .= '>' . $view->t($status) . '</option>';

        }

        return $html;
    }

}