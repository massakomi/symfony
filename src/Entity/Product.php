<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(nullable=false, type="string", length=255)
     */
    #[Assert\NotBlank]
    private $name;

    /**
     * @ORM\Column(nullable=true, type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(nullable=true, type="integer")
     */
    private $price;

    /**
     * @ORM\Column(nullable=true, type="integer")
     */
    private $price_old;

    /**
     * @ORM\Column(nullable=true, type="string")
     */
    private $image;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $detail_text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $update_at;

    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    private $active;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $category_id;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getPriceOld(): ?int
    {
        return $this->price_old;
    }

    public function setPriceOld(int $price_old): static
    {
        $this->price_old = $price_old;

        return $this;
    }

    public function getDetailText(): ?string
    {
        return $this->detail_text;
    }

    public function setDetailText(?string $detail_text): static
    {
        $this->detail_text = $detail_text;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): static
    {
        $this->create_at = $create_at;

        return $this;
    }
    public function setCreateAtValue()
    {
        $this->create_at = new \DateTime();
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->update_at;
    }
    public function setUpdateAtValue()
    {
        $this->update_at = new \DateTime();
    }

    public function setUpdateAt(\DateTimeInterface $update_at): static
    {
        $this->update_at = $update_at;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getCategoryId(): ?int
    {
        return $this->category_id;
    }

    public function setCategoryId(Category $category): static
    {
        $this->category_id = $category->getId();

        return $this;
    }

    public function getCategoryObject(): Category
    {
        return $this->category_id;
    }

    public function setCategoryObject(Category $category)
    {
        $this->category_id = $category;

        return $this;
    }
}
