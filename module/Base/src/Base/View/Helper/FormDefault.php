<?php

namespace Base\View\Helper;

use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Submit;
use Laminas\Form\Form;
use Laminas\View\Helper\AbstractHelper;

class FormDefault extends AbstractHelper
{

    public function __invoke(Form $form, $action)
    {
        $form->setAttribute('method', 'post');
        $form->setAttribute('action', $action);
        $form->prepare();

        $view = $this->getView();
        $html = '';

        $html .= $view->form()->openTag($form);

        $html .= '<table class="default-table">';

        $formElements = $form->getElements();

        foreach ($formElements as $formElement) {
            if ($formElement instanceof Checkbox) {
                $html .= $view->formRowCheckbox($form, $formElement);
            } else if ($formElement instanceof Submit) {
                $html .= $view->formRowSubmit($form, $formElement);
            } else {
                $html .= $view->formRowDefault($form, $formElement);
            }
        }

        $html .= '</table>';

        $html .= $view->form()->closeTag();

        return $html;
    }

}