<?php

namespace App\DataFixtures;

//use App\Entity\BlogComment;
use App\Entity\BlogTopic;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class BlogTopicFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     * @throws Exception
     */
    public function load(ObjectManager $manager) : void
    {
        $jobMarket = new BlogTopic();
        $jobMarket->setName('Job_market');
//        $jobMarket->setAuthor($manager->getRepository(user));
//        $jobMarket->setAuthor($manager->merge($this->getReference('user')));
        $jobMarket->setAuthor($manager->getRepository(User::class)->find(7));
        $jobMarket->setCreatedAt(new \DateTime());

        $manager->persist($jobMarket);

        $manager->flush();

//        $this->addReference('blog-topic', $jobMarket);
    }
}
