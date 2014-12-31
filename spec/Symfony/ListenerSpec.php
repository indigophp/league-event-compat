<?php

namespace spec\League\Event\Compat\Symfony;

use League\Event\EventInterface;
use PhpSpec\ObjectBehavior;

class ListenerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(function() {});
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('League\Event\Compat\Symfony\Listener');
        $this->shouldHaveType('League\Event\CallbackListener');
    }

    function it_should_throw_an_exception_when_invalid_event_passed(EventInterface $event)
    {
        $this->shouldThrow('RuntimeException')->duringHandle($event);
    }
}
