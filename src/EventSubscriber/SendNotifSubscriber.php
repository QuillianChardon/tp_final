<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Advert;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

class SendNotifSubscriber implements EventSubscriberInterface
{

    public function __construct(private NotifierInterface $notifier)
    {
    }
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendNotification', EventPriorities::POST_WRITE]
        ];
    }

    public function sendNotification(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();
        $method = $entity->getRequest()->getMethod();

        if (Request::METHOD_POST !== $method  || !$entity instanceof Advert) {
            return;
        }
        $this->notifier->send((new Notification('Advert', ['email']))->content('l\'advert a était confirmé'));
    }
}
