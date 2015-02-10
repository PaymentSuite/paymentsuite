<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
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
