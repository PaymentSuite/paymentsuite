<?php

namespace PaymentSuite\PaylandsBundle\Services\Interfaces;

use PaymentSuite\PaylandsBundle\Util\ExtraData;

interface ExtraDataBuilderAwareInterface
{
    public function buildExtraData(): ExtraData;
}
