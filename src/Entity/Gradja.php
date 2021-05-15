<?php

namespace App\Entity;

use App\Repository\GradjaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=2048, nullable=true)
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
     * @ORM\Column(type="date", nullable=true)
     */
    private $godinaIzdanja;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $brojInventara;

    /**
     * @ORM\ManyToMany(targetEntity=Autori::class, inversedBy="popisGradje")
     */
    private $autori;

    /**
     * @ORM\ManyToOne(targetEntity=Statusi::class, inversedBy="gradje")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity=Zanrovi::class, inversedBy="gradje")
     */
    private $zanrovi;

    /**
     * @ORM\ManyToOne(targetEntity=Knjiznice::class, inversedBy="gradje")
     * @ORM\JoinColumn(nullable=false)
     */
    private $knjiznicaVlasnik;

    /**
     * @ORM\OneToMany(targetEntity=Posudbe::class, mappedBy="gradja")
     */
    private $posudbe;

    /**
     * @ORM\ManyToOne(targetEntity=Izdavaci::class, inversedBy="gradje")
     */
    private $izdavac;

    /**
     * @ORM\ManyToOne(targetEntity=Jezici::class, inversedBy="gradje")
     */
    private $jezici;

    public function __construct()
    {
        $this->autori = new ArrayCollection();
        $this->zanrovi = new ArrayCollection();
        $this->posudbe = new ArrayCollection();
    }

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


    public function getGodinaIzdanja(): ?\DateTimeInterface
    {
        return $this->godinaIzdanja;
    }

    public function setGodinaIzdanja(?\DateTimeInterface $godinaIzdanja): self
    {
        $this->godinaIzdanja = $godinaIzdanja;

        return $this;
    }

    public function getBrojInventara(): ?string
    {
        return $this->brojInventara;
    }

    public function setBrojInventara(string $brojInventara): self
    {
        $this->brojInventara = $brojInventara;

        return $this;
    }

    /**
     * @return Collection|Autori[]
     */
    public function getAutori(): Collection
    {
        return $this->autori;
    }

    public function addAutori(Autori $autori): self
    {
        if (!$this->autori->contains($autori)) {
            $this->autori[] = $autori;
        }

        return $this;
    }

    public function removeAutori(Autori $autori): self
    {
        $this->autori->removeElement($autori);

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

    /**
     * @return Collection|Zanrovi[]
     */
    public function getZanrovi(): Collection
    {
        return $this->zanrovi;
    }

    public function addZanrovi(Zanrovi $zanrovi): self
    {
        if (!$this->zanrovi->contains($zanrovi)) {
            $this->zanrovi[] = $zanrovi;
        }

        return $this;
    }

    public function removeZanrovi(Zanrovi $zanrovi): self
    {
        $this->zanrovi->removeElement($zanrovi);

        return $this;
    }

    public function getKnjiznicaVlasnik(): ?Knjiznice
    {
        return $this->knjiznicaVlasnik;
    }

    public function setKnjiznicaVlasnik(?Knjiznice $knjiznicaVlasnik): self
    {
        $this->knjiznicaVlasnik = $knjiznicaVlasnik;

        return $this;
    }

    /**
     * @return Collection|Posudbe[]
     */
    public function getPosudbe(): Collection
    {
        return $this->posudbe;
    }

    public function addPosudbe(Posudbe $posudbe): self
    {
        if (!$this->posudbe->contains($posudbe)) {
            $this->posudbe[] = $posudbe;
            $posudbe->setGradja($this);
        }

        return $this;
    }

    public function removePosudbe(Posudbe $posudbe): self
    {
        if ($this->posudbe->removeElement($posudbe)) {
            // set the owning side to null (unless already changed)
            if ($posudbe->getGradja() === $this) {
                $posudbe->setGradja(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->getNaslov();
    }

    public function getIzdavac(): ?Izdavaci
    {
        return $this->izdavac;
    }

    public function setIzdavac(?Izdavaci $izdavac): self
    {
        $this->izdavac = $izdavac;

        return $this;
    }

    public function getJezici(): ?Jezici
    {
        return $this->jezici;
    }

    public function setJezici(?Jezici $jezici): self
    {
        $this->jezici = $jezici;

        return $this;
    }
}
