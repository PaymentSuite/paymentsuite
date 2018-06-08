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

namespace PaymentSuite\PaylandsBundle\Services;

use PaymentSuite\PaylandsBundle\Event\PaylandsCardValidEvent;
use PaymentSuite\PaylandsBundle\PaylandsEvents;
use PaymentSuite\PaylandsBundle\PaylandsMethod;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class PaylandsEventDispatcher
 *
 * @author WAM Team <develop@wearemarketing.com>
 */
class PaylandsEventDispatcher
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * PaylandsEventDispatcher constructor.
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function notifyCardValid(PaylandsMethod $paylandsMethod)
    {
        $event = new PaylandsCardValidEvent($paylandsMethod);

        $this->eventDispatcher->dispatch(PaylandsEvents::CARD_VALID, $event);
    }
}