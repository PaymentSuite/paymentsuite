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

namespace PaymentSuite\PaylandsBundle\Twig;

use PaymentSuite\PaylandsBundle\Services\PaylandsViewRenderer;

/**
 * Class PaylandsExtension.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class PaylandsExtension extends \Twig_Extension
{
    /**
     * @var PaylandsViewRenderer
     */
    protected $viewRenderer;

    /**
     * PaylandsExtension constructor.
     *
     * @param PaylandsViewRenderer $viewRenderer
     */
    public function __construct(PaylandsViewRenderer $viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('paylands_view', [
                $this->viewRenderer,
                'renderView',
            ], [
                'needs_environment' => true,
                'is_safe' => ['html'],
            ]),
        ];
    }
}
