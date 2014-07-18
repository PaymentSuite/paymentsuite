<?php

namespace PaymentSuite\PayUBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

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
