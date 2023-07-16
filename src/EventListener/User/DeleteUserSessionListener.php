<?php

namespace App\EventListener\User;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class DeleteUserSessionListener
{
    public function onLogout(LogoutEvent $event): void
    {
        $response = new Response();
        $cookie = new Cookie('user_auth', '', time() - 3600, '/', 'localhost');
        $response->headers->setCookie($cookie);
        $event->setResponse($response);
    }
}