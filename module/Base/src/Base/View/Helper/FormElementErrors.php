<?php

namespace Base\View\Helper;

use Laminas\Form\View\Helper\FormElementErrors as LaminasFormElementErrors;

class FormElementErrors extends LaminasFormElementErrors
{

    protected $attributes = array(
        'class' => 'default-form-errors message',
    );

}