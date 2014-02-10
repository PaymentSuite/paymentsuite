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


namespace PaymentSuite\RedsysBundle\Tests\Services;

use PaymentSuite\RedsysBundle\Services\Wrapper\RedsysFormTypeWrapper;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class RedsysFormTypeWrapperTest
 * @package PaymentSuite\RedsysBundle\Tests\Services
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
    const merchantUrl = 't';

    /**
     * @var string
     */
    const merchantUrlOk = 'x';

    /**
     * @var string
     */
    const merchantUrlKo = 'z';

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
            ->getMockBuilder('Mmoreram\PaymentBridgeBundle\Services\PaymentBridge')
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
            $this::merchantCode,
            $this::secretKey,
            $this::url,
            $this::merchantUrl,
            $this::merchantUrlOk,
            $this::merchantUrlKo);
    }

    /**
     * Test form creation
     */
    public function testFormCreation()
    {
        $amount = 10;

        $formData = array(
            'Ds_Merchant_Amount' => $amount * 100,
            'Ds_Merchant_MerchantSignature' => 'D42DE69C83F647F3E40BD69A9199C5097B7B5353',
            'Ds_Merchant_MerchantCode'      => $this::merchantCode,
            'Ds_Merchant_Currency'          => '978',
            'Ds_Merchant_Terminal'          => $this::terminal,
            'Ds_Merchant_Order'             => '342',
            'Ds_Merchant_MerchantURL'       => $this::merchantUrl,
            'Ds_Merchant_UrlOK'             => $this::merchantUrlOk,
            'Ds_Merchant_UrlKO'             => $this::merchantUrlKo,
            'Ds_Merchant_TransactionType'   => $this::transactionType,
            'Ds_Merchant_ProductDescription' => $this::prodDesc,
            'Ds_Merchant_Titular'           => $this::titular,
            'Ds_Merchant_MerchantName'      => $this::name
        );

        $this
            ->paymentBridge
            ->expects($this->once())
            ->method('getExtraData')
            ->will($this->returnValue(array('terminal'  => $this::terminal,
                'transaction_type'                      => $this::transactionType,
                'product_description'                   => $this::prodDesc,
                'merchant_titular'                      =>  $this::titular,
                'merchant_name'                         => $this::name
            )));

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

        $formView = $this->redsysFormTypeWrapper->buildForm();

        $children = $formView->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
            $message = $formData[$key].'/'.$children[$key]->vars['value'];
            $this->assertEquals($formData[$key], $children[$key]->vars['value'],$message);

        }
    }
}