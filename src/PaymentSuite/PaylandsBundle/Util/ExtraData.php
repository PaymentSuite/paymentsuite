<?php


namespace PaymentSuite\PaylandsBundle\Util;

use PaymentSuite\PaylandsBundle\Util\Interfaces\ArrayableInterface;

/**
 * @author Juanjo Martínez <jmartinez@wearemarketing.com>
 */
final class ExtraData implements ArrayableInterface
{
    private $profile;

    private $address;

    private $shippingAddress;

    private $billingAddress;

    private function __construct(
        Profile $profile = null,
        Address $address = null,
        Address $shippingAddress = null,
        Address $billingAddress = null
    )
    {
        $this->profile = $profile;
        $this->address = $address;
        $this->shippingAddress = $shippingAddress;
        $this->billingAddress = $billingAddress;
    }

    public static function create(
        Profile $profile = null,
        Address $address = null,
        Address $shippingAddress = null,
        Address $billingAddress = null
    ): self
    {
        return new self($profile, $address, $shippingAddress, $billingAddress);
    }

    public function toArray(): array
    {
        return array_filter([
            'profile' => $this->profile ? $this->profile->toArray() : [],
            'address' => $this->address ? $this->address->toArray() : [],
            'shipping_address' => $this->shippingAddress ? $this->shippingAddress->toArray() : [],
            'billing_address' => $this->billingAddress ? $this->billingAddress->toArray() : [],
        ]);
    }
}
