<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PayUBundle\Factory;

use PaymentSuite\PayuBundle\Model\Merchant;

/**
 * Class MerchantFactory
 */
class MerchantFactory
{
    /**
     * @var string
     *
     * apiLogin
     */
    protected $apiLogin;

    /**
     * @var string
     *
     * apiKey
     */
    protected $apiKey;

    /**
     * Construct method
     *
     * @param string $apiLogin Merchant API login
     * @param string $apiKey   Merchant API key
     */
    public function __construct($apiLogin, $apiKey)
    {
        $this->apiLogin = $apiLogin;
        $this->apiKey = $apiKey;
    }

    /**
     * Creates an instance of Merchant model
     *
     * @return Merchant model
     */
    public function create()
    {
        $merchant = new Merchant();
        $merchant->setApiLogin($this->apiLogin);
        $merchant->setApiKey($this->apiKey);

        return $merchant;
    }
}
