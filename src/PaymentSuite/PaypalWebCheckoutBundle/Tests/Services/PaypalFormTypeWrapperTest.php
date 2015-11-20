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

namespace PaymentSuite\PaypalWebCheckoutBundle\Tests\Services;

use Symfony\Component\Form\Test\TypeTestCase;

use PaymentSuite\PaypalWebCheckoutBundle\Services\Wrapper\PaypalFormTypeWrapper;

/**
 * Class PaypalFormTypeWrapperTest
 *
 * @author Arkaitz Garro <hola@arkaitzgarro.com>
 */
class PaypalFormTypeWrapperTest extends TypeTestCase
{
    /**
     * @var string
     *
     * Business code
     */
    const business = 'arkaitz.garro-facilitator@gmail.com';

    /**
     * @var string
     */
    const url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

    /**
     * @var string
     */
    const returnRouteName = 'payment_success';

    /**
     * @var string
     */
    const cancelRouteName = 'payment_cancel';

    /**
     * @var string
     */
    const failedRouteName = 'payment_failed';

    /**
     * @var string
     */
    const notifyRouteName = 'payment_process';

    /**
     * @var PaymentBridge
     *
     * Payment bridge object
     */
    private $paymentBridge;

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher object
     */
    private $paymentEventDispatcher;

    /**
     * @var PaypalMethod
     *
     * Paypal method object
     */
    private $paypalMethod;

    /**
     * @var PaypalFormTypeWrapper
     *
     * Paypal form type manager object
     */
    private $paypalFormTypeWrapper;

    /**
     * Setup method
     */
    public function setUp()
    {
        parent::setUp();

        $this->paymentBridge = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface')
            ->setMethods(['setAmount', 'getAmount', 'findOrder', 'getOrder', 'setOrder', 'getOrderId', 'getCurrency', 'isOrderPaid', 'getExtraData', 'getOrderDescription'])
            ->getMock();

        $this->paymentBridge
            ->method('getCurrency')
            ->willReturn('EUR');

        $this->paymentBridge
            ->method('getAmount')
            ->willReturn(1000);

        $this->paymentBridge
            ->method('getExtraData')
            ->willReturn(['items' => []]);

        $this->paymentEventDispatcher = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paypalMethod = $this
            ->getMockBuilder('PaymentSuite\PaypalWebCheckoutBundle\PaypalMethod')
            ->disableOriginalConstructor()
            ->getMock();

        $router = $this->getMockBuilder('Symfony\Bundle\Frameworkbundle\Routing\Router')
            ->disableOriginalConstructor()
            ->getMock();

        $urlFactory = $this->getMockBuilder('PaymentSuite\PaypalWebCheckoutBundle\Services\UrlFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paypalFormTypeWrapper = new PaypalFormTypeWrapper($this->factory,
            $this->paymentBridge,
            $router,
            $this::business,
            $urlFactory
            );
    }

    /**
     * Test form creation
     */
    public function testFormCreation()
    {
        $amount = 10;

        $formData = [
            'business' => 'arkaitz.garro-facilitator@gmail.com',
        ];

        $formView = $this->paypalFormTypeWrapper->buildForm();

        $children = $formView->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
            $message = $formData[$key] . '/' . $children[$key]->vars['value'];
            $this->assertEquals($formData[$key], $children[$key]->vars['value'], $message);
        }
    }
}
