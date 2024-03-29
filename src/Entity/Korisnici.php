<?php

namespace App\Entity;

use App\Repository\KorisniciRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=KorisniciRepository::class)
 * @method string getUserIdentifier()
 */
class Korisnici implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private ?string $ime;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private ?string $prezime;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     * @Assert\Email
     * @Assert\NotBlank
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Lozinka mora imati barem {{ limit }} znakova! ",
     * )
     * @Assert\NotCompromisedPassword
     */
    private ?string $lozinka;

    /**
     * @ORM\Column(type="string", length=191, nullable=true, unique=true)
     */
    private ?string $brojTelefona;

    /**
     * @ORM\Column(type="string", length=2048, nullable=true)
     * @Assert\Image
     */
    private ?string $fotografija;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     */
    private $brojIskazniceKorisnika;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private ?string $razred;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\OneToMany(targetEntity=Posudbe::class, mappedBy="korisnici")
     */
    private $posudbe;

    /**
     * @ORM\ManyToOne(targetEntity=Knjiznice::class, inversedBy="korisnici")
     */
    private ?Knjiznice $knjiznice;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $notifications = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $postavke = [];


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

    public function getRazred(): ?string
    {
        return $this->razred;
    }

    public function setRazred(?string $razred): self
    {
        $this->razred = $razred;

        return $this;
    }

    public function getRoles(): array
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

    public function getPassword(): string
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

    public function getUsername(): string
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
    public function getBrojIskazniceKorisnika(): mixed
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
     * @return Collection
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

    public function getBrojTrenutnoRezerviranih(): ?int
    {
        $trenutneRezervacije = 0;

        foreach ($this->getPosudbe()->toArray() as $rezervacija) {
            if($rezervacija->getStatus()->getId() == 5){
                $trenutneRezervacije++;
            }
        }
        return $trenutneRezervacije;

    }

    public function getBrojTrenutnoPosudenih(): ?int
    {
        $trenutnePosudbe = 0;

        foreach ($this->getPosudbe()->toArray() as $posudba) {
            if($posudba->getStatus()->getId() == 3 || $posudba->getStatus()->getId() == 9){
                $trenutnePosudbe++;
            }
        }
        return $trenutnePosudbe;

    }

    public function getNotifications(): ?array
    {

        return $this->notifications;
    }

    public function setNotifications(?array $notifications): self
    {
        $this->notifications = $notifications;

        return $this;
    }

    public function addNotifications(?string $notification): self
    {
        if($this->getNotifications() == null){
            $this->notifications = array($notification);
        } else{
            array_push($this->notifications, $notification);
        }

        return $this;
    }

    public function closeNotification(?int $number): self
    {
        unset($this->notifications[$number]);

        return $this;
    }

    public function getPostavke(): ?array
    {
        return $this->postavke;
    }

    public function setPostavke(?array $postavke): self
    {
        $this->postavke = $postavke;

        return $this;
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }
}
