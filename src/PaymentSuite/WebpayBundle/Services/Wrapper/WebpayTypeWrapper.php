<?php

/**
 * WebpayBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 */

namespace PaymentSuite\WebpayBundle\Services\Wrapper;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;

/**
 * WebpayTypeWrapper
 */
class WebpayTypeWrapper
{
    /**
     * @var FormFactory
     *
     * Form factory
     */
    protected $formFactory;

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge
     */
    protected $paymentBridge;

    /**
     * @var string
     *
     * KCC cgi-bin location
     */
    protected $cgiUri;

    /**
     * Formtype construct method
     *
     * @param FormFactory            $formFactory   Form factory
     * @param PaymentBridgeInterface $paymentBridge Payment bridge
     * @param string                 $cgiUri        KCC cgi-bin location
     */
    public function __construct(FormFactory $formFactory, PaymentBridgeInterface $paymentBridge, $cgiUri)
    {
        $this->formFactory = $formFactory;
        $this->paymentBridge = $paymentBridge;
        $this->cgiUri = $cgiUri;
    }

    /**
     * Builds form given session ID and success and fail routes
     *
     * @param string $sessionId Session ID
     * @param string $okRoute   Ok route
     * @param string $failRoute Fail route
     *
     * @return FormBuilderInterface
     */
    public function buildForm($sessionId, $okRoute, $failRoute)
    {
        $formBuilder = $this
            ->formFactory
            ->createNamedBuilder(null)
            ->setAction($this->cgiUri . '/tbk_bp_pago.cgi')
            ->setMethod('POST')
            ->add('TBK_TIPO_TRANSACCION', 'hidden', array('data' => 'TR_NORMAL'))
            ->add('TBK_MONTO', 'hidden', array('data' => floor($this->paymentBridge->getAmount() * 100)))
            ->add('TBK_ORDEN_COMPRA', 'hidden', array('data' => $this->paymentBridge->getOrderId()))
            ->add('TBK_ID_SESION', 'hidden', array('data' => $sessionId))
            ->add('TBK_URL_EXITO', 'hidden', array('data' => $okRoute))
            ->add('TBK_URL_FRACASO', 'hidden', array('data' => $failRoute));

        return $formBuilder;
    }
}
