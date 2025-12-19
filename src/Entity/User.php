<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['username'], message: ' {{ value }} est déjà utilisé.')]
#[UniqueEntity(fields: ['email'], message: ' {{ value }} est déjà utilisé.')]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Le nom d\'utilisateur est obligatoire.')]
    #[Assert\Length(min: 3, max: 180, minMessage: 'Le nom d\'utilisateur doit contenir au moins 3 caractères.', maxMessage: 'Le nom d\'utilisateur doit contenir au plus 180 caractères.')]
    private ?string $username = null;

    #[ORM\Column(length: 180, nullable: false)]
    private ?string $lastName = null;

    #[ORM\Column(length: 180, nullable: false)]
    private ?string $firstName = null;

    #[ORM\Column(length: 180, nullable: false)]
    private ?string $phone = null;

    #[ORM\Column(length: 64, unique: true, nullable: false)]
    private ?string $shareToken = null;
    

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $themes = null;

    /**
     * @var Collection<int, Diplomas>
     */
    #[ORM\OneToMany(targetEntity: Diplomas::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $diplomas;

    /**
     * @var Collection<int, Experience>
     */
    #[ORM\OneToMany(targetEntity: Experience::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $experiences;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(targetEntity: Project::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $projects;

    #[Vich\UploadableField(mapping: 'avatar', fileNameProperty: 'avatarName', size:'imageSize')]
    private ?File $avatarFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $avatarName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: false)]
    private ?DateTimeImmutable $createdAt = null;
    
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: false)]
    #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide.')]
    #[Assert\NotBlank(message: 'L\'email est obligatoire.')]
    private ?string $email = null;

    public function __construct()
    {
        $this->diplomas = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);
        // Exclure avatarFile de la sérialisation car c'est un UploadedFile non sérialisable
        unset($data["\0".self::class."\0avatarFile"]);
        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getThemes(): ?array
    {
        return $this->themes;
    }

    public function setThemes(?array $themes): static
    {
        $this->themes = $themes;

        return $this;
    }

    /**
     * @return Collection<int, Diplomas>
     */
    public function getDiplomas(): Collection
    {
        return $this->diplomas;
    }

    public function addDiploma(Diplomas $diploma): static
    {
        if (!$this->diplomas->contains($diploma)) {
            $this->diplomas->add($diploma);
            $diploma->setUser($this);
        }

        return $this;
    }

    public function removeDiploma(Diplomas $diploma): static
    {
        if ($this->diplomas->removeElement($diploma)) {
            // set the owning side to null (unless already changed)
            if ($diploma->getUser() === $this) {
                $diploma->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): static
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences->add($experience);
            $experience->setUser($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): static
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setUser($this);
        }

        return $this;
    }

    /**
     * Remove a project from the user @param Project $project
     * @return $this
     * use $project->getUser() and $project->setUser(null) to remove the project from the user
     */
    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getUser() === $this) {
                $project->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     */
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of imageSize
     */
    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    /**
     * Set the value of imageSize
     */
    public function setImageSize(?int $imageSize): self
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    /**
     * Get the value of avatarName
     */
    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    /**
     * Set the value of avatarName
     */
    public function setAvatarName(?string $avatarName): self
    {
        $this->avatarName = $avatarName;

        return $this;
    }

    /**
     * Get the value of avatarFile
     */
    public function getAvatarFile(): ?File
    {
        return $this->avatarFile;
    }

    /**
     * Set the value of avatarFile
     */
    public function setAvatarFile(?File $avatarFile): void
    {
        $this->avatarFile = $avatarFile;
        
        if(null !== $avatarFile){
            $this->updatedAt = new DateTimeImmutable();
        }
        

    }

    /**
     * Get the value of email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of lastName
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of firstName
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of phone
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    #[ORM\PrePersist]
    public function autoCreatedAt(): void
    {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function autoUpdatedAt(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    /**
     * Get the value of shareToken
     */
    public function getShareToken(): ?string
    {
        return $this->shareToken;
    }

    /**
     * Set the value of shareToken
     */
    public function setShareToken(?string $shareToken): self
    {
        $this->shareToken = $shareToken;

        return $this;
    }

    #[ORM\PrePersist]
    public function autoGenerateShareToken(): void
    {
        $this->shareToken = bin2hex(random_bytes(32));
    }
}
