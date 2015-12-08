<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\StripeBundle\Twig;

use Twig_Extension;
use Twig_SimpleFunction;

use PaymentSuite\StripeBundle\Services\StripeTemplateRender;

/**
 * Text utilities extension.
 */
class StripeExtension extends Twig_Extension
{
    /**
     * @var StripeTemplateRender
     *
     * Stripe template render
     */
    private $stripeTemplateRender;

    /**
     * Construct method.
     *
     * @param StripeTemplateRender $stripeTemplateRender Stripe template render
     */
    public function __construct(StripeTemplateRender $stripeTemplateRender)
    {
        $this->stripeTemplateRender = $stripeTemplateRender;
    }

    /**
     * Return all filters.
     *
     * @return array Filters created
     */
    public function getFunctions()
    {
        $options = ['needs_environment' => true];

        return [
            new Twig_SimpleFunction('stripe_render', [
                $this->stripeTemplateRender,
                'renderStripeForm',
            ], $options),
            new Twig_SimpleFunction('stripe_scripts', [
                $this->stripeTemplateRender,
                'renderStripeScripts',
            ], $options),
        ];
    }

    /**
     * return extension name.
     *
     * @return string extension name
     */
    public function getName()
    {
        return 'payment_stripe_extension';
    }
}
