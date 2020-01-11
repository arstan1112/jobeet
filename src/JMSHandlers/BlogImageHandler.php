<?php

/*
 * This handler is used to serialize BlogTopic image data for API calls
 */

namespace App\JMSHandlers;

use App\Entity\BlogImage;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BlogImageHandler implements SubscribingHandlerInterface
{

    /**
     * @var ParameterBagInterface
     */
    private $bag;

    /**
     * BlogImageHandler constructor.
     * @param ParameterBagInterface  $bag
     */
    public function __construct(ParameterBagInterface $bag)
    {
        $this->bag = $bag;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format'    => 'json',
                'type'      => 'BlogImages',
                'method'    => 'serializeMix',
            ]
        ];
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param                          $images
     * @param array                    $type
     *
     * @return BlogImage|array
     */
    public function serializeMix(JsonSerializationVisitor $visitor, $images, array $type)
    {
        $data = [];
        foreach ($images as $image) {
            $data[] = $this->bag->get('domain').$this->bag->get('blog_web_directory').'/'.$image->getName();
        }

        return $data;
    }
}
