<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\AdvertRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdvertRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['advert:output']],
    denormalizationContext: ['groups' => ['advert:input', 'picutre:input']],
)]

class Advert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['advert:output'])]
    private ?int $id = null;

    #[Assert\NotBlank, Assert\Length(min: 3, max: 100, maxMessage: 'Le nom ne peut pas avoir plus de {{ limit }} caractères', minMessage: 'L\'advert ne peut pas avoir moins de {{ limit }} caractères')]
    #[ORM\Column(type: 'string', length: 100)]
    #[Groups(['advert:output', 'advert:input'])]
    private ?string $title = null;

    #[Assert\NotBlank, Assert\Length(max: 1200, maxMessage: 'La description ne peut pas avoir plus de {{ limit }} caractères')]
    #[ORM\Column(type: 'text')]
    #[Groups(['advert:output', 'advert:input'])]
    private ?string $content = null;

    #[Assert\NotBlank, Assert\Length(max: 255, maxMessage: 'L\'utilisateur ne peut pas avoir plus de {{ limit }} caractères')]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['advert:output', 'advert:input'])]
    private ?string $author = null;

    #[Assert\NotBlank, Assert\Length(max: 255, maxMessage: 'L\'email ne peut pas avoir plus de {{ limit }} caractères',)]
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['advert:output', 'advert:input'])]
    private ?string  $email = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'adverts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['advert:output', 'advert:input'])]
    private ?Category $category = null;

    #[Assert\NotBlank, Assert\Range(
        min: 1,
        max: 1000000.00,
        notInRangeMessage: 'Le prix doit être entre {{ min }} et {{ max }} €',
    )]
    #[ORM\Column(type: 'float')]
    #[Groups(['advert:output', 'advert:input'])]
    private ?float $price = null;

    #[Assert\NotBlank, Assert\Length(max: 255, maxMessage: 'Le status ne peut pas avoir plus de {{ limit }} caractères',)]
    #[ORM\Column(type: 'string', length: 255, options: ['default' => 'draft'])]
    #[Groups(['advert:output', 'advert:input'])]
    private ?string $state = 'draft';

    #[Assert\NotBlank]
    #[ORM\Column(type: 'datetime')]
    #[Groups(['timestamp:output'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(['timestamp:output'])]
    private ?\DateTimeImmutable $publishAt = null;

    #[ORM\OneToMany(mappedBy: 'advert', targetEntity: Picture::class, orphanRemoval: true, cascade: ['all'])]
    #[Groups(['advert:input'])]
    #[ApiSubresource]
    private $pictures;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->pictures = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

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

    public function getPublishAt(): ?\DateTimeImmutable
    {
        return $this->publishAt;
    }

    public function setPublishAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishAt = $publishedAt;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setAdvert($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getAdvert() === $this) {
                $picture->setAdvert(null);
            }
        }

        return $this;
    }
}
