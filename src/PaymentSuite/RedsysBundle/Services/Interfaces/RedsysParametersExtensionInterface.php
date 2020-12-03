<?php

namespace PaymentSuite\RedsysBundle\Services\Interfaces;

/**
 * @author Gerard Rico <grico@wearemarketing.com>
 */
interface RedsysParametersExtensionInterface
{
    public function extend(array &$parameters): void;
}
