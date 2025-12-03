<?php

namespace App\Entity;

use App\Repository\TechnoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: TechnoRepository::class)]
class Techno
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'technos')]
    private Collection $projects;

    #[Vich\UploadableField(mapping: 'techno_image', fileNameProperty: 'technoImageName', size:'technoImageSize')]
    private ?File $technoImageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $technoImageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $technoImageSize = null;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $project->addTechno($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeTechno($this);
        }

        return $this;
    }

    /**
     * Get the value of technoImageFile
     */
    public function getTechnoImageFile(): ?File
    {
        return $this->technoImageFile;
    }

    /**
     * Set the value of technoImageFile
     */
    public function setTechnoImageFile(?File $technoImageFile): self
    {
        $this->technoImageFile = $technoImageFile;

        return $this;
    }

    /**
     * Get the value of technoImageName
     */
    public function getTechnoImageName(): ?string
    {
        return $this->technoImageName;
    }

    /**
     * Set the value of technoImageName
     */
    public function setTechnoImageName(?string $technoImageName): self
    {
        $this->technoImageName = $technoImageName;

        return $this;
    }

    /**
     * Get the value of technoImageSize
     */
    public function getTechnoImageSize(): ?int
    {
        return $this->technoImageSize;
    }

    /**
     * Set the value of technoImageSize
     */
    public function setTechnoImageSize(?int $technoImageSize): self
    {
        $this->technoImageSize = $technoImageSize;

        return $this;
    }
}
