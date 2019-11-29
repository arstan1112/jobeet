<?php

namespace App\DataFixtures;

use App\Entity\BlogComment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Exception;

class BlogCommentFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        // TODO: Implement load() method.
        $comment = new BlogComment();
        $comment->setText('Lorem ipsum ...');
        $comment->setBlogTopic($manager->merge($this->getReference('blogJobMarket')));
        $comment->setUser($manager->merge($this->getReference('user')));
        $comment->setCreatedAt(new \DateTime());

        $manager->persist($comment);
        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
        return [
            BlogTopicFixtures::class,
            UserFixtures::class,
        ];
    }
}