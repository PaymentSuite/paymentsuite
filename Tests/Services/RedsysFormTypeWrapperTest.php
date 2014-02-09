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


namespace Acme\DemoBundle\Tests\Utility;

use PaymentSuite\RedsysBundle\Services\Wrapper\RedsysFormTypeWrapper;
use Symfony\Component\Form\Test\TypeTestCase;
use PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException;
use Symfony\Component\Form\FormView;
class RedsysFormTypeWrapperTest extends TypeTestCase
{

    const  merchantCode = '327234688';

    const secretKey = 'qwertyasdf0123456789';

    const url = 'https://sis-t.redsys.es:25443/sis/realizarPago';

    const terminal = '001';

    const merchantUrl = 't';

    const merchantUrlOk = 'x';

    const merchantUrlKo = 'z';

    const transactionType = '0';

    const prodDesc = 'desc';

    const titular = 'tit';

    const name = 'name';

    private $paymentBridge;

    private $paymentEventDispatcher;

    private $redsysMethod;

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