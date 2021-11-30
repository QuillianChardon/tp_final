<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Advert;
use App\Repository\AdminUserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class SendNotificationEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private NotifierInterface $notifier, private AdminUserRepository $repository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendNotification', EventPriorities::POST_WRITE],
        ];
    }


    public function sendNotification(ViewEvent $event): void
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (Request::METHOD_POST !== $method || !$entity instanceof Advert) {
            return;
        }

        $notification = (new Notification('Advert posted', ['email']))->content('Une nouvelle annonce est disponible sur le site');
        $adminUsers = $this->repository->findAll();
        foreach ($adminUsers as $adminUser) {
            $recipient = new Recipient($adminUser->getEmail());
            $this->notifier->send($notification, $recipient);
        }
    }
}
