<?php

namespace App\Entity;

use App\Repository\JeziciRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=JeziciRepository::class)
 */
class Jezici
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
    private $ime;

    /**
     * @ORM\OneToMany(targetEntity=Gradja::class, mappedBy="jezici")
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

    public function getIme(): ?string
    {
        return $this->ime;
    }

    public function setIme(string $ime): self
    {
        $this->ime = $ime;

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
            $gradje->setJezici($this);
        }

        return $this;
    }

    public function removeGradje(Gradja $gradje): self
    {
        if ($this->gradje->removeElement($gradje)) {
            // set the owning side to null (unless already changed)
            if ($gradje->getJezici() === $this) {
                $gradje->setJezici(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getIme();
    }
}
