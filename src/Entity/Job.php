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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=JobArticle::class, mappedBy="job", orphanRemoval=true)
     */
    private $jobArticles;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $regionName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $depCode;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $depName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codeInseeCommune;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raisonSociale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $geoLatitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $geoLongitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\ManyToMany(targetEntity=JobCategory::class, inversedBy="jobs")
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apiRecordId;

    /**
     * @ORM\OneToMany(targetEntity=FleImage::class, mappedBy="job", cascade={"persist", "remove"})
     */
    private $carouselImages;

    public function __construct()
    {
        $this->jobArticles = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->carouselImages = new ArrayCollection();
    }

    public function initJob($name, $regionName, $lat, $long, $ville, $raison, $cp, $insee, $depName, $depCode, $adresse, $recordId){
        $this->name = $name;
        $this->regionName = $regionName;
        $this->geoLatitude = $lat;
        $this->geoLongitude = $long;
        $this->ville = $ville;
        $this->raisonSociale = $raison;
        $this->codePostal = $cp;
        $this->codeInseeCommune= $insee;
        $this->depName = $depName;
        $this->depCode = $depCode;
        $this->adresse = $adresse;
        $this->apiRecordId = $recordId;
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

    public function getRegionName(): ?string
    {
        return $this->regionName;
    }

    public function setRegionName(?string $regionName): self
    {
        $this->regionName = $regionName;

        return $this;
    }

    public function getDepCode(): ?int
    {
        return $this->depCode;
    }

    public function setDepCode(?int $depCode): self
    {
        $this->depCode = $depCode;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDepName(): ?string
    {
        return $this->depName;
    }

    public function setDepName(?string $depName): self
    {
        $this->depName = $depName;

        return $this;
    }

    public function getCodeInseeCommune(): ?string
    {
        return $this->codeInseeCommune;
    }

    public function setCodeInseeCommune(?string $codeInseeCommune): self
    {
        $this->codeInseeCommune = $codeInseeCommune;

        return $this;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raisonSociale;
    }

    public function setRaisonSociale(?string $raisonSociale): self
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getGeoLatitude(): ?float
    {
        return $this->geoLatitude;
    }

    public function setGeoLatitude(?float $geoLatitude): self
    {
        $this->geoLatitude = $geoLatitude;

        return $this;
    }

    public function getGeoLongitude(): ?float
    {
        return $this->geoLongitude;
    }

    public function setGeoLongitude(?float $geoLongitude): self
    {
        $this->geoLongitude = $geoLongitude;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection|JobCategory[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(JobCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(JobCategory $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getApiRecordId(): ?string
    {
        return $this->apiRecordId;
    }

    public function setApiRecordId(?string $apiRecordId): self
    {
        $this->apiRecordId = $apiRecordId;

        return $this;
    }

    /**
     * @return Collection|FleImage[]
     */
    public function getCarouselImages(): Collection
    {
        return $this->carouselImages;
    }

    public function addCarouselImage(FleImage $carouselImage): self
    {
        if (!$this->carouselImages->contains($carouselImage)) {
            $this->carouselImages[] = $carouselImage;
            $carouselImage->setJob($this);
        }

        return $this;
    }

    public function removeCarouselImage(FleImage $carouselImage): self
    {
        if ($this->carouselImages->removeElement($carouselImage)) {
            // set the owning side to null (unless already changed)
            if ($carouselImage->getJob() === $this) {
                $carouselImage->setJob(null);
            }
        }

        return $this;
    }

}
