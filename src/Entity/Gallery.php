<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass="App\Repository\GalleryRepository")
 */
class Gallery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

	/**
	 * @Gedmo\Slug(fields={"title"})
	 * @ORM\Column(length=128)
	 */
	private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $body;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @Assert\File(mimeTypes={ "image/png", "image/jpeg" })
	 *
	 */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FleImage", mappedBy="gallery", cascade={"persist", "remove"})
     */
    private $gallery_images;


    public function __construct()
    {
        $this->gallery_images = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|FleImage[]
     */
    public function getGalleryImages(): Collection
    {
        return $this->gallery_images;
    }

    public function addGalleryImage(FleImage $galleryImage): self
    {
        if (!$this->gallery_images->contains($galleryImage)) {
            $this->gallery_images[] = $galleryImage;
            $galleryImage->setGallery($this);
        }

        return $this;
    }

    public function removeGalleryImage(FleImage $galleryImage): self
    {
        if ($this->gallery_images->contains($galleryImage)) {
            $this->gallery_images->removeElement($galleryImage);
            // set the owning side to null (unless already changed)
            if ($galleryImage->getGallery() === $this) {
                $galleryImage->setGallery(null);
            }
        }

        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getSlug()
         	{
         		return $this->slug;
         	}

	/**
	 * @param mixed $slug
	 */
	public function setSlug($slug): void
         	{
         		$this->slug = $slug;
         	}

}
