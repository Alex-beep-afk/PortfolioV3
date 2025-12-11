<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $customerName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dificulties = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[Vich\UploadableField(mapping: 'project_image', fileNameProperty: 'projectImageName', size:'projectImageSize')]
    private ?File $projectImageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $projectImageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $projectImageSize = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkToProject = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?User $user = null;

    /**
     * @var Collection<int, Techno>
     */
    #[ORM\ManyToMany(targetEntity: Techno::class, inversedBy: 'projects')]
    private Collection $technos;

    public function __construct()
    {
        $this->technos = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): static
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getDificulties(): ?string
    {
        return $this->dificulties;
    }

    public function setDificulties(?string $dificulties): static
    {
        $this->dificulties = $dificulties;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of linkToProject
     */
    public function getLinkToProject(): ?string
    {
        return $this->linkToProject;
    }

    /**
     * Set the value of linkToProject
     */
    public function setLinkToProject(?string $linkToProject): self
    {
        $this->linkToProject = $linkToProject;

        return $this;
    }

    /**
     * @return Collection<int, Techno>
     */
    public function getTechnos(): Collection
    {
        return $this->technos;
    }

    public function addTechno(Techno $techno): static
    {
        if (!$this->technos->contains($techno)) {
            $this->technos->add($techno);
        }

        return $this;
    }

    public function removeTechno(Techno $techno): static
    {
        $this->technos->removeElement($techno);
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get the value of projectImageFile
     */
    public function getProjectImageFile(): ?File
    {
        return $this->projectImageFile;
    }

    /**
     * Set the value of projectImageFile
     */
    public function setProjectImageFile(?File $projectImageFile): self
    {
        $this->projectImageFile = $projectImageFile;

        return $this;
    }

    /**
     * Get the value of projectImageName
     */
    public function getProjectImageName(): ?string
    {
        return $this->projectImageName;
    }

    /**
     * Set the value of projectImageName
     */
    public function setProjectImageName(?string $projectImageName): self
    {
        $this->projectImageName = $projectImageName;

        return $this;
    }

    /**
     * Get the value of projectImageSize
     */
    public function getProjectImageSize(): ?int
    {
        return $this->projectImageSize;
    }

    /**
     * Set the value of projectImageSize
     */
    public function setProjectImageSize(?int $projectImageSize): self
    {
        $this->projectImageSize = $projectImageSize;

        return $this;
    }
}
