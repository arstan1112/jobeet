<?php

namespace App\EventSubscriber;

use App\Entity\Visits;
use App\EventListener\Event\VisitCreatedEvent;
use App\Repository\VisitsRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class VisitSubscriber implements EventSubscriberInterface
{
    /**
     * @var VisitsRepository
     */
    private $visits;

    public function __construct(VisitsRepository $visits)
    {
        $this->visits = $visits;
    }

    public function onVisitCreated(VisitCreatedEvent $event)
    {
        $visit = new Visits();
        $visit->setCount(
            $visit->getCount() + 1
        );

        $visit->setPage($event->getPage());

        $this->visits->save($visit);
    }

    public function onResponseCreated(ResponseEvent $event)
    {
        $page = $event->getRequest()->attributes->get('_route');
        $visit = new Visits();
        $visit->setCount(
            $visit->getCount() + 1
        );

        $visit->setPage($page);

        $this->visits->save($visit);
    }

    public static function getSubscribedEvents()
    {
        return [
//            VisitCreatedEvent::class => 'onVisitCreated',
            KernelEvents::RESPONSE => 'onResponseCreated'
        ];
    }
}
