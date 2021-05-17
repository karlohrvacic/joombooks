<?php

namespace App\Entity;

use App\Repository\KnjizniceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=KnjizniceRepository::class)
 */
class Knjiznice implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $oibKnjiznice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $naziv;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresa;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $cijenaZakasnine;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lozinka;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxPosudjenih;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxRezerviranih;

    /**
     * @ORM\OneToMany(targetEntity=Gradja::class, mappedBy="knjiznicaVlasnik")
     */
    private $gradje;


    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Korisnici::class, mappedBy="knjiznice")
     */
    private $korisnici;

    /**
     * @ORM\Column(type="integer")
     */
    private $daniRezervacije;

    /**
     * @ORM\OneToMany(targetEntity=Posudbe::class, mappedBy="knjiznica")
     */
    private $posudbe;

    /**
     * @ORM\Column(type="integer")
     */
    private $daniPosudbe;

    public function __construct()
    {
        $this->gradje = new ArrayCollection();
        $this->korisnici = new ArrayCollection();
        $this->posudbe = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOibKnjiznice(): ?string
    {
        return $this->oibKnjiznice;
    }

    public function setOibKnjiznice(string $oibKnjiznice): self
    {
        $this->oibKnjiznice = $oibKnjiznice;

        return $this;
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

    public function setAdresa(string $adresa): self
    {
        $this->adresa = $adresa;

        return $this;
    }

    public function getCijenaZakasnine(): ?float
    {
        return $this->cijenaZakasnine;
    }

    public function setCijenaZakasnine(float $cijenaZakasnine): self
    {
        $this->cijenaZakasnine = $cijenaZakasnine;

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

    public function getMaxPosudjenih(): ?int
    {
        return $this->maxPosudjenih;
    }

    public function setMaxPosudjenih(int $maxPosudjenih): self
    {
        $this->maxPosudjenih = $maxPosudjenih;

        return $this;
    }

    public function getMaxRezerviranih(): ?int
    {
        return $this->maxRezerviranih;
    }

    public function setMaxRezerviranih(int $maxRezerviranih): self
    {
        $this->maxRezerviranih = $maxRezerviranih;

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
            $gradje->setKnjiznicaVlasnik($this);
        }

        return $this;
    }

    public function removeGradje(Gradja $gradje): self
    {
        if ($this->gradje->removeElement($gradje)) {
            // set the owning side to null (unless already changed)
            if ($gradje->getKnjiznicaVlasnik() === $this) {
                $gradje->setKnjiznicaVlasnik(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_LIBRARY';

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

    /**
     * @return Collection|Korisnici[]
     */
    public function getKorisnici(): Collection
    {
        return $this->korisnici;
    }

    public function addKorisnici(Korisnici $korisnici): self
    {
        if (!$this->korisnici->contains($korisnici)) {
            $this->korisnici[] = $korisnici;
            $korisnici->setKnjiznice($this);
        }

        return $this;
    }

    public function removeKorisnici(Korisnici $korisnici): self
    {
        if ($this->korisnici->removeElement($korisnici)) {
            // set the owning side to null (unless already changed)
            if ($korisnici->getKnjiznice() === $this) {
                $korisnici->setKnjiznice(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getNaziv();
    }

    public function getDaniRezervacije(): ?int
    {
        return $this->daniRezervacije;
    }

    public function setDaniRezervacije(int $daniRezervacije): self
    {
        $this->daniRezervacije = $daniRezervacije;

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
            $posudbe->setKnjiznica($this);
        }

        return $this;
    }

    public function removePosudbe(Posudbe $posudbe): self
    {
        if ($this->posudbe->removeElement($posudbe)) {
            // set the owning side to null (unless already changed)
            if ($posudbe->getKnjiznica() === $this) {
                $posudbe->setKnjiznica(null);
            }
        }

        return $this;
    }

    public function getDaniPosudbe(): ?int
    {
        return $this->daniPosudbe;
    }

    public function setDaniPosudbe(int $daniPosudbe): self
    {
        $this->daniPosudbe = $daniPosudbe;

        return $this;
    }
}

