<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FlashMessageHelper implements FlashMessageHelperInterface
{
    private $flashBag;

    public function __construct(RequestStack $requestStack)
    {
        $this->flashBag = $requestStack->getSession()->getFlashBag();
    }

    public function addFormErrorsAsFlash(FormInterface $form): void
    {
        $errors = $form->getErrors(true);
        foreach ($errors as $error) {
            $this->flashBag->add('error', $error->getMessage());
        }
    }
}

