<?php

namespace Base\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\View\Helper\AbstractHelper;

class FormRowSubmit extends AbstractHelper
{

    public function __invoke($form, $id)
    {
        $view = $this->getView();

        if ($id instanceof ElementInterface) {
            $formElement = $id;
        } else {
            $formElement = $form->get($id);
        }

        $html = sprintf('<tr><td>&nbsp;</td><td>%s</td></tr>',
            $view->formElement($formElement));

        return $html;
    }

}