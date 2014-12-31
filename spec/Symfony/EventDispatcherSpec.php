<?php

namespace spec\League\Event\Compat\Symfony;

use League\Event\Compat\Stub\Symfony\TestEventListener;
use Symfony\Component\EventDispatcher\Event as SymfonyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventDispatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('League\Event\Compat\Symfony\EventDispatcher');
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventDispatcherInterface');
    }

    function it_should_have_no_listeners_by_default()
    {
        $this->getListeners()->shouldReturn([]);
    }

    function it_should_allow_to_add_listeners(TestEventListener $listener)
    {
        $this->addListener('pre.foo', [$listener, 'preFoo']);
        $this->addListener('post.foo', [$listener, 'postFoo']);

        $this->hasListeners('pre.foo')->shouldReturn(true);
        $this->hasListeners('post.foo')->shouldReturn(true);
        $this->hasListeners()->shouldReturn(true);

        $this->getListeners('pre.foo')->shouldHaveCount(1);
        $this->getListeners('post.foo')->shouldHaveCount(1);
        $this->getListeners()->shouldHaveCount(2);
    }

    function it_should_return_listeners_sort_by_priority(TestEventListener $listener1, TestEventListener $listener2, TestEventListener $listener3)
    {
        $this->addListener('pre.foo', [$listener1, 'preFoo'], -10);
        $this->addListener('pre.foo', [$listener2, 'preFoo'], 10);
        $this->addListener('pre.foo', [$listener3, 'preFoo']);

        $expected = [
            [$listener2, 'preFoo'],
            [$listener3, 'preFoo'],
            [$listener1, 'preFoo'],
        ];

        $this->getListeners('pre.foo')->shouldReturn($expected);
    }

    function it_should_allow_to_dispatch_events(TestEventListener $listener)
    {
        $eventType = Argument::type('Symfony\Component\EventDispatcher\Event');
        $dispatcherType = Argument::type('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $listener->preFoo($eventType, 'pre.foo', $dispatcherType)->shouldBeCalled();
        $listener->postFoo($eventType, 'post.foo', $dispatcherType)->shouldNotBeCalled();

        $this->addListener('pre.foo', [$listener, 'preFoo']);
        $this->addListener('post.foo', [$listener, 'postFoo']);

        $this->dispatch('pre.foo')->shouldHaveType('Symfony\Component\EventDispatcher\Event');
    }

    function it_should_allow_to_dispatch_unknown_events()
    {
        $this->dispatch('unknown')->shouldHaveType('Symfony\Component\EventDispatcher\Event');
    }

    function it_should_allow_to_dispatch_an_event_object(SymfonyEvent $event)
    {
        $event->setName(Argument::type('string'))->will(function($args) {
            $this->getName()->willReturn($args[0]);
        });
        $event->setDispatcher(Argument::type('Symfony\Component\EventDispatcher\EventDispatcherInterface'))->will(function($args) {
            $this->getDispatcher()->willReturn($args[0]);
        });

        $this->dispatch('pre.foo', $event)->shouldReturn($event);
    }

    function it_should_allow_to_stop_propagation(SymfonyEvent $event, TestEventListener $listener, TestEventListener $otherListener)
    {
        $dispatcherType = Argument::type('Symfony\Component\EventDispatcher\EventDispatcherInterface');

        $event->setName(Argument::type('string'))->will(function($args) {
            $this->getName()->willReturn($args[0]);
        });
        $event->setDispatcher($dispatcherType)->will(function($args) {
            $this->getDispatcher()->willReturn($args[0]);
        });
        $event->isPropagationStopped()->willReturn(false);
        $event->stopPropagation()->will(function($args) {
            $this->isPropagationStopped()->willReturn(true);
        });

        $listener->postFoo($event, 'post.foo', $dispatcherType)->will(function($args) {
            $args[0]->stopPropagation();
        })->shouldBeCalled();
        $otherListener->postFoo($event, 'post.foo', $dispatcherType)->shouldNotBeCalled();

        $this->addListener('post.foo', [$listener, 'postFoo'], 10);
        $this->addListener('post.foo', [$otherListener, 'postFoo']);

        $this->dispatch('post.foo', $event);
    }

    function it_should_allow_to_remove_a_listener(TestEventListener $listener)
    {
        $this->addListener('pre.foo', [$listener, 'preFoo']);
        $this->hasListeners('pre.foo')->shouldReturn(true);
        $this->removeListener('pre.foo', [$listener, 'preFoo']);
        $this->hasListeners('pre.foo')->shouldReturn(false);
    }

    /**
     * It does not work and fails with the following message:
     *
     * Using $this when not in object context in PATH/vendor/phpspec/prophecy/src/Prophecy/Doubler/Generator/ClassCreator.php(49) : eval()'d code on line 6
     */
    function it_should_allow_to_add_a_subscriber(EventSubscriberInterface $subscriber)
    {
        // $subscriber->getSubscribedEvents()->willReturn([
        //     'pre.foo'  => 'preFoo',
        //     'post.foo' => 'postFoo',
        // ]);

        // $this->addSubscriber($subscriber);

        // $this->hasListeners('pre.foo')->shouldReturn(true);
        // $this->hasListeners('post.foo')->shouldReturn(true);
    }
}
