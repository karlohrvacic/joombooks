<?php

namespace App\Entity;

use App\Repository\PosudbeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PosudbeRepository::class)
 */
class Posudbe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $idGradje;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $brojIskazniceKorisnika;

    /**
     * @ORM\Column(type="date")
     */
    private $datumPosudbe;

    /**
     * @ORM\Column(type="date")
     */
    private $datumRokaVracanja;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datumVracanja;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdGradje(): ?int
    {
        return $this->idGradje;
    }

    public function setIdGradje(int $idGradje): self
    {
        $this->idGradje = $idGradje;

        return $this;
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

    public function getDatumPosudbe(): ?\DateTimeInterface
    {
        return $this->datumPosudbe;
    }

    public function setDatumPosudbe(\DateTimeInterface $datumPosudbe): self
    {
        $this->datumPosudbe = $datumPosudbe;

        return $this;
    }

    public function getDatumRokaVracanja(): ?\DateTimeInterface
    {
        return $this->datumRokaVracanja;
    }

    public function setDatumRokaVracanja(\DateTimeInterface $datumRokaVracanja): self
    {
        $this->datumRokaVracanja = $datumRokaVracanja;

        return $this;
    }

    public function getDatumVracanja(): ?\DateTimeInterface
    {
        return $this->datumVracanja;
    }

    public function setDatumVracanja(?\DateTimeInterface $datumVracanja): self
    {
        $this->datumVracanja = $datumVracanja;

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
}
