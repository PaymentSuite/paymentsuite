<?php
/**
 * PaypalExpressCheckoutBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Mickael Andrieu <mickael.andrieu@sensiolabs.com>
 * @package PaypalExpressCheckout
 *
 * Marc Morera 2013
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
        foreach($this->products as $product) {
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
