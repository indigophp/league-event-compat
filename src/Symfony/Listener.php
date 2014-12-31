<?php

/*
 * This file is part of the Indigo Event Compatibility package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Event\Compat\Symfony;

use League\Event\CallbackListener;
use League\Event\EventInterface;

/**
 * Wrapper around Symfony callable listeners
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Listener extends CallbackListener
{
    /**
     * {@inheritdoc}
     */
    public function handle(EventInterface $event)
    {
        if (!$event instanceof Event) {
            throw new \RuntimeException('This listener can only handle Symfony Events');
        }

        call_user_func($this->callback, $event->getEvent(), $event->getName(), $event->getEvent()->getDispatcher());
    }

    /**
     * {@inheritdoc}
     */
    public function isListener($listener)
    {
        if ($listener instanceof Listener) {
            $listener = $listener->getCallback();
        }

        return $this->callback === $listener;
    }
}
