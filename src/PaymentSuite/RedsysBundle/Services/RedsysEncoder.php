<?php

namespace PaymentSuite\RedsysBundle\Services;

/**
 * Class RedsysEncoder.
 */
final class RedsysEncoder
{
    public static function encode($params)
    {
        return base64_encode(json_encode($params));
    }

    public static function decode($params)
    {
        return json_decode(base64_decode(self::normalize($params)), true);
    }

    public static function normalize($value)
    {
        return strtr($value, '-_', '+/');
    }
}
