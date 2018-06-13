<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PaylandsBundle\Tests\Services;

use PaymentSuite\PaylandsBundle\Event\PaylandsCardValidEvent;
use PaymentSuite\PaylandsBundle\PaylandsMethod;
use PaymentSuite\PaylandsBundle\Services\PaylandsEventDispatcher;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class PaylandsEventDispatcherTest
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class PaylandsEventDispatcherTest extends TestCase
{
    /**
     * @test
     */
    public function notifyCardValid()
    {
        $paylandsMethod = new PaylandsMethod();

        /** @var EventDispatcherInterface|ObjectProphecy $eventDispatcher */
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);

        $isEventValid = function (PaylandsCardValidEvent $event) use ($paylandsMethod) {
            $this->assertSame($paylandsMethod, $event->getPaymentMethod());

            return true;
        };

        $eventDispatcher
            ->dispatch('paylands.card_valid', Argument::that($isEventValid))
            ->shouldBeCalled();

        $dispatcher = new PaylandsEventDispatcher($eventDispatcher->reveal());

        $dispatcher->notifyCardValid($paylandsMethod);
    }
}
