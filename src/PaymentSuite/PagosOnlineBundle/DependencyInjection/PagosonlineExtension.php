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

namespace PaymentSuite\PagosOnlineBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PagosonlineExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('pagosonline.controller.route', $config['controller_route']);

        $container->setParameter('pagosonline.success.route', $config['payment_success']['route']);
        $container->setParameter('pagosonline.success.order.append', $config['payment_success']['order_append']);
        $container->setParameter('pagosonline.success.order.field', $config['payment_success']['order_append_field']);

        $container->setParameter('pagosonline.fail.route', $config['payment_fail']['route']);
        $container->setParameter('pagosonline.fail.order.append', $config['payment_fail']['order_append']);
        $container->setParameter('pagosonline.fail.order.field', $config['payment_fail']['order_append_field']);

        //$container->setParameter('pagosonline.user_id', $config['user_id']);
        //$container->setParameter('pagosonline.password', $config['password']);
        $container->setParameter('pagosonline.account_id', $config['account_id']);
        //$container->setParameter('pagosonline.wsdl_url', $config['wsdl_url']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('parameters.yml');
        $loader->load('services.yml');
    }
}
