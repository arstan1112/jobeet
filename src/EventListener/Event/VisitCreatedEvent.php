<?php


namespace App\EventListener\Event;

use Symfony\Contracts\EventDispatcher\Event;

class VisitCreatedEvent extends Event
{
    protected $page;

    public function __construct(string $page)
    {
        $this->page = $page;
    }

    public function getPage()
    {
        return $this->page;
    }
}
