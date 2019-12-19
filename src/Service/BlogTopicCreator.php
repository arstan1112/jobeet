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
    private $hashTagChecker;

    /**
     * BlogTopicCreator constructor.
     * @param EntityManagerInterface $em
     * @param BlogHashTagChecker     $hashTagChecker
     */
    public function __construct(EntityManagerInterface $em, BlogHashTagChecker $hashTagChecker)
    {
        $this->em             = $em;
        $this->hashTagChecker = $hashTagChecker;
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

        $content = strip_tags($topic->getText());
        $content = str_replace("\n", "", $content);
        $content = str_replace("\r", "", $content);
        $content = preg_replace("/&nbsp;/", '', $content);
        $api          = new TextRankFacade();
        $summaryArray = $api->summarizeTextBasic($content);
        $summary      = implode("", $summaryArray);

        $topic->setAuthor($user);
        $topic->setSummary($summary);

        $this->em->persist($topic);
        $this->em->flush();
//        throw new \Exception('testerror');
    }
}
