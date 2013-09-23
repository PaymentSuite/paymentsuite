<?php

/**
 * BankwireBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package BankwireBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\BankwireBundle\Services\Wrapper;

use Mmoreram\BankwireBundle\BankwireMethod;


/**
 * BankwireMethodWrapper
 */
class BankwireMethodWrapper
{

    /**
     * @var BankwireMethod
     * 
     * Bankwire method
     */
    private $bankwireMethod;


    /**
     * Construct method
     */
    public function __construct()
    {
        $this->bankwireMethod = new BankwireMethod;
    }


    /**
     * Get bankwire method
     * 
     * @return BankwireMethod Bankwire method
     */
    public function getBankwireMethod()
    {
        return $this->bankwireMethod;
    }

}