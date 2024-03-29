<?php

namespace App\Entity;

use App\Repository\PosudbeRepository;
use DateTime;
use DateTimeInterface;
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
    private ?int $id;

    /**
     * @ORM\Column(type="date")
     */
    private ?DateTimeInterface $datumPosudbe;

    /**
     * @ORM\Column(type="date")
     */
    private ?DateTimeInterface $datumRokaVracanja;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private ?DateTimeInterface $datumVracanja;

    /**
     * @ORM\ManyToOne(targetEntity=Gradja::class, inversedBy="posudbe")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Gradja $gradja;

    /**
     * @ORM\ManyToOne(targetEntity=Statusi::class, inversedBy="posudbe")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Statusi $status;

    /**
     * @ORM\ManyToOne(targetEntity=Korisnici::class, inversedBy="posudbe")
     */
    private ?Korisnici $korisnici;

    /**
     * @ORM\ManyToOne(targetEntity=Knjiznice::class, inversedBy="posudbe")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Knjiznice $knjiznica;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatumPosudbe(): ?DateTimeInterface
    {
        return $this->datumPosudbe;
    }

    public function setDatumPosudbe(DateTimeInterface $datumPosudbe): self
    {
        $this->datumPosudbe = $datumPosudbe;

        return $this;
    }

    public function getDatumRokaVracanja(): ?DateTimeInterface
    {
        return $this->datumRokaVracanja;
    }

    public function setDatumRokaVracanja(DateTimeInterface $datumRokaVracanja): self
    {
        $this->datumRokaVracanja = $datumRokaVracanja;

        return $this;
    }

    public function getDatumVracanja(): ?DateTimeInterface
    {
        return $this->datumVracanja;
    }

    public function setDatumVracanja(?DateTimeInterface $datumVracanja): self
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

    public function zakasnina(): float|int
    {
        $daniKasnjenja = $this->getDatumRokaVracanja()->diff(new DateTime('today'))->format('%r%a');
        $cijenaZakasnine = $this->getKorisnici()->getKnjiznice()->getCijenaZakasnine();
        if($daniKasnjenja <= 0){
            return 0;
        }
        return $daniKasnjenja * $cijenaZakasnine;
    }

    public function brojDanaIsteka(): string
    {
        return ((new DateTime('today'))->diff($this->getDatumRokaVracanja())->format('%r%a'));
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

    public function getBrojPutaProduljenjaRezervacije(): float|int
    {
        if($this->getStatus()->getId() == 5){
            return ($this->getDatumPosudbe()->diff($this->getDatumRokaVracanja())->format('%r%a')
                / $this->getKnjiznica()->getDaniRezervacije());
        }else{
            return 0;
        }
    }

    public function getBrojPutaProduljenjaPosudbe(): float|int
    {
        if($this->getStatus()->getId() == 3){
            return ($this->getDatumPosudbe()->diff($this->getDatumRokaVracanja())->format('%r%a')
                / $this->getKnjiznica()->getDaniPosudbe());
        }else{
            return 0;
        }
    }
}
