<?php

namespace spec\League\Event\Compat\Symfony;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;
use PhpSpec\ObjectBehavior;

class EventSpec extends ObjectBehavior
{
    function let(SymfonyEvent $event)
    {
        $this->beConstructedWith($event);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('League\Event\Compat\Symfony\Event');
        $this->shouldHaveType('League\Event\EventInterface');
    }

    function it_should_allow_to_stop_propagation(SymfonyEvent $event)
    {
        $event->isPropagationStopped()->willReturn(false);
        $event->stopPropagation()->will(function($args) {
            $this->isPropagationStopped()->willReturn(true);
        });

        $this->isPropagationStopped()->shouldReturn(false);

        $this->stopPropagation();

        $this->isPropagationStopped()->shouldReturn(true);
    }
}
