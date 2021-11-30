<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use DateTime;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Workflow\Event\Event;

class WorkFlowAdvertSubscriber implements EventSubscriberInterface
{
    public function __construct(private NotifierInterface $notifier)
    {
    }
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
        $advert->setPublishAt(new \DateTimeImmutable());



        $notification = (new Notification('Advert publish', ['email']))->content('L\'annonce a étais validé');
        $recipient = new Recipient($advert->getEmail());
        $this->notifier->send($notification, $recipient);
    }

    public function unsetPublishedAt(Event $event): void
    {
        /** @var advert $advert*/
        $advert = $event->getSubject();
        $advert->setPublishAt(null);
    }
}
