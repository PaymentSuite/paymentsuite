<?php

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
