<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class MailerService
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendMail(Email $email): void
    {
        $dsn       = $this->getMailerDsn();
        $transport = Transport::fromDsn($dsn);

        $mailer = new Mailer($transport);
        $mailer->send($email);
    }

    private function getMailerDsn(): string
    {
        return $this->parameterBag->get('dsn');
    }
}