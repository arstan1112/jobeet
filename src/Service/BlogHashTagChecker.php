<?php


namespace App\Service;

use App\Entity\BlogTopicHashTag;
use App\Utils\HashTagsNormalizer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use phpDocumentor\Reflection\Types\Mixed_;

class BlogHashTagChecker
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
     * @param array $hashTag
     * @return array
     * @throws NonUniqueResultException
     */

//    public function hashTagExist(mixed $hashTag) : array
    public function hashTagExist(array $hashTag) : array
    {
//        $hashes = HashTagsNormalizer::normalize($hashTag);
        $hashes = HashTagsNormalizer::normalizeArray($hashTag);
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
