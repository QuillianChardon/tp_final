<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class WorkFlowAdvertSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.advert.enter.published' => 'setPublishedAt',
            'workflow.advert.enter.rejected' => 'unsetPublishedAt',
        ];
    }
    public function setPublishedAt(Event $event): void
    {
        /** @var advert $advert*/
        $advert = $event->getSubject();
        $advert->setPublishAt(new DateTime());
    }

    public function unsetPublishedAt(Event $event): void
    {
        /** @var advert $advert*/
        $advert = $event->getSubject();
        $advert->setPublishAt(null);
    }
}
