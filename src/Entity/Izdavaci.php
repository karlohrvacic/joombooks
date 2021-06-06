<?php

namespace App\Entity;

use App\Repository\IzdavaciRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=IzdavaciRepository::class)
 */
class Izdavaci
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $naziv;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresa;

    /**
     * @ORM\ManyToOne(targetEntity=Drzave::class, inversedBy="izdavaci")
     */
    private $drzava;

    /**
     * @ORM\OneToMany(targetEntity=Gradja::class, mappedBy="izdavac")
     */
    private $gradje;

    public function __construct()
    {
        $this->gradje = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setAdresa(?string $adresa): self
    {
        $this->adresa = $adresa;

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

    /**
     * @return Collection|Gradja[]
     */
    public function getGradje(): Collection
    {
        return $this->gradje;
    }

    public function addGradje(Gradja $gradje): self
    {
        if (!$this->gradje->contains($gradje)) {
            $this->gradje[] = $gradje;
            $gradje->setIzdavac($this);
        }

        return $this;
    }

    public function removeGradje(Gradja $gradje): self
    {
        if ($this->gradje->removeElement($gradje)) {
            // set the owning side to null (unless already changed)
            if ($gradje->getIzdavac() === $this) {
                $gradje->setIzdavac(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getNaziv();
    }
}
