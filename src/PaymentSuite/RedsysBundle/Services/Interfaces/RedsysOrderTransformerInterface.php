<?php

namespace PaymentSuite\RedsysBundle\Services\Interfaces;

interface RedsysOrderTransformerInterface
{
    public function transform(int $orderId): string;

    public function reverseTransform(string $dsOrder): int;
}
