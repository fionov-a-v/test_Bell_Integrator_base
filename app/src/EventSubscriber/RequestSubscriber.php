<?php

namespace App\EventSubscriber;

use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestSubscriber implements EventSubscriberInterface
{
    private $translatableListener;

    public function __construct(TranslatableListener $translatableListener)
    {
        $this->translatableListener = $translatableListener;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $this->translatableListener->setTranslatableLocale($request->getLocale());
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
