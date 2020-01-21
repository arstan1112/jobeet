<?php

namespace App\EventSubscriber;

use App\Controller\VisitInterface;
use App\Entity\Visits;
use App\EventListener\Event\VisitCreatedEvent;
use App\Repository\VisitsRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class VisitSubscriber implements EventSubscriberInterface
{
    /**
     * @var VisitsRepository
     */
    private $visits;

    /**
     * @param VisitsRepository $visits
     */
    public function __construct(VisitsRepository $visits)
    {
        $this->visits = $visits;
    }

    /**
     * @param VisitCreatedEvent $event
     */
    public function onVisitCreated(VisitCreatedEvent $event)
    {
        dump($event);
        die;

        $visit = new Visits();
        $visit->setCount(
            $visit->getCount() + 1
        );

        $visit->setPage($event->getPage());

        $this->visits->save($visit);
    }

    /**
     * @param ControllerEvent $event
     */
    public function onResponseCreated(ControllerEvent $event)
    {
        $controller = $event->getController()[0];
        if (! ($controller instanceof VisitInterface)) {
            return;
        }

        $page = $event->getRequest()->attributes->get('_route');
        $visit = new Visits();
        $visit->setCount(
            $visit->getCount() + 1
        );

        $visit->setPage($page);

        $this->visits->save($visit);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
//            VisitCreatedEvent::class => 'onVisitCreated',
            KernelEvents::CONTROLLER => 'onResponseCreated'
        ];
    }
}
