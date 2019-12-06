<?php


namespace App\Service;

use App\Entity\BlogTopic;
use App\Entity\BlogTopicHashTag;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use PhpScience\TextRank\TextRankFacade;
use PhpScience\TextRank\Tool\StopWords\English;

class BlogTopicCreator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var BlogHashTagChecker
     */
    private $hashTagService;

    /**
     * BlogTopicCreator constructor.
     * @param EntityManagerInterface $em
     * @param BlogHashTagChecker     $hashTagService
     */
    public function __construct(EntityManagerInterface $em, BlogHashTagChecker $hashTagService)
    {
        $this->em             = $em;
        $this->hashTagService = $hashTagService;
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
        $checkedTags = $this->hashTagService->hashTagExist($hashTags);

        foreach ($checkedTags[0] as $newTag) {
            $hashTagObj = new BlogTopicHashTag();
            $hashTagObj->setName($newTag);
            $hashTagObj->setCreatedAt(new \DateTime());
            $topic->addBlogTopicHashTag($hashTagObj);
        };
        foreach ($checkedTags[1] as $existedTag) {
            $topic->addBlogTopicHashTag($existedTag);
        };

        // 1 2 3
        // 1 2 3 4
        // 1 4 3

        $text         = $topic->getText();
        $api          = new TextRankFacade();
        $summaryArray = $api->summarizeTextBasic($text);
        $summary      = implode("", $summaryArray);

        $topic->setAuthor($user);
        $topic->setSummary($summary);

        $this->em->persist($topic);
        $this->em->flush();
//        throw new \Exception('testerror');
    }
}
