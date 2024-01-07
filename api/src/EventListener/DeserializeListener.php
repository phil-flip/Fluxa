<?php

namespace App\EventListener;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Transformer\TransformerChain;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::VIEW, priority: EventPriorities::PRE_WRITE)]
readonly final class DeserializeListener
{
    public function __construct(private TransformerChain $transformer)
    {
    }

    public function __invoke(ViewEvent $event)
    {
//        $event->setControllerResult(
//            $this->transformer->transform($event->getControllerResult())
//        );
    }
}