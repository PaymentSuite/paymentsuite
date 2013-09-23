<?php

/**
 * TransferenceBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @package TransferenceBundle
 *
 * Marc Morera 2013
 */

namespace Mmoreram\TransferenceBundle\Services\Wrapper;

use Mmoreram\TransferenceBundle\TransferenceMethod;


/**
 * TransferenceMethodWrapper
 */
class TransferenceMethodWrapper
{

    /**
     * @var TransferenceMethod
     * 
     * Transference method
     */
    private $transferenceMethod;


    /**
     * Construct method
     */
    public function __construct()
    {
        $this->transferenceMethod = new TransferenceMethod;
    }


    /**
     * Get transference method
     * 
     * @return TransferenceMethod Transference method
     */
    public function getTransferenceMethod()
    {
        return $this->transferenceMethod;
    }

}