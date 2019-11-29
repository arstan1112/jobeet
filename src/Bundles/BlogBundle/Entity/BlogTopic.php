<?php


namespace App\Bundles\BlogBundle\Entity;

use App\Entity\BlogComment;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogTopicRepository")
 */
class BlogTopic
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="blogTopic", cascade={"persist", "persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogComment", mappedBy="blogTopic", cascade={"persist", "remove"})
     */
    private $blogComment;

    public function __construct()
    {
        $this->blogComment = new ArrayCollection();
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|BlogComment[]
     */
//    public function getBlogComments(): ?BlogComment
    public function getBlogComment(): Collection
    {
        return $this->blogComment;
    }

    /**
     * @param BlogComment $blogComment
     * @return $this
     */
    public function addBlogComments(BlogComment $blogComment): self
    {
        if (!$this->blogComment->contains($blogComment)) {
            $this->blogComment[]=$blogComment;
            $blogComment->setBlogTopic($this);
        }

        return $this;
    }

    /**
     * @param BlogComment $blogComment
     * @return $this
     */
    public function removeBlogComments(BlogComment $blogComment): self
    {
        if ($this->blogComment->contains($blogComment)) {
            $this->blogComment->removeElement($blogComment);
            if ($blogComment->getBlogTopic()===$this) {
                $blogComment->setBlogTopic(null);
            }
        }

        return $this;
    }

//    public function setBlogComments(BlogComment $blogComments): self
//    {
//        $this->blogComments = $blogComments;
//
//        // set the owning side of the relation if necessary
//        if ($blogComments->getBlogTopic() !== $this) {
//            $blogComments->setBlogTopic($this);
//        }
//
//        return $this;
//    }
}