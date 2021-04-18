<?php

namespace App\Service;

use Symfony\Component\Security\Core\Security;
class GetUser
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getKorisnik() : Response
    {
        $user = $this->security->getUser(); // null or UserInterface, if logged in
        // ... do whatever you want with $user
    }
}
