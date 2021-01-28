<?php


namespace PaymentSuite\PaylandsBundle\Services;

use PaymentSuite\PaylandsBundle\Util\Address;
use PaymentSuite\PaylandsBundle\Util\ExtraData;
use PaymentSuite\PaylandsBundle\Util\Profile;

/**
 * @author Juanjo Martínez <jmartinez@wearemarketing.com>
 */
final class ExtraDataBuilder
{
    private $profile;

    private $address;

    private $shippingAddress;

    private $billingAddress;

    public function withProfile(Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function withAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function withShippingAddress(Address $address): self
    {
        $this->shippingAddress = $address;

        return $this;
    }

    public function withBillingAddress(Address $address): self
    {
        $this->billingAddress = $address;

        return $this;
    }

    public function build(): ExtraData
    {
        return ExtraData::create(
            $this->profile,
            $this->address,
            $this->shippingAddress,
            $this->billingAddress
        );
    }
}
