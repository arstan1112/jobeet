<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Entity\Category;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AffiliatesRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Affiliates
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $token;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Categories[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Categories", inversedBy="affiliates")
     * @ORM\JoinTable(name="affiliates_categories")
     */
    private $categories;

//    /**
//     * @ORM\ManyToMany(targetEntity="App\Entity\Categories")
//     * @ORM\JoinTable(name="affiliates_categories")
//     */
//    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive() : ?bool
    {
        return $this->active;
    }

//    public function getActive(): ?bool
//    {
//        return $this->active;
//    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

//    public function setCreatedAt(\DateTimeInterface $createdAt): self
//    {
//        $this->createdAt = $createdAt;
//
//        return $this;
//    }

    /**
//     * @return Collection|Categories[]
     * @return Categories[]|ArrayCollection
     */
//    public function getCategories(): Collection
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param Categories $category
     * @return self
     */
    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
//            $this->categories[] = $category;
//            $category->addAffiliate($this);
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        $this->categories->removeElement($category);
//        if ($this->categories->contains($category)) {
//            $this->categories->removeElement($category);
//            $category->removeAffiliate($this);
//        }

        return $this;
    }

//    /**
//     * @return Collection|Categories[]
//     */
//    public function getCategories(): Collection
//    {
//        return $this->categories;
//    }

//    public function addCategory(Categories $category): self
//    {
//        if (!$this->categories->contains($category)) {
//            $this->categories[] = $category;
//        }
//
//        return $this;
//    }
//
//    public function removeCategory(Categories $category): self
//    {
//        if ($this->categories->contains($category)) {
//            $this->categories->removeElement($category);
//        }
//
//        return $this;
//    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }
}
