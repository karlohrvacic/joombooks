<?php

namespace App\Entity;

use App\Repository\AutoriRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AutoriRepository::class)
 */
class Autori
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $ime;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()

     */
    private $prezime;


    /**
     * @ORM\ManyToMany(targetEntity=Gradja::class, mappedBy="autori")
     */
    private $popisGradje;

    /**
     * @ORM\ManyToOne(targetEntity=Drzave::class, inversedBy="autori")
     */
    private $drzava;

    public function __construct()
    {
        $this->popisGradje = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIme(): ?string
    {
        return $this->ime;
    }

    public function setIme(string $ime): self
    {
        $this->ime = $ime;

        return $this;
    }

    public function getPrezime(): ?string
    {
        return $this->prezime;
    }

    public function setPrezime(string $prezime): self
    {
        $this->prezime = $prezime;

        return $this;
    }

    /**
     * @return Collection|Gradja[]
     */
    public function getPopisGradje(): Collection
    {
        return $this->popisGradje;
    }

    public function addPopisGradje(Gradja $popisGradje): self
    {
        if (!$this->popisGradje->contains($popisGradje)) {
            $this->popisGradje[] = $popisGradje;
            $popisGradje->addAutori($this);
        }

        return $this;
    }

    public function removePopisGradje(Gradja $popisGradje): self
    {
        if ($this->popisGradje->removeElement($popisGradje)) {
            $popisGradje->removeAutori($this);
        }

        return $this;
    }

    public function getDrzava(): ?Drzave
    {
        return $this->drzava;
    }

    public function setDrzava(?Drzave $drzava): self
    {
        $this->drzava = $drzava;

        return $this;
    }

    public function __toString(){
        return $this->getPrezime().", ".$this->getIme();
    }
}
