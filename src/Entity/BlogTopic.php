<?php

namespace App\Entity;

use App\Utils\HashTagsNormalizer;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlogTopicRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BlogTopic
{
    const HASH_TAGS_LIMIT      = 3;
    const HASH_TAGS_CHAR_LIMIT = 10;

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
     * @ORM\OrderBy({"createdAt"="DESC"})
     */
    private $blogComment;

    /**
     * @var
     * @ORM\Column(type="text")
     */
    private $summary;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\BlogTopicHashTag", inversedBy="blogTopics", cascade={"persist"})
     */
    private $blogTopicHashTags;

    /**
     * @var
     * @Assert\NotBlank(message="Hash tag connot be blank")
     */
    private $hash;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlogImage", mappedBy="topicId", orphanRemoval=true, cascade={"persist"})
     */
    private $blogImages;

    public function __construct()
    {
        $this->blogComment       = new ArrayCollection();
        $this->blogTopicHashTags = new ArrayCollection();
        $this->blogImages        = new ArrayCollection();
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

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return Collection|BlogTopicHashTag[]
     */
    public function getBlogTopicHashTags(): Collection
    {
        return $this->blogTopicHashTags;
    }

    public function addBlogTopicHashTag(BlogTopicHashTag $blogTopicHashTag): self
    {
        if (!$this->blogTopicHashTags->contains($blogTopicHashTag)) {
            $this->blogTopicHashTags[] = $blogTopicHashTag;
            $blogTopicHashTag->addBlogTopic($this);
//            $blogTopicHashTag->setBlogTopic($this);
        }

        return $this;
    }

    public function removeBlogTopicHashTag(BlogTopicHashTag $blogTopicHashTag): self
    {
        if ($this->blogTopicHashTags->contains($blogTopicHashTag)) {
            $this->blogTopicHashTags->removeElement($blogTopicHashTag);
            $blogTopicHashTag->removeBlogTopic($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash): void
    {
        $this->hash = $hash;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();

//        $hash   = $this->getHash();
//        $hashes = preg_split('[#]', $hash);
//        array_shift($hashes);
//        foreach ($hashes as $hash) {
//            $hashTag = new BlogTopicHashTag();
//            $hashTag->setName($hash);
//            $hashTag->setCreatedAt(new \DateTime());
//            $this->addBlogTopicHashTag($hashTag);
//        }

//        $hashTag = new BlogTopicHashTag();
//        $hashTag->setName($this->getHash());
//        $hashTag->setCreatedAt(new \DateTime());
//        $this->addBlogTopicHashTag($hashTag);
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
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

    /**
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback()
     */
    public function validateHashTags(ExecutionContextInterface $context)
    {
        $hashes = HashTagsNormalizer::normalizeArray($this->hash);
        if (count($hashes) > BlogTopic::HASH_TAGS_LIMIT) {
            $context
                ->buildViolation("Too many tags. Less than 3 is allowed")
                ->atPath('hash')
                ->addViolation();
        }

        foreach ($hashes as $hash) {
            if (strlen($hash) > BlogTopic::HASH_TAGS_CHAR_LIMIT) {
                $context
                    ->buildViolation("Hash tag length cannot be more than 10 characters")
                    ->atPath('hash')
                    ->addViolation();
            }
        }
    }

    /**
     * @return Collection|BlogImage[]
     */
    public function getBlogImages(): Collection
    {
        return $this->blogImages;
    }

    public function addBlogImage(BlogImage $blogImage): self
    {
        if (!$this->blogImages->contains($blogImage)) {
            $this->blogImages[] = $blogImage;
            $blogImage->setTopicId($this);
        }

        return $this;
    }

    public function removeBlogImage(BlogImage $blogImage): self
    {
        if ($this->blogImages->contains($blogImage)) {
            $this->blogImages->removeElement($blogImage);
            // set the owning side to null (unless already changed)
            if ($blogImage->getTopicId() === $this) {
                $blogImage->setTopicId(null);
            }
        }

        return $this;
    }
}
