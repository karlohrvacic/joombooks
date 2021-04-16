<?php

namespace App\Entity;

use App\Repository\KnjizniceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KnjizniceRepository::class)
 */
class Knjiznice
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
    private $oibKnjiznice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $naziv;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresa;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $cijenaZakasnine;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lozinka;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxPosudjenih;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxRezerviranih;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNaziv(): ?string
    {
        return $this->naziv;
    }

    public function setNaziv(string $naziv): self
    {
        $this->naziv = $naziv;

        return $this;
    }

    public function getAdresa(): ?string
    {
        return $this->adresa;
    }

    public function setAdresa(string $adresa): self
    {
        $this->adresa = $adresa;

        return $this;
    }

    public function getCijenaZakasnine(): ?float
    {
        return $this->cijenaZakasnine;
    }

    public function setCijenaZakasnine(float $cijenaZakasnine): self
    {
        $this->cijenaZakasnine = $cijenaZakasnine;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLozinka(): ?string
    {
        return $this->lozinka;
    }

    public function setLozinka(string $lozinka): self
    {
        $this->lozinka = $lozinka;

        return $this;
    }

    public function getMaxPosudjenih(): ?int
    {
        return $this->maxPosudjenih;
    }

    public function setMaxPosudjenih(int $maxPosudjenih): self
    {
        $this->maxPosudjenih = $maxPosudjenih;

        return $this;
    }

    public function getMaxRezerviranih(): ?int
    {
        return $this->maxRezerviranih;
    }

    public function setMaxRezerviranih(int $maxRezerviranih): self
    {
        $this->maxRezerviranih = $maxRezerviranih;

        return $this;
    }
}
