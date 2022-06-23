<?php

namespace Base\View\Helper\Layout;

use User\Manager\UserSessionManager;
use Laminas\View\Helper\AbstractHelper;

class HeaderUser extends AbstractHelper
{
    protected $userManager;

    public function __construct(UserSessionManager $userManager)
    {
        $this->userManager = $userManager;
    }

    public function __invoke()
    {
        return $this->userManager->getSessionUser();   
    }
}