<?php


namespace App\Service;

use App\Entity\BlogTopic;
use App\Entity\BlogTopicHashTag;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class BlogTopicCreator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var BlogHashTagChecker
     */
    private $hashTagChecker;

    /**
     * @var TextSummarizer
     */
    private $summarizer;

    /**
     * @param EntityManagerInterface $em
     * @param BlogHashTagChecker     $hashTagChecker
     * @param TextSummarizer         $summarizer
     */
    public function __construct(
        EntityManagerInterface $em,
        BlogHashTagChecker     $hashTagChecker,
        TextSummarizer         $summarizer
    ) {
        $this->em             = $em;
        $this->hashTagChecker = $hashTagChecker;
        $this->summarizer     = $summarizer;
    }

    /**
     * @param BlogTopic $topic
     * @param User      $user
     *
     * @throws NonUniqueResultException
     */
    public function create(BlogTopic $topic, User $user)
    {
        $hashTags    = $topic->getHash();
        $checkedTags = $this->hashTagChecker->hashTagExist($hashTags);

        foreach ($checkedTags[0] as $newTag) {
            $hashTagObj = new BlogTopicHashTag();
            $hashTagObj->setName($newTag);
            $hashTagObj->setCreatedAt(new \DateTime());

            $topic->addBlogTopicHashTag($hashTagObj);
        };
        foreach ($checkedTags[1] as $existedTag) {
            $topic->addBlogTopicHashTag($existedTag);
        };

        $summary = $this->summarizer->summarize($topic);

        $topic->setAuthor($user);
        $topic->setSummary($summary);

        $this->em->persist($topic);
        $this->em->flush();
    }
}
