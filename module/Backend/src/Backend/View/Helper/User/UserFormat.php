<?php

namespace Backend\View\Helper\User;

use User\Entity\User;
use Laminas\View\Helper\AbstractHelper;

class UserFormat extends AbstractHelper
{

    public function __invoke(User $user, $search = null)
    {
        $view = $this->getView();
        $html = '';

        switch ($user->need('status')) {
            case 'placeholder':
                $attr = ' class="gray"';
                break;
            default:
                $attr = null;
                break;
        }

        $html .= sprintf('<tr %s>', $attr);

        $html .= sprintf('<td headers="%s">%s</td>', $view->t('No.'),
            $user->need('uid'));

        $html .= sprintf('<td headers="%s">%s</td>', $view->t('Name'),
            $user->need('alias'));

        $html .= sprintf('<td headers="%s">%s</td>', $view->t('Status'),
            $view->t($user->getStatus()));

        /* Email col */

        $email = $user->get('email');

        if ($email) {
            $email = '<a href="mailto:' . $email . '" class="unlined" style="color: #333; opacity: 1.0;">' . $email . '</a>';
        } else {
            $email = '-';
        }

        $html .= sprintf('<td class="email-col" headers="%s">%s</td>', $view->t('Email address'),
            $email);

        /* Notes col */

        $notes = $user->getMeta('notes');

        if ($notes) {
            if (strlen($notes) > 48) {
                $notes = substr($notes, 0, 48) . '&hellip;';
            }

            $notes = '<span class="small-text">' . $notes . '</span>';
        } else {
            $notes = '-';
        }

        $html .= sprintf('<td class="notes-col">%s</td>',
            $notes);

        /* Actions col */

        $html .= sprintf('<td class="actions-col no-print"><a href="%s" class="unlined gray symbolic symbolic-edit">%s</a> &nbsp; <a href="%s" class="unlined gray symbolic symbolic-booking">%s</a></td>',
            $view->url('backend/user/edit', ['uid' => $user->need('uid')], ['query' => ['search' => $search]]),
            $view->t('Edit'),
            $view->url('backend/booking', [], ['query' => ['user' => $user->need('alias') . ' (' . $user->need('uid') . ')']]),
            $view->t('Bookings'));

        $html .= '</tr>';

        return $html;
    }

}