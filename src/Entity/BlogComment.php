<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogCommentRepository")
 * @JMS\ExclusionPolicy("all")
 */
class BlogComment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @JMS\Expose()
     * @JMS\Type("string")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BlogTopic", inversedBy="blogComment", cascade={"persist", "persist"})
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id", nullable=false)
     */
    private $blogTopic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="blogComment", cascade={"persist", "persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * @JMS\Expose()
     * @JMS\Type("string")
     * @JMS\SerializedName("author")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Expose()
     * @JMS\Type("DateTime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getBlogTopic(): ?BlogTopic
    {
        return $this->blogTopic;
    }

    public function setBlogTopic(BlogTopic $blogTopic): self
    {
        $this->blogTopic = $blogTopic;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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
}
