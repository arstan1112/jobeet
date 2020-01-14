<?php


namespace App\EventSubscriber;

use App\Entity\BlogImpressions;
use App\Entity\BlogTopic;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class BlogImpressionSubscriber implements EventSubscriber
{

    /**
     * @var LifecycleEventArgs
     */
    private $args;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
//        $this->args = $args;
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return [
          Events::prePersist,
          Events::preRemove,
          Events::postUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $impressions = $args->getEntity();

        if (!$impressions instanceof BlogImpressions) {
            return;
        }

        $topic = $impressions->getBlogTopic();
        if ($topic->getLikes()) {
            $likes = $topic->getLikes();
        } else {
            $likes = 0;
        }

        if ($topic->getDislikes()) {
            $dislikes = $topic->getDislikes();
        } else {
            $dislikes = 0;
        }

        if ($impressions->getType() == 1) {
            $topic->setLikes($likes+1);
        } elseif ($impressions->getType() == 2) {
            $topic->setDislikes($dislikes+1);
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $impressions = $args->getEntity();

        if (!$impressions instanceof BlogImpressions) {
            return;
        }

        $topic = $impressions->getBlogTopic();

        if ($impressions->getType() == 1) {
            $topic->setLikes($topic->getLikes()+1);
            $topic->setDislikes($topic->getDislikes()-1);
        } elseif ($impressions->getType() == 2) {
            $topic->setDislikes($topic->getDislikes()+1);
            $topic->setLikes($topic->getLikes()-1);
        }
        $this->em->flush();
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $impressions = $args->getEntity();

        if (!$impressions instanceof BlogImpressions) {
            return;
        }

        $topic = $impressions->getBlogTopic();

        if ($impressions->getType() == 1) {
            $topic->setLikes($topic->getLikes()-1);
        } elseif ($impressions->getType() == 2) {
            $topic->setDislikes($topic->getDislikes()-1);
        }
    }
}
