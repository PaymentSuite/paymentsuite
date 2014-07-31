<?php

namespace PaymentSuite\PayUBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class SerializerPass
 */
class SerializerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $container
            ->getDefinition('jms_serializer.camel_case_naming_strategy')
            ->setClass('JMS\Serializer\Naming\IdenticalPropertyNamingStrategy');
    }
}
