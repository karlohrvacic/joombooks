<?php

namespace App\Service;

use App\Entity\Korisnici;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailerSender
{
    private $mailer;
    private $router;

    public function __construct( MailG $mailer, UrlGeneratorInterface $router){
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public function sendActivationEmail(Korisnici $user)
    {
        $code = $user->getLozinka();
        $signUpPage = $this->router->generate("activation_index", ['code' => $code] , UrlGenerator::ABSOLUTE_URL);
        $ime = $user->getIme();
        $email = (new Email())
            ->from('hello@jumkabooks.hr')
            ->to($user->getEmail())
            ->subject('Aktivacija računa!')
            ->html("<p>Pozdrav $ime!<br>Vaš kod za aktivaciju računa je $code <br> <a href='$signUpPage'>Aktivirajte ovdje</a></p>");

        $this->mailer->send($email);

    }
}