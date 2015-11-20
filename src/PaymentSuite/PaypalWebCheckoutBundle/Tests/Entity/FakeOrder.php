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

namespace PaymentSuite\PaypalWebCheckoutBundle\Tests\Entity;

/**
 * Class FakeOrder
 *
 * This class is used to fake an order to be processed by PaymentSuite
 *
 * @author Arkaitz Garro <hola@arkaitzgarro.com>
 */
class FakeOrder
{
    /**
     * @var id
     */
    private $id;

    /**
     * Class initialization
     */
    public function __construct()
    {
        $this->id = 0;
    }

    /**
     * Get order id
     *
     * @return int Order id
     */
    public function getId()
    {
        return $this->id;
    }
}
