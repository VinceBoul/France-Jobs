<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=JobCategory::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=JobArticle::class, mappedBy="job", orphanRemoval=true)
     */
    private $jobArticles;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->jobArticles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCategory(): ?JobCategory
    {
        return $this->category;
    }

    public function setCategory(?JobCategory $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|JobArticle[]
     */
    public function getJobArticles(): Collection
    {
        return $this->jobArticles;
    }

    public function addJobArticle(JobArticle $jobArticle): self
    {
        if (!$this->jobArticles->contains($jobArticle)) {
            $this->jobArticles[] = $jobArticle;
            $jobArticle->setJob($this);
        }

        return $this;
    }

    public function removeJobArticle(JobArticle $jobArticle): self
    {
        if ($this->jobArticles->removeElement($jobArticle)) {
            // set the owning side to null (unless already changed)
            if ($jobArticle->getJob() === $this) {
                $jobArticle->setJob(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
