<?php

namespace PaymentSuite\StripeBundle\ValueObject;

class StripeTransaction implements EditableStripeTransaction
{
    private $source;
    private $amount;
    private $currency;
    private $customerId;
    private $description;
    private $email;
    private $metadata;

    public function __construct($source, $amount, $currency)
    {
        $this->source = $source;
        $this->amount = $amount;
        $this->currency = strtolower($currency);
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    public function getCustomerData()
    {
        return [
            'source' => $this->source,
            "description" => $this->description,
            "email" => $this->email,
            "metadata" => $this->metadata,
        ];
    }

    /**
     * @param mixed $description
     * @return StripeTransaction
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param mixed $email
     * @return StripeTransaction
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param mixed $metadata
     * @return StripeTransaction
     */
    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @param mixed $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getChargeData()
    {
        return [
            "customer" => $this->customerId,
            "amount" => $this->amount,
            "currency" => $this->currency,
        ];
    }
}