<?php

namespace App\Entity;

use App\Repository\ClanstvaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClanstvaRepository::class)
 */
class Clanstva
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brojIskazniceKorisnika;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $oibKnjiznice;

    /**
     * @ORM\Column(type="integer")
     */
    private $idKorisnika;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrojIskazniceKorisnika(): ?string
    {
        return $this->brojIskazniceKorisnika;
    }

    public function setBrojIskazniceKorisnika(string $brojIskazniceKorisnika): self
    {
        $this->brojIskazniceKorisnika = $brojIskazniceKorisnika;

        return $this;
    }

    public function getOibKnjiznice(): ?string
    {
        return $this->oibKnjiznice;
    }

    public function setOibKnjiznice(string $oibKnjiznice): self
    {
        $this->oibKnjiznice = $oibKnjiznice;

        return $this;
    }

    public function getIdKorisnika(): ?int
    {
        return $this->idKorisnika;
    }

    public function setIdKorisnika(int $idKorisnika): self
    {
        $this->idKorisnika = $idKorisnika;

        return $this;
    }
}
