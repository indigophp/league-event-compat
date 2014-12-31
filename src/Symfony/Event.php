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

use League\Event\AbstractEvent;
use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

/**
 * Wrapper around Symfony Event
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Event extends AbstractEvent
{
    /**
     * @var SymfonyEvent
     */
    protected $event;

    /**
     * @param string $name
     * @param SymfonyEvent  $event
     */
    public function __construct(SymfonyEvent $event)
    {
        $this->event = $event;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->event->getName();
    }

    /**
     * Returns the wrapped event
     *
     * @return SymfonyEvent
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * {@inheritdoc}
     */
    public function stopPropagation()
    {
        $this->event->stopPropagation();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped()
    {
        return $this->event->isPropagationStopped();
    }
}
