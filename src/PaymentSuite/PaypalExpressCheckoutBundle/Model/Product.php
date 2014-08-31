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

class Product
{
    private $description;
    private $name;
    private $price;
    private $priceTVA;
    private $count;

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getPriceTVA()
    {
        return $this->priceTVA;
    }

    public function setPriceTVA($priceTVA)
    {
        $this->priceTVA = $priceTVA;

        return $this;
    }

    public function getCount()
    {
        return $count;
    }

    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }
}
