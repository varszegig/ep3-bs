<?php

namespace Frontend\Controller\Plugin;

use Square\Manager\SquareManager;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class DetermineSquareGroup extends AbstractPlugin
{

    public function __invoke(SquareManager $squareManager)
    {
        $controller = $this->getController();
        if ($squareManager->getMinSquareGroup() > 0) {

            $group = $controller->params()->fromQuery('group-select');

            if (! $group) {
                if ($controller->cookie()->get('group-select')) {
                    $group = $controller->cookie()->get('group-select');
                } else {
                    $group = $squareManager->getMinSquareGroup();
                }
            }

            $controller->cookie()->set('group-select', $group);

            return $group;
        }
    }

}