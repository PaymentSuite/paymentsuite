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

namespace PaymentSuite\BankwireBundle\Services\Wrapper;

use PaymentSuite\BankwireBundle\BankwireMethod;

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
