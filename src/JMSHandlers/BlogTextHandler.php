<?php


namespace App\JMSHandlers;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Tests\Serializer\GraphNavigatorTest;

class BlogTextHandler implements SubscribingHandlerInterface
{

    /**
     * @inheritDoc
     */
    public static function getSubscribingMethods()
    {
        return [
          [
              'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
              'format' => 'json',
              'type' => 'BlogText',
              'method' => 'serializeMix',
          ]
        ];
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param                          $text
     * @param array                    $type
     *
     * @return string
     */
    public function serializeMix(JsonSerializationVisitor $visitor, $text, array $type)
    {
        return strip_tags($text);
    }
}
