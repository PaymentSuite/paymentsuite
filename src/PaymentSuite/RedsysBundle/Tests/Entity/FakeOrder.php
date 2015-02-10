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

namespace PaymentSuite\RedsysBundle\Tests\Entity;

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
     * @var integer id
     */
    private $id;

    /**
     * @var string dsOrder
     */
    private $dsOrder;

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

    /**
     * Get dsOrder
     *
     * @return string
     */
    public function getDsOrder()
    {
        return $this->dsOrder;
    }

    /**
     * Set dsOrder
     *
     * @param string $dsOrder
     */
    public function setDsOrder($dsOrder)
    {
        $this->dsOrder = $dsOrder;
    }
}
