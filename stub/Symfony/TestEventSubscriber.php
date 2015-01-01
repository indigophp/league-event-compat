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
class TestEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'pre.foo'  => 'preFoo',
            'post.foo' => 'postFoo',
        ];
    }

    public function preFoo()
    {
    }

    public function postFoo()
    {
    }
}
