<?php

namespace PaymentSuite\RedsysBundle\Services\Interfaces;

interface RedsysOrderTransformerInterface
{
    public function transform($orderId);

    public function reverseTransform($dsOrder);
}
