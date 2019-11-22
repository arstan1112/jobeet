<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BasuUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User extends BasuUser
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\BlogTopic", mappedBy="author", cascade={"persist", "remove"})
     */
    private $blogTopic;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\BlogComment", mappedBy="UserId", cascade={"persist", "remove"})
     */
    private $blogComment;

    public function getBlogTopic(): ?BlogTopic
    {
        return $this->blogTopic;
    }

    public function setBlogTopic(BlogTopic $blogTopic): self
    {
        $this->blogTopic = $blogTopic;

        // set the owning side of the relation if necessary
        if ($blogTopic->getAuthor() !== $this) {
            $blogTopic->setAuthor($this);
        }

        return $this;
    }

    public function getBlogComment(): ?BlogComment
    {
        return $this->blogComment;
    }

    public function setBlogComment(BlogComment $blogComment): self
    {
        $this->blogComment = $blogComment;

        // set the owning side of the relation if necessary
        if ($blogComment->getUser() !== $this) {
            $blogComment->setUser($this);
        }

        return $this;
    }
}