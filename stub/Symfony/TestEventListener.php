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

use Symfony\Component\EventDispatcher\Event;

/**
 * Copied from Symfony EventDispatcher test suite
 */
class TestEventListener
{
    /* Listener methods */

    public function preFoo(Event $e)
    {
    }

    public function postFoo(Event $e)
    {
    }
}
