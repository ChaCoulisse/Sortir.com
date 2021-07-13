<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email", "userName"}, message="Un compte comportant cet E-mail ou pseudo existe déjà.")
 * @ORM\Table(name="`user`")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer votre nom")
     * @Assert\Length(min=2, max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $lastname;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer votre prénom")
     * @Assert\Length(min=2, max=50)
     * @ORM\Column(type="string", length=50)
     */
    private $firstname;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer votre numéro de téléphone")
     * @Assert\Length(min=10, max=15)
     * @ORM\Column(type="string", length=15)
     */
    private $phone;

    /**
     * @Assert\Email(message = "Votre email '{{ value }}' n'est pas valide")
     * @ORM\Column(type="string", length=70, unique=true)
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer un mot de passe")
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Veuillez rentrer votre pseudo")
     * @ORM\Column(type="string", length=50)
     */
    private $userName;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\OneToMany(targetEntity=Trip::class, mappedBy="organizer")
     */
    private $organizedTrips;

    /**
     * @ORM\ManyToMany(targetEntity=Trip::class, mappedBy="participants")
     */
    private $participantsTrip;

    public function __construct()
    {
        $this->organizedTrips = new ArrayCollection();
        $this->participantsTrip = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getCampus(): ?campus
    {
        return $this->campus;
    }

    public function setCampus(?campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection|Trip[]
     */
    public function getOrganizedTrips(): Collection
    {
        return $this->organizedTrips;
    }

    public function addOrganizedTrip(Trip $organizedTrip): self
    {
        if (!$this->organizedTrips->contains($organizedTrip)) {
            $this->organizedTrips[] = $organizedTrip;
            $organizedTrip->setOrganizer($this);
        }

        return $this;
    }

    public function removeOrganizedTrip(Trip $organizedTrip): self
    {
        if ($this->organizedTrips->removeElement($organizedTrip)) {
            // set the owning side to null (unless already changed)
            if ($organizedTrip->getOrganizer() === $this) {
                $organizedTrip->setOrganizer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Trip[]
     */
    public function getParticipantsTrip(): Collection
    {
        return $this->participantsTrip;
    }

    public function addParticipantsTrip(Trip $participantsTrip): self
    {
        if (!$this->participantsTrip->contains($participantsTrip)) {
            $this->participantsTrip[] = $participantsTrip;
            $participantsTrip->addParticipants($this);
        }

        return $this;
    }

    public function removeParticipantsTrip(Trip $participantsTrip): self
    {
        if ($this->participantsTrip->removeElement($participantsTrip)) {
            $participantsTrip->removeParticipants($this);
        }

        return $this;
    }
}
