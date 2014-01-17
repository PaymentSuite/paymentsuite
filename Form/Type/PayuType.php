<?php

namespace Scastells\PayuBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Mmoreram\PaymentCoreBundle\Services\interfaces\PaymentBridgeInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Validator\Constraints\Collection;

class PayuType extends AbstractType
{

    /*
     * @var PaymentBridgeInterface
     *
     * Cart Wrapper
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
     * @param \Symfony\Component\Routing\Router $router
     * @param PaymentBridgeInterface $paymentBridge Payment bridge
     * @param string $controllerRouteName
     * @internal param \Symfony\Component\Routing\Router $router
     */
    public function __construct(Router $router, PaymentBridgeInterface $paymentBridge, $controllerRouteName)
    {
        $this->paymentBridge = $paymentBridge;
        $this->router = $router;
        $this->controllerRouteName = $controllerRouteName;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->setAction($this->router->generate($this->controllerRouteName, array(), true))
            ->setMethod('POST')

            /**
             * Some hidden fields
             */
            ->add('card_type', 'hidden', array(
                'data'  =>  "VISA"
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
        return 'payu_view';
    }
}