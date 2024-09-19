<?php

// src/EventSubscriber/AuthenticationSubscriber.php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class AuthenticationSubscriber
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    #[AsEventListener(event: LoginSuccessEvent::class)]
    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add('success', 'Connexion réussie !');
    }

    #[AsEventListener(event: LoginFailureEvent::class)]
    public function onLoginFailure(LoginFailureEvent $event): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add('error', 'Login et/ou mot de passe incorrect !');
    }

    #[AsEventListener(event: LogoutEvent::class)]
    public function onLogout(LogoutEvent $event): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add('success', 'Déconnexion réussie !');
    }
}
