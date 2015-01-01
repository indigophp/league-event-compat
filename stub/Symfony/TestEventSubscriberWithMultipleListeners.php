<?php

/*
 * This file is part of the Indigo Event Compatibility package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\Event\Compat\Stub\Symfony;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Copied from Symfony EventDispatcher test suite
 */
class TestEventSubscriberWithMultipleListeners implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'pre.foo' => [
                ['preFoo1'],
                ['preFoo2', 10],
            ],
        ];
    }

    public function preFoo1()
    {
    }

    public function preFoo2()
    {
    }
}
