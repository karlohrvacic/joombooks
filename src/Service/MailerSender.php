<?php

namespace App\Service;

use App\Entity\Korisnici;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerSender
{
    private $mailer;
    private $router;

    public function __construct( MailerInterface $mailer, UrlGeneratorInterface $router){
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public function sendActivationEmail(Korisnici $user, $code)
    {
        $signUpPage = $this->router->generate("activation_index", ['code' => $code] , UrlGenerator::ABSOLUTE_URL);
        $ime = $user->getIme();
        $email = (new TemplatedEmail())
            ->from('aktivacija@joombooks.karlo.codes')
            ->to($user->getEmail())
            ->subject('Aktivacija računa!')
            ->htmlTemplate('emails/aktivacija.html.twig')
            ->context([
                'ime' => $ime,
                'link' => $signUpPage
                ]);
        $this->mailer->send($email);
    }
}