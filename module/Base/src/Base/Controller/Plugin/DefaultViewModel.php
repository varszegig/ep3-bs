<?php

namespace Base\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\View\Model\ViewModel;

class DefaultViewModel extends AbstractPlugin
{

    public function __invoke($variables = null, $options = null, $template = null)
    {
        $viewModel = new ViewModel($variables, $options);

        if ($template) {
            $viewModel->setTemplate($template);
        }

        return $viewModel;
    }

}