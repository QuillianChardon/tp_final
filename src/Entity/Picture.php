<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PictureRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[ApiResource]
class Picture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['picture:output'])]
    private ?int $id = null;


    #[ORM\Column(type: 'string', length: 500)]
    #[Groups(['picture:output', 'picture:input'])]
    private ?string $path = null;


    #[ORM\Column(type: 'datetime')]
    #[Groups(['timestamp:output'])]
    private ?\DateTime $createdAt = null;

    #[ORM\ManyToOne(targetEntity: advert::class, inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['pictures:output', 'pictures:input'])]
    private ?Advert $advert = null;

    /**
     * @Vich\UploadableField(mapping="adverts", fileNameProperty="path")
     */
    private ?File $file = null;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAdvert(): ?advert
    {
        return $this->advert;
    }

    public function setAdvert(?advert $advert): self
    {
        $this->advert = $advert;

        return $this;
    }

    /**
     * Get the value of file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @return  self
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}
