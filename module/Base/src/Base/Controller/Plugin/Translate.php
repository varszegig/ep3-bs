<?php

namespace Base\Controller\Plugin;

use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;

class Translate extends AbstractPlugin
{

    protected $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function __invoke($message)
    {
        return $this->translator->translate($message);
    }

}