<?php

namespace App\Entity;

use App\Repository\GradjaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GradjaRepository::class)
 */
class Gradja
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ISBN;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $naslov;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $oibKnjiznice;

    /**
     * @ORM\Column(type="string", length=2048)
     */
    private $fotografija;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $opis;

    /**
     * @ORM\Column(type="date")
     */
    private $datumDodavanja;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $godinaIzdanja;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $jezik;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(?string $ISBN): self
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getNaslov(): ?string
    {
        return $this->naslov;
    }

    public function setNaslov(string $naslov): self
    {
        $this->naslov = $naslov;

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

    public function getFotografija(): ?string
    {
        return $this->fotografija;
    }

    public function setFotografija(string $fotografija): self
    {
        $this->fotografija = $fotografija;

        return $this;
    }

    public function getOpis(): ?string
    {
        return $this->opis;
    }

    public function setOpis(?string $opis): self
    {
        $this->opis = $opis;

        return $this;
    }

    public function getDatumDodavanja(): ?\DateTimeInterface
    {
        return $this->datumDodavanja;
    }

    public function setDatumDodavanja(\DateTimeInterface $datumDodavanja): self
    {
        $this->datumDodavanja = $datumDodavanja;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getGodinaIzdanja(): ?\DateTimeInterface
    {
        return $this->godinaIzdanja;
    }

    public function setGodinaIzdanja(?\DateTimeInterface $godinaIzdanja): self
    {
        $this->godinaIzdanja = $godinaIzdanja;

        return $this;
    }

    public function getJezik(): ?string
    {
        return $this->jezik;
    }

    public function setJezik(?string $jezik): self
    {
        $this->jezik = $jezik;

        return $this;
    }
}
