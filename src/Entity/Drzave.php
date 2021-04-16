<?php

namespace App\Entity;

use App\Repository\DrzaveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DrzaveRepository::class)
 */
class Drzave
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
     * @ORM\OneToMany(targetEntity=Autori::class, mappedBy="drzava")
     */
    private $autori;

    public function __construct()
    {
        $this->autori = new ArrayCollection();
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
            $autori->setDrzava($this);
        }

        return $this;
    }

    public function removeAutori(Autori $autori): self
    {
        if ($this->autori->removeElement($autori)) {
            // set the owning side to null (unless already changed)
            if ($autori->getDrzava() === $this) {
                $autori->setDrzava(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->naziv;
    }
}
