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

namespace PaymentSuite\PaypalExpressCheckoutBundle\Model;

class OrderFactory
{
    private $products;
    private $total;
    private $totalTVA;
    private $port;
    private $returnUrl;
    private $cancelUrl;

    public function __construct(array $products)
    {
        $total = 0;
        $totalTVA = 0;
        $this->products = $products;
        foreach ($this->products as $product) {
            $total += $product->getPrice();
            $totalTVA += $product->getPriceTVA();
        }
        $this->total = $total;
        $this->totalWithTax = $totalTVA;
    }

    public function getProducts()
    {
        return $this->orders;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function getTotalTVA()
    {
        return $this->totalTVA;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }
}
