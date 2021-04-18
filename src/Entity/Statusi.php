<?php

namespace App\Entity;

use App\Repository\StatusiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusiRepository::class)
 */
class Statusi
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
    private $naziv;

    /**
     * @ORM\OneToMany(targetEntity=Gradja::class, mappedBy="status")
     */
    private $gradje;

    /**
     * @ORM\OneToMany(targetEntity=Posudbe::class, mappedBy="status")
     */
    private $posudbe;

    public function __construct()
    {
        $this->gradje = new ArrayCollection();
        $this->posudbe = new ArrayCollection();
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
            $gradje->setStatus($this);
        }

        return $this;
    }

    public function removeGradje(Gradja $gradje): self
    {
        if ($this->gradje->removeElement($gradje)) {
            // set the owning side to null (unless already changed)
            if ($gradje->getStatus() === $this) {
                $gradje->setStatus(null);
            }
        }

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
            $posudbe->setStatus($this);
        }

        return $this;
    }

    public function removePosudbe(Posudbe $posudbe): self
    {
        if ($this->posudbe->removeElement($posudbe)) {
            // set the owning side to null (unless already changed)
            if ($posudbe->getStatus() === $this) {
                $posudbe->setStatus(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getNaziv();
    }
}
