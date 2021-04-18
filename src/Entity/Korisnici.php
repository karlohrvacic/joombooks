<?php

namespace App\Entity;

use App\Repository\KorisniciRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
     * @ORM\Column(type="string", length=255)
     */
    private $brojIskazniceKorisnika;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $razred;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Posudbe::class, mappedBy="korisnici")
     */
    private $posudbe;

    /**
     * @ORM\ManyToOne(targetEntity=Knjiznice::class, inversedBy="korisnici")
     */
    private $knjiznice;


    public function __construct()
    {
        $this->posudbe = new ArrayCollection();
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
        $this->lozinka = $password;

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

    public function __toString(){
       return $this->getIme()." ".$this->getPrezime()." [".$this->getEmail()."]";
    }

    /**
     * @return mixed
     */
    public function getBrojIskazniceKorisnika()
    {
        return $this->brojIskazniceKorisnika;
    }

    /**
     * @param mixed $brojIskazniceKorisnika
     */
    public function setBrojIskazniceKorisnika($brojIskazniceKorisnika): void
    {
        $this->brojIskazniceKorisnika = $brojIskazniceKorisnika;
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
            $posudbe->setKorisnici($this);
        }

        return $this;
    }

    public function removePosudbe(Posudbe $posudbe): self
    {
        if ($this->posudbe->removeElement($posudbe)) {
            // set the owning side to null (unless already changed)
            if ($posudbe->getKorisnici() === $this) {
                $posudbe->setKorisnici(null);
            }
        }

        return $this;
    }

    public function getKnjiznice(): ?Knjiznice
    {
        return $this->knjiznice;
    }

    public function setKnjiznice(?Knjiznice $knjiznice): self
    {
        $this->knjiznice = $knjiznice;

        return $this;
    }
}
