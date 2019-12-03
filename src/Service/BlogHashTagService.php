<?php


namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\BlogTopicHashTag;
use Doctrine\ORM\NonUniqueResultException;

class BlogHashTagService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * BlogHashTagService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $hashTag
     * @return array
     * @throws NonUniqueResultException
     */
    public function hashTagExist(string $hashTag) : array
    {
        $hashes = preg_split('[#]', $hashTag);
        array_shift($hashes);
        $checkedTags = [];
        $newTags     = [];
        $existedTags = [];
        foreach ($hashes as $hash) {
            $check = $this->em->getRepository(BlogTopicHashTag::class)->findByName($hash);
            if (!$check) {
                $newTags[]     = $hash;
            } elseif ($check) {
                $existedTags[] = $check;
            }
        }
        $checkedTags[] = $newTags;
        $checkedTags[] = $existedTags;
        return $checkedTags;
    }
}
