<?php


namespace App\JMSHandlers;

use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializerInterface;
use phpDocumentor\Reflection\Types\Context as Context;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class JobCategoryHandler
 * @package App\JMSHandlers
 */
class JobCategoryHandler implements SubscribingHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'Category',
                'method'    => 'serializeMix',
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format'    => 'json',
                'type'      => 'Category',
                'method'    => 'deserializeMix',
            ],
        ];
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param $category
     * @param array $type
     *
     * @return Categories|array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function serializeMix(JsonSerializationVisitor $visitor, $category, array $type)
    {
        return [
            'id' => $category->getId(),
        ];
    }

    /**
     * @param JsonDeserializationVisitor $visitor
     * @param $categoryId
     * @param array $type
     *
     * @return int|Categories
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function deserializeMix(JsonDeserializationVisitor $visitor, $categoryId, array $type)
    {
        return $this->em->getRepository(Categories::class)->find($categoryId);
    }
}
