<?php
/**
 * GoogleWalletBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 * This class define a payload of a product for sale.
 *
 * @copyright 2013  Google Inc. All rights reserved.
 * @author Rohit Panwar <panwar@google.com>
 *
 */

namespace PaymentSuite\GoogleWalletBundle\Entity;

class Payload
{
    /**
     * @var string The target audience for JWT.
     */
    const AUDIENCE = "Google";

    /**
     * @var string The type of request.
     */
    const TYPE = "google/payments/inapp/item/v1";

    /**
     * @var integer The time when the purchase will expire (in seconds).
     */
    private $exp;

    /**
     * @var integer The time when JWT issued (in seconds).
     */
    private $iat;

    /**
     * @var array Requested  fields.
     */
    private $request = array();

    /**
     * @var array Payload.
     */
    public $payload = array();

    /**
     * Set JWT Issued time.
     * @param integer $issuedAt The time when the JWT was issued.
     */
    public function setIssuedAt($issuedAt)
    {
        $this->iat = $issuedAt;
    }

    /**
     * Set JWT expiration time.
     * @param integer $expiryTime The time when the purchase will expire.
     */
    public function setExpiration($expiryTime)
    {
        $this->exp = $expiryTime;
    }

    /**
     * Add requested data into Request array.
     * @param string $fieldName  Requested field name.
     * @param string $fieldValue Requested field value.
     */
    public function addProperty($fieldName, $fieldValue)
    {
        $this->request[$fieldName] = $fieldValue;
    }

    /**
     * Create payload of the product.
     * @param  string $sellerIdentifier Merchant Id.
     * @return array  $this->payload Payload of the product.
     */
    public function createPayload($sellerIdentifier)
    {
        $this->payload['iss'] = $sellerIdentifier;
        $this->payload['aud'] = self::AUDIENCE;
        $this->payload['typ'] = self::TYPE;
        $this->payload['exp'] = $this->exp;
        $this->payload['iat'] = $this->iat;
        $this->payload['request'] = $this->request;

        return $this->payload;
    }
}
