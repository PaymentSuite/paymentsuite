<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 *
 */

namespace PaymentSuite\PayUBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use PaymentSuite\PayuBundle\DependencyInjection\Compiler\SerializerPass;

/**
 * Payu payment bundle
 */
class PayuBundle extends Bundle
{
    public function build(ContainerBuilder $builder)
    {
        $builder->addCompilerPass(new SerializerPass());
    }
}
