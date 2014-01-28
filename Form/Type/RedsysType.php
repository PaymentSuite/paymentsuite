<?php

/**
 * RedsysBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @author Gonzalo Vilaseca <gonzalo.vilaseca@gmail.com>
 * @package RedsysBundle
 *
 * Gonzalo Vilaseca 2014
 */

namespace PaymentSuite\RedsysBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use PaymentSuite\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
use Symfony\Component\Routing\Router;

/**
 * Type for a shop edit profile form
 */
class RedsysType extends AbstractType
{

    /**
     * @var PaymentBridgeInterface
     *
     * Card Wrapper
     */
    private $paymentBridge;


    /**
     * @var Router
     *
     * Router instance
     */
    private $router;


    /**
     * @var string
     *
     * Execution route name
     */
    private $controllerRouteName;


    /**
     * Formtype construct method
     *
     * @param Router                 $router              Router instance
     * @param PaymentBridgeInterface $paymentBridge       Payment bridge
     * @param string                 $controllerRouteName Controller route name
     */
    public function __construct(Router $router, PaymentBridgeInterface $paymentBridge, $controllerRouteName)
    {
        $this->paymentBridge = $paymentBridge;
        $this->router = $router;
        $this->controllerRouteName = $controllerRouteName;
    }


    /**
     * Buildform function
     *
     * @param FormBuilderInterface $builder the formBuilder
     * @param array                $options the options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->router->generate($this->controllerRouteName, array(), true))
            ->setMethod('POST')

            ->add('Ds_Merchant_Amount', 'hidden', array(
                'data'  =>  number_format($this->paymentBridge->getAmount(), 2) * 100
            ))

            ->add('Ds_Merchant_Currency', 'hidden', array(
                'data'  =>  $this->paymentBridge->getCurrency()
            ))

            ->add('Ds_Merchant_Order', 'hidden', array(
                'data'  =>  $this->paymentBridge->getOrderId()
            ))

            ->add('Ds_Merchant_ProductDescription', 'hidden')

            ->add('Ds_Merchant_MerchantCode', 'hidden')

            ->add('Ds_Merchant_Titular', 'hidden')

            ->add('Ds_Merchant_MerchantURL', 'hidden')

            ->add('Ds_Merchant_UrlOK', 'hidden')

            ->add('Ds_Merchant_UrlKO', 'hidden')

            ->add('Ds_Merchant_MerchantName', 'hidden')

            ->add('Ds_Merchant_MerchantSignature', 'hidden')

            ->add('Ds_Merchant_Terminal', 'hidden')

            ->add('submit', 'submit');
    }


    /**
     * Return unique name for this form
     *
     * @return string
     */
    public function getName()
    {
        return 'redsys_view';
    }
}