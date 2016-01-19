<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * Copyright (c) 2013-2016 Marc Morera
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\RedsysBundle\Services;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class RedsysUrlFactory.
 */
class RedsysUrlFactory
{
    /**
     * @var UrlGeneratorInterface
     *
     * Url generator
     */
    private $urlGenerator;

    /**
     * Construct.
     *
     * @param UrlGeneratorInterface $urlGenerator Url generator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Get the route result Redsys.
     *
     * @return string Result url
     */
    public function getReturnRedsysUrl()
    {
        return $this
            ->urlGenerator
            ->generate(
                'paymentsuite_redsys_result',
                [],
                true
            );
    }

    /**
     * Get the route succesfull payment return from Redsys.
     *
     * @param $orderId
     *
     * @return string Success utl
     */
    public function getReturnUrlOkForOrderId($orderId)
    {
        return $this->urlGenerator->generate(
            'paymentsuite_redsys_success',
            ['id' => $orderId],
            true
        );
    }

    /**
     * Get the route cancelled payment from Redsys.
     *
     * @param $orderId
     *
     * @return string Ko url
     */
    public function getReturnUrlKoForOrderId($orderId)
    {
        return $this
            ->urlGenerator
            ->generate(
                'paymentsuite_redsys_failure',
                ['id' => $orderId],
                true
            );
    }
}
