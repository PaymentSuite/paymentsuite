<?php

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\RedsysBundle\Services\Interfaces\RedsysOrderTransformerInterface;

class RedsysOrderTransformer implements RedsysOrderTransformerInterface
{
    public function transform($orderId)
    {
        $orderNumber = (string) $orderId;

        $length = strlen($orderNumber);
        $minLength = 4;

        if ($length < $minLength) {
            $orderNumber = str_pad($orderNumber, $minLength, '0', STR_PAD_LEFT);
        }

        $orderNumber .= 'T'.strrev(time());
        $orderNumber = substr($orderNumber, 0, 12);

        return $orderNumber;
    }

    public function reverseTransform($dsOrder)
    {
        $chunks = explode('T', $dsOrder);

        return (int) $chunks[0];
    }


}
