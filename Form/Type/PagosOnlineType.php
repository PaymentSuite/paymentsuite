<?php

namespace Scastells\PagosOnlineBundle\Form\Type;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class PagosOnlineType extends AbstractType
{
    /*
     * @var PaymentBridgeInterface
     *
     * Cart Wrapper
     */
    private $paymentBridge;


    /**
     * Formtype construct method
     *
     * @param PaymentBridgeInterface $paymentBridge Payment bridge
     */
    public function __construct(PaymentBridgeInterface $paymentBridge)
    {
        $this->paymentBridge = $paymentBridge;
    }


    public function buildFrom(FormBuilderInterface $builder, array $options)
    {
        $builder

            /**
             * Credit card type
             */
            ->add('card_exp_month', 'choice', array(
                'required' => true,
                'choices' => array(
                    'VISA' => 'Visa','AMEX' => 'Amex','DINNERS' => 'Diners', 'MASTERCARD' => 'Mastercard'
                ),
            ))

            /**
             * Card Owner
             */

            ->add('card_name', 'text', array(
                'required' => true,
            ))

            /**
             * Credit card number
             */
            ->add('card_num', 'text', array(
                'required' => true,
                'max_length' => 16,
            ))

            /**
             * Credit card expiration
             */
            ->add('card_exp_month', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(1, 12), range(1, 12)),
            ))
            ->add('card_exp_year', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(2013, 2025), range(2013, 2025)),
            ))

            /**
             * Credit card security
             */
            ->add('card_ccv2', 'text', array(
                'required' => true,
                'max_length' => 4,
            ))

            /**
             * Number of quotes
             */
            ->add('card_cuotas', 'choice', array(
                'required' => true,
                'choices' => array_combine(range(1, 36), range(1, 36)),
            ))

            /**
             * Some hidden fields
             */
            ->add('amount', 'hidden', array(
                'data'  =>  number_format($this->paymentBridge->getAmount(), 2) * 100
            ))
            ->add('payment_processer', 'hidden', array(
                'data'  =>  'paymill_processer'
            ))
            ->add('submit', 'submit');
    }

    /**
     * Return unique name for this form
     *
     * @return string
     */
    public function getName()
    {
        return 'pagosonline_view';
    }
}