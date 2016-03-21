<?php
/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Manu Garcia <manugarciaes@gmail.com>
 */

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\RedsysBundle\Exception\InvalidSignatureException;

/**
 * Class RedsysSignature
 * @package PaymentSuite\RedsysBundle\Services
 */
class RedsysSignature
{
    const SIGNATURE_VERSION = 'HMAC_SHA256_V1';

    /**
     * Return a new signature using HMAC SHA256
     *
     * @param $amount       string
     * @param $order        string
     * @param $merchantCode string
     * @param $currency     string
     * @param $response     string
     * @param $secret       string
     * @return string
     */
    public function sign (
        $order,
        $secret,
        $data
    ) {
        $key = base64_decode($secret);
        $bytes = array(0, 0, 0, 0, 0, 0, 0, 0);

        $iv = implode(array_map("chr", $bytes));
        $key = \mcrypt_encrypt(MCRYPT_3DES, $key, $order, MCRYPT_MODE_CBC, $iv);

        return base64_encode(\hash_hmac('sha256', $data, $key, true));
    }

    /**
     * Compare signature from Redsys and our signature
     *
     * @param $externalSignature "Signature from Redsys"
     * @param $internalSignature "Our signature"
     * @return bool
     * @throws InvalidSignatureException
     */
    public function checkSign($externalSignature, $internalSignature)
    {
        $internalSignature = strtr($internalSignature, '+/', '-_');

        if ($externalSignature != $internalSignature) {
            throw new InvalidSignatureException();
        }

        return true;
    }

    /**
     * Return signature version
     *
     * @return string
     */
    public function getSignatureVersion()
    {
        return $this::SIGNATURE_VERSION;
    }

}