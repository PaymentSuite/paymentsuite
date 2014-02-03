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

use PaymentSuite\RedsysBundle\Services\RedsysManager;

class RedsysManagerTest extends \PHPUnit_Framework_TestCase
{
    private $paymentBridge;

    private $redsysMethodWrapper;

    private $paymentEventDispatcher;

    private $redsysResponseMethod;

    private $redsysManager;

    private $redsysMethod;

    private $templating;

    /**
     * Setup method
     */
    public function setUp()
    {

        $this->paymentBridge = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->redsysMethodWrapper = $this
            ->getMockBuilder('PaymentSuite\RedsysBundle\Services\Wrapper\RedsysMethodWrapper')
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

        $this->redsysResponseMethod = $this
            ->getMockBuilder('Redsys\Models\Response\Method')
            ->disableOriginalConstructor()
            ->getMock();

        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\Engine', array(), array(), '', false, false);

        $this->redsysManager = new RedsysManager($this->paymentEventDispatcher, $this->redsysMethodWrapper, $this->paymentBridge, $this->templating);
    }

    public function currencyProvider()
    {
        return array(
            array('978', 'EUR'),
            array('484', 'MXN'),
            array('949', 'TRY'),
            array('756', 'CHF')
        );
    }
    /**
     * @dataProvider currencyProvider
     */
    public function testCurrencyTranslation($expected, $currency)
    {

        $result = $this->redsysManager->currencyTranslation($currency);

        // assert that your calculator added the numbers correctly!
        $this->assertEquals($expected, $result);
    }

    /**
     * @expectedException PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException
     */
    public function testCurrencyTranslationException()
    {
        $result = $this->redsysManager->currencyTranslation('XXX');
    }

    /**
     * @expectedException PaymentSuite\RedsysBundle\Exception\ParameterNotReceivedException
     */
    public function testParameterNotReceivedException(){
        $parameters = array(
            'Ds_Signature'       => 'X',
        );

        $this
            ->redsysMethodWrapper
            ->expects($this->any())
            ->method('getRedsysMethod')
            ->will($this->returnValue($this->redsysMethod));

        $result = $this->redsysManager->processResult($parameters);

    }

    /**
     * @expectedException PaymentSuite\RedsysBundle\Exception\InvalidSignatureException
     */
    public function testInvalidSignatureException(){
        $parameters = array(
            'Ds_Date'       => 'X',
            'Ds_Hour'       => 'X',
            'Ds_Terminal'       => '1',
            'Ds_SecurePayment'  => '1',
            'Ds_Signature'       => '96375FB107B95DA311B818B39F46F03D3A9874',
            'Ds_Response'        => '0',
            'Ds_Amount'          => '100',
            'Ds_Order'           => '0001',
            'Ds_MerchantCode'    => '999008881',
            'Ds_Currency'        => '978',
            'Ds_TransactionType' => '2',
            'Ds_MerchantData'   =>  'Mis datos',
            'Ds_Card_Country'   =>  'y',
            'Ds_AuthorisationCode' =>   '222FFF',
            'Ds_ConsumerLanguage' => 'y',
            'Ds_Card_Type'  => 'y',

        );

        $this
            ->redsysMethodWrapper
            ->expects($this->any())
            ->method('getRedsysMethod')
            ->will($this->returnValue($this->redsysMethod));

        $this->redsysManager->processResult($parameters);

    }

