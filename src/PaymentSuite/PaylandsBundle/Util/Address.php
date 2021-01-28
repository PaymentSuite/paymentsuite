<?php


namespace PaymentSuite\PaylandsBundle\Util;

use PaymentSuite\PaylandsBundle\Util\Interfaces\ArrayableInterface;

/**
 * @author Juanjo Martínez <jmartinez@wearemarketing.com>
 */
final class Address implements ArrayableInterface
{
     private $city;

     private $country;

     private $address1;

     private $address2;

     private $address3;

     private $zipCode;

     private $stateCode;

    public function __construct(
        string $city,
        CountryCode $country,
        string $address1,
        string $zipCode,
        string $stateCode,
        string $address2 = null,
        string $address3 = null
    )
    {
        $this->city = $city;
        $this->country = $country;
        $this->address1 = $address1;
        $this->zipCode = $zipCode;
        $this->stateCode = $stateCode;
        $this->address2 = $address2;
        $this->address3 = $address3;
    }

    public static function create(
        string $city,
        CountryCode $country,
        string $address1,
        string $zipCode,
        string $stateCode,
        string $address2 = null,
        string $address3 = null
    ): self
    {
        return new self(
            $city,
            $country,
            $address1,
            $zipCode,
            $stateCode,
            $address2,
            $address3
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'city' => $this->city,
            'country' => (string) $this->country,
            'address1' => $this->address1,
            'zip_code' => $this->zipCode,
            'state_code' => $this->stateCode,
            'address2' => $this->address2,
            'address3' => $this->address3,
        ]);
    }
}
