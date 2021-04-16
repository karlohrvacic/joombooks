<?php

namespace App\Entity;

use App\Repository\KorisniciRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=KorisniciRepository::class)
 */
class Korisnici implements UserInterface
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
    private $ime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prezime;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lozinka;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brojTelefona;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     */
    private $fotografija;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $razred;

    /**
     * @ORM\OneToMany(targetEntity=Clanstva::class, mappedBy="korisnik")
     */
    private $clanstva;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    public function __construct()
    {
        $this->clanstva = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLozinka(): ?string
    {
        return $this->lozinka;
    }

    public function setLozinka(string $lozinka): self
    {
        $this->lozinka = $lozinka;

        return $this;
    }

    public function getBrojTelefona(): ?string
    {
        return $this->brojTelefona;
    }

    public function setBrojTelefona(?string $brojTelefona): self
    {
        $this->brojTelefona = $brojTelefona;

        return $this;
    }

    public function getFotografija(): ?string
    {
        return $this->fotografija;
    }

    public function setFotografija(?string $fotografija): self
    {
        $this->fotografija = $fotografija;

        return $this;
    }

    public function getRazred(): ?int
    {
        return $this->razred;
    }

    public function setRazred(?int $razred): self
    {
        $this->razred = $razred;

        return $this;
    }

    /**
     * @return Collection|Clanstva[]
     */
    public function getClanstva(): Collection
    {
        return $this->clanstva;
    }

    public function addClanstva(Clanstva $clanstva): self
    {
        if (!$this->clanstva->contains($clanstva)) {
            $this->clanstva[] = $clanstva;
            $clanstva->setKorisnik($this);
        }

        return $this;
    }

    public function removeClanstva(Clanstva $clanstva): self
    {
        if ($this->clanstva->removeElement($clanstva)) {
            // set the owning side to null (unless already changed)
            if ($clanstva->getKorisnik() === $this) {
                $clanstva->setKorisnik(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword()
    {
        return (string) $this->lozinka;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return (string) $this->email;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
