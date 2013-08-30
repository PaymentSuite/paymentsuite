<?php

/**
 * BaseEcommerce Symfony2 Bundle
 *
 * Befactory 2013
 */

namespace Befactory\PaymentCoreBundle\Twig\Abstracts;

use Symfony\Component\Form\FormFactory;
use Twig_Extension;

/**
 * Text utilities extension
 *
 */
abstract class AbstractExtension extends Twig_Extension
{


    /**
     * @var boolean
     *
     * Payment method is defined
     */
    protected $enabled;


    /**
     * @var FormFactory
     *
     * Form factory
     */
    protected $formFactory;


    /**
     * Return if Payment method is enabled.
     * This variable must be injected in constructor, defined in you config parameters
     * This variable is always mandatory for all payment bundles
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }


    /**
     * Construct method
     *
     * @param boolean $enabled      Return if module is enabled
     * @param string  $paymentRoute Payment route
     */
    public function __construct($enabled, FormFactory $formFactory)
    {
        $this->enabled = $enabled;
        $this->formFactory = $formFactory;
    }


    /**
     * Return all filters
     *
     * @return array Filters created
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('is_enabled', array($this, 'isEnabled'))
        );
    }
}