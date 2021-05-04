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
     * @ORM\ManyToOne(targetEntity=Gradja::class, inversedBy="posudbe")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gradja;

    /**
     * @ORM\ManyToOne(targetEntity=Statusi::class, inversedBy="posudbe")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Korisnici::class, inversedBy="posudbe")
     */
    private $korisnici;

    /**
     * @ORM\ManyToOne(targetEntity=Knjiznice::class, inversedBy="posudbe")
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

    public function getGradja(): ?Gradja
    {
        return $this->gradja;
    }

    public function setGradja(?Gradja $gradja): self
    {
        $this->gradja = $gradja;

        return $this;
    }

    public function getStatus(): ?Statusi
    {
        return $this->status;
    }

    public function setStatus(?Statusi $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getKorisnici(): ?Korisnici
    {
        return $this->korisnici;
    }

    public function setKorisnici(?Korisnici $korisnici): self
    {
        $this->korisnici = $korisnici;

        return $this;
    }

    public function zakasnina(){
        $daniKasnjenja = $this->getDatumRokaVracanja()->diff($this->getDatumPosudbe())->format('%r%a');
        $cijenaZakasnine = $this->getKorisnici()->getKnjiznice()->getCijenaZakasnine();
        if($daniKasnjenja <= 0){
            return 0;
        }
        return $daniKasnjenja * $cijenaZakasnine;

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
