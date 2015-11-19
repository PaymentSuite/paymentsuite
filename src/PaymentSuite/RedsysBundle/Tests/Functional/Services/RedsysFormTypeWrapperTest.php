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

namespace PaymentSuite\RedsysBundle\Tests\Functional\Services;

use Symfony\Component\Form\Test\TypeTestCase;

use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher;
use PaymentSuite\RedsysBundle\RedsysMethod;
use PaymentSuite\RedsysBundle\Services\UrlFactory;
use PaymentSuite\RedsysBundle\Services\Wrapper\RedsysFormTypeWrapper;

/**
 * Class RedsysFormTypeWrapperTest
 */
class RedsysFormTypeWrapperTest extends TypeTestCase
{
    /**
     * @var string
     *
     * Merchant code
     */
    const merchantCode = '327234688';

    /**
     * @var string
     */
    const secretKey = 'qwertyasdf0123456789';

    /**
     * @var string
     */
    const url = 'https://sis-t.redsys.es:25443/sis/realizarPago';

    /**
     * @var string
     */
    const terminal = '001';

    /**
     * @var string
     */
    const merchantUrl = 'redsys_result';

    /**
     * @var string
     */
    const merchantUrlOk = 'card_thanks';

    /**
     * @var string
     */
    const merchantUrlKo = 'card_fail';

    /**
     * @var string
     */
    const transactionType = '0';

    /**
     * @var string
     */
    const prodDesc = 'desc';

    /**
     * @var string
     */
    const titular = 'tit';

    /**
     * @var string
     */
    const name = 'name';

    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge object
     */
    private $paymentBridge;

    /**
     * @var UrlFactory
     *
     * Url factory object
     */
    private $urlFactory;

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher object
     */
    private $paymentEventDispatcher;

    /**
     * @var RedsysMethod
     *
     * Redsys method object
     */
    private $redsysMethod;

    /**
     * @var RedsysFormTypeWrapper
     *
     * Redsys form type manager object
     */
    private $redsysFormTypeWrapper;

    /**
     * Setup method
     */
    public function setUp()
    {
        parent::setUp();

        $this->paymentBridge = $this
            ->getMockBuilder('PaymentSuite\RedsysBundle\Services\Interfaces\PaymentBridgeRedsysInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->urlFactory = $this
            ->getMockBuilder('PaymentSuite\RedsysBundle\Services\UrlFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->paymentEventDispatcher = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\PaymentEventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->redsysMethod = $this
            ->getMockBuilder('PaymentSuite\RedsysBundle\RedsysMethod')
            ->disableOriginalConstructor()
            ->getMock();

        $this->redsysFormTypeWrapper = new RedsysFormTypeWrapper($this->factory,
            $this->paymentBridge,
            $this->urlFactory,
            $this::merchantCode,
            $this::secretKey,
            $this::url,
            $this::merchantUrl);
    }

    /**
     * Test form creation
     */
    public function testFormCreation()
    {
        $amount = 100;

        $formData = [
            'Ds_Merchant_Amount'             => $amount,
            'Ds_Merchant_MerchantSignature'  => 'CB43B12351A9826D9640CC285CBDFD8CA6A5994C',
            'Ds_Merchant_MerchantCode'       => $this::merchantCode,
            'Ds_Merchant_Currency'           => '978',
            'Ds_Merchant_Terminal'           => $this::terminal,
            'Ds_Merchant_Order'              => '342',
            'Ds_Merchant_MerchantURL'        => '/payment/redsys/result',
            'Ds_Merchant_UrlOK'              => '/payment/redsys/checkout/ok',
            'Ds_Merchant_UrlKO'              => '/payment/redsys/checkout/ko',
            'Ds_Merchant_TransactionType'    => $this::transactionType,
            'Ds_Merchant_ProductDescription' => $this::prodDesc,
            'Ds_Merchant_Titular'            => $this::titular,
            'Ds_Merchant_MerchantName'       => $this::name,
        ];

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getExtraData')
            ->will($this->returnValue(['terminal'  => $this::terminal,
                                            'transaction_type'                      => $this::transactionType,
                                            'product_description'                   => $this::prodDesc,
                                            'merchant_titular'                      => $this::titular,
                                            'merchant_name'                         => $this::name,
            ]));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getAmount')
            ->will($this->returnValue($amount));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getOrderNumber')
            ->will($this->returnValue('342'));

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getCurrency')
            ->will($this->returnValue('EUR'));

        $this
            ->urlFactory
            ->expects($this->once())
            ->method('getReturnUrlOkForOrderId')
            ->will($this->returnValue('/payment/redsys/checkout/ok'));

        $this
            ->urlFactory
            ->expects($this->once())
            ->method('getReturnUrlKoForOrderId')
            ->will($this->returnValue('/payment/redsys/checkout/ko'));

        $this
            ->urlFactory
            ->expects($this->once())
            ->method('getReturnRedsysUrl')
            ->will($this->returnValue('/payment/redsys/result'));

        $formView = $this->redsysFormTypeWrapper->buildForm();

        $children = $formView->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);

            $message = $formData[$key] . ':::' . $children[$key]->vars['value'];
            $this->assertEquals($formData[$key], $children[$key]->vars['value'], $message);
        }
    }
}
