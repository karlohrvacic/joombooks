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
     * @ORM\ManyToOne(targetEntity=Korisnici::class, inversedBy="clanstva")
     * @ORM\JoinColumn(nullable=false)
     */
    private $korisnik;

    /**
     * @ORM\ManyToOne(targetEntity=Knjiznice::class, inversedBy="clanstva")
     * @ORM\JoinColumn(nullable=false)
     */
    private $knjiznica;


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

    public function getKorisnik(): ?Korisnici
    {
        return $this->korisnik;
    }

    public function setKorisnik(?Korisnici $korisnik): self
    {
        $this->korisnik = $korisnik;

        return $this;
    }

    public function getKnjiznica(): ?Knjiznice
    {
        return $this->knjiznica;
    }

    public function setKnjiznica(?Knjiznice $knjiznica): self
    {
        $this->knjiznica = $knjiznica;

        return $this;
    }
}
