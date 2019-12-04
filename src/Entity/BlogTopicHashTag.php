<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogTopicHashTagRepository")
 */
class BlogTopicHashTag
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\BlogTopic", mappedBy="blogTopicHashTags", cascade={"persist"})
     */
    private $blogTopics;

    public function __construct()
    {
        $this->blogTopics = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|BlogTopic[]
     */
    public function getBlogTopics(): Collection
    {
        return $this->blogTopics;
    }

    public function addBlogTopic(BlogTopic $blogTopic): self
    {
        if (!$this->blogTopics->contains($blogTopic)) {
            $this->blogTopics[] = $blogTopic;
        }

        return $this;
    }

    public function removeBlogTopic(BlogTopic $blogTopic): self
    {
        if ($this->blogTopics->contains($blogTopic)) {
            $this->blogTopics->removeElement($blogTopic);
        }

        return $this;
    }

//    public function setBlogTopic(?BlogTopic $blogTopic): self
//    {
//        $this->blogTopic = $blogTopic;
//
//        return $this;
//    }
}
