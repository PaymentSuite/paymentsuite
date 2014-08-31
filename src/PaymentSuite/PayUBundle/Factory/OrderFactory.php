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

use PaymentSuite\PayuBundle\Model\Order;

/**
 * Class OrderFactory
 */
class OrderFactory
{
    /**
     * @var string
     *
     * accountId
     */
    protected $accountId;

    /**
     * @var string
     *
     * language
     */
    protected $language;

    /**
     * Construct method
     *
     * @param string $accountId Merchant Account ID
     * @param string $language  Language used on emails
     */
    public function __construct($accountId, $language)
    {
        $this->accountId = $accountId;
        $this->language = $language;
    }

    /**
     * Creates an instance of Order model
     *
     * @return Order model
     */
    public function create()
    {
        $order = new Order();
        $order->setAccountId($this->accountId);
        $order->setLanguage($this->language);

        return $order;
    }
}
