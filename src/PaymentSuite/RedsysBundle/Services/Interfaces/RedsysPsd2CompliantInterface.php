<?php

namespace PaymentSuite\RedsysBundle\Services\Interfaces;

use PaymentSuite\RedsysBundle\Services\Redsys3dSecureBuilder;

/**
 * @author Gerard Rico <grico@wearemarketing.com>
 */
interface RedsysPsd2CompliantInterface
{
    public function build3dSecureParameters(Redsys3dSecureBuilder $builder): void;
}