    /**
     * @expectedException PaymentSuite\PaymentCoreBundle\Exception\PaymentException
     */
    public function testPaymentError(){

        $dsResponse = '100';

        $dsAuthorisationCode = '222FFF';

        $dsCardCountry = 'ESP';

        $dsCardType = 'y';

        $dsConsumerLanguage = 'y';

        $dsDate = 'X';

        $dsHour = 'X';

        $dsSecurePayment = '1';

        $parameters = array(
            'Ds_Date'       => $dsDate,
            'Ds_Hour'       => $dsHour,
            'Ds_Terminal'       => '1',
            'Ds_SecurePayment'  => $dsSecurePayment,
            'Ds_Signature'       => 'A9573FC831EA06ADE877BDDF7AB30975377239E0',
            'Ds_Response'        => $dsResponse,
            'Ds_Amount'          => '100',
            'Ds_Order'           => '0001',
            'Ds_MerchantCode'    => '999008881',
            'Ds_Currency'        => '978',
            'Ds_TransactionType' => '2',
            'Ds_MerchantData'   =>  'Mis datos',
            'Ds_Card_Country'   =>  $dsCardCountry,
            'Ds_AuthorisationCode' =>   $dsAuthorisationCode,
            'Ds_ConsumerLanguage' => $dsConsumerLanguage,
            'Ds_Card_Type'  => $dsCardType,

        );

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderDone')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysMethod));

        $this
            ->redsysMethodWrapper
            ->expects($this->any())
            ->method('getRedsysMethod')
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsResponse')
            ->with($this->equalTo($dsResponse))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsAuthorisationCode')
            ->with($this->equalTo($dsAuthorisationCode))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsCardCountry')
            ->with($this->equalTo($dsCardCountry))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsCardType')
            ->with($this->equalTo($dsCardType))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsConsumerLanguage')
            ->with($this->equalTo($dsConsumerLanguage))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsDate')
            ->with($this->equalTo($dsDate))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsHour')
            ->with($this->equalTo($dsHour))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsSecurePayment')
            ->with($this->equalTo($dsSecurePayment))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderFail')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysMethod));

        $result = $this->redsysManager->processResult($parameters);

    }

    public function testPaymentSuccess(){

        $dsResponse = 0;

        $dsAuthorisationCode = '222FFF';

        $dsCardCountry = 'ESP';

        $dsCardType = 'y';

        $dsConsumerLanguage = 'y';

        $dsDate = 'X';

        $dsHour = 'X';

        $dsSecurePayment = '1';

        $parameters = array(
            'Ds_Date'       => $dsDate,
            'Ds_Hour'       => $dsHour,
            'Ds_Terminal'       => '1',
            'Ds_SecurePayment'  => $dsSecurePayment,
            'Ds_Signature'       => 'EB13F59FCC707771E0500BE372E4024B4BAEAF24',
            'Ds_Response'        => $dsResponse,
            'Ds_Amount'          => '99',
            'Ds_Order'           => '0001',
            'Ds_MerchantCode'    => '999008881',
            'Ds_Currency'        => '978',
            'Ds_TransactionType' => '2',
            'Ds_MerchantData'   =>  'Mis datos',
            'Ds_Card_Country'   =>  $dsCardCountry,
            'Ds_AuthorisationCode' =>   $dsAuthorisationCode,
            'Ds_ConsumerLanguage' => $dsConsumerLanguage,
            'Ds_Card_Type'  => $dsCardType,

        );



        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderDone')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysMethod));

        $this
            ->redsysMethodWrapper
            ->expects($this->any())
            ->method('getRedsysMethod')
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsResponse')
            ->with($this->equalTo($dsResponse))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsAuthorisationCode')
            ->with($this->equalTo($dsAuthorisationCode))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsCardCountry')
            ->with($this->equalTo($dsCardCountry))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsCardType')
            ->with($this->equalTo($dsCardType))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsConsumerLanguage')
            ->with($this->equalTo($dsConsumerLanguage))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsDate')
            ->with($this->equalTo($dsDate))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsHour')
            ->with($this->equalTo($dsHour))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->redsysMethod
            ->expects($this->any())
            ->method('setDsSecurePayment')
            ->with($this->equalTo($dsSecurePayment))
            ->will($this->returnValue($this->redsysMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderDone')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysMethod));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderSuccess')
            ->with($this->equalTo($this->paymentBridge), $this->equalTo($this->redsysMethod));

        $result = $this->redsysManager->processResult($parameters);

    }
}