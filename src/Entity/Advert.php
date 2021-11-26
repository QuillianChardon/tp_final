<?php

namespace App\Entity;

use App\Repository\AdvertRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdvertRepository::class)]
class Advert
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Assert\NotBlank, Assert\Length(min: 3, max: 100, maxMessage: 'Le nom ne peut pas avoir plus de {{ limit }} caractères', minMessage: 'L\'advert ne peut pas avoir moins de {{ limit }} caractères')]
    #[ORM\Column(type: 'string', length: 100)]
    private ?string $title = null;

    #[Assert\NotBlank, Assert\Length(max: 1200, maxMessage: 'La description ne peut pas avoir plus de {{ limit }} caractères')]
    #[ORM\Column(type: 'text')]
    private ?string $content = null;

    #[Assert\NotBlank, Assert\Length(max: 255, maxMessage: 'L\'utilisateur ne peut pas avoir plus de {{ limit }} caractères')]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $author = null;

    #[Assert\NotBlank, Assert\Length(max: 255, maxMessage: 'L\'email ne peut pas avoir plus de {{ limit }} caractères',)]
    #[ORM\Column(type: 'string', length: 255)]
    private ?string  $email = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'adverts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[Assert\NotBlank, Assert\Range(
        min: 1,
        max: 1000000.00,
        notInRangeMessage: 'Le prix doit être entre {{ min }} et {{ max }} €',
    )]
    #[ORM\Column(type: 'float')]
    private ?float $price = null;

    #[Assert\NotBlank, Assert\Length(max: 255, maxMessage: 'Le status ne peut pas avoir plus de {{ limit }} caractères',)]
    #[ORM\Column(type: 'string', length: 255, options: ['default' => 'draft'])]
    private ?string $state = 'draft';

    #[Assert\NotBlank]
    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $publishAt = null;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
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

    public function getPublishAt(): ?\DateTimeInterface
    {
        return $this->publishAt;
    }

    public function setPublishAt(?\DateTimeInterface $publishAt): self
    {
        $this->publishAt = $publishAt;

        return $this;
    }
}
