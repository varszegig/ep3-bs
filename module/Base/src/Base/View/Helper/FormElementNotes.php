<?php

namespace Base\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\View\Helper\AbstractHelper;

class FormElementNotes extends AbstractHelper
{

    public function __invoke(ElementInterface $element, array $arguments = array())
    {
        $notes = $element->getOption('notes');

        if (! $notes) {
            return null;
        }

        $view = $this->getView();

        return '<div class="default-form-notes">' . vsprintf($view->translate($notes), $arguments) . '</div>';
    }

}