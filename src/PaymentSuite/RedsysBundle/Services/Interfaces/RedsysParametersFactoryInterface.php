<?php

namespace PaymentSuite\RedsysBundle\Services\Interfaces;


/**
 * @author Gerard Rico <grico@wearemarketing.com>
 */
interface RedsysParametersFactoryInterface
{
    public function create(): array;
}