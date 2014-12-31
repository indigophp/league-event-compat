<?php

/*
 * This file is part of the League Event Compatibility package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Event\Compat\Symfony;

use League\Event\EmitterInterface;
use League\Event\Emitter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event as SymfonyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Wrapper around League EventEmitter
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var EmitterInterface
     */
    protected $emitter;

    /**
     * League EmitterInterface does not provide a way to get every single listener
     *
     * To provide full compatibility we store a list of event names to be able to get all of them
     *
     * @var array
     */
    protected $eventNames = [];

    /**
     * @param EmitterInterface $emitter
     */
    public function __construct(EmitterInterface $emitter = null)
    {
        $this->emitter = $emitter ?: new Emitter;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($eventName, SymfonyEvent $event = null)
    {
        if (is_null($event)) {
            $event = new SymfonyEvent;
        }

        $event->setDispatcher($this);
        $event->setName($eventName);

        $this->emitter->emit(new Event($event));

        return $event;
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners($eventName = null)
    {
        $listeners = [];

        if (is_null($eventName)) {
            foreach (array_keys($this->eventNames) as $eventName) {
                $listeners = array_merge($listeners, $this->emitter->getListeners($eventName));
            }
        } else {
            $listeners = $this->emitter->getListeners($eventName);
        }

        $listeners = array_map(function($listener) {
            if ($listener instanceof Listener) {
                return $listener->getCallback();
            }
        }, $listeners);

        return array_filter($listeners);
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners($eventName = null)
    {
        if (is_null($eventName)) {
            return (bool) count($this->eventNames);
        }

        return $this->emitter->hasListeners($eventName);
    }

    /**
     * {@inheritdoc}
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->eventNames[$eventName] = true;

        $this->emitter->addListener($eventName, new Listener($listener), $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener($eventName, $listener)
    {
        $listener = new Listener($listener);

        $this->emitter->removeListener($eventName, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_string($params)) {
                $this->addListener($eventName, [$subscriber, $params]);
            } elseif (is_string($params[0])) {
                $this->addListener($eventName, [$subscriber, $params[0]], isset($params[1]) ? $params[1] : 0);
            } else {
                foreach ($params as $listener) {
                    $this->addListener($eventName, [$subscriber, $listener[0]], isset($listener[1]) ? $listener[1] : 0);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $params) {
            if (is_array($params) && is_array($params[0])) {
                foreach ($params as $listener) {
                    $this->removeListener($eventName, array($subscriber, $listener[0]));
                }
            } else {
                $this->removeListener($eventName, array($subscriber, is_string($params) ? $params : $params[0]));
            }
        }
    }
}
