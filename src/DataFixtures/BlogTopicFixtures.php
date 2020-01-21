<?php

namespace App\DataFixtures;

use App\Entity\BlogComment;
use App\Entity\BlogTopic;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Repository\UsersRepository;
use Exception;

class BlogTopicFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $jobMarket = new BlogTopic();
        $jobMarket->setName('Job_market');
//        $jobMarket->setAuthor($manager->getRepository(user));
//        $jobMarket->setAuthor($manager->merge($this->getReference('user')));
//        $jobMarket->setAuthor($manager->getRepository(User::class)->find(7));
        $jobMarket->setAuthor($manager->merge($this->getReference('user')));
        $jobMarket->setCreatedAt(new \DateTime());
        $jobMarket->setUpdatedAt(new \DateTime());

        $manager->persist($jobMarket);

        $manager->flush();

        $this->addReference('blogJobMarket', $jobMarket);
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        // TODO: Implement dependencies method
        return [
            UserFixtures::class,
        ];
    }
}
