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

use PaymentSuite\RedsysBundle\Services\RedsysManager;

/**
 * Redsys manager test
 */
class RedsysManagerTest extends \PHPUnit_Framework_TestCase
{
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
     * @var RedsysManager
     *
     * Redsys manager object
     */
    private $redsysManager;

    /**
     * @var RedsysMethod
     *
     * Redsys method object
     */
    private $redsysMethod;

    /**
     * Setup method
     */
    public function setUp()
    {

        $this->paymentBridge = $this
            ->getMockBuilder('PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->RedsysFormTypeWrapper = $this
            ->getMockBuilder('PaymentSuite\RedsysBundle\Services\Wrapper\RedsysFormTypeWrapper')
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

        $this->redsysManager = new RedsysManager(
            $this->paymentEventDispatcher,
            $this->RedsysFormTypeWrapper,
            $this->paymentBridge,
            '333');
    }

    /**
     * @expectedException PaymentSuite\RedsysBundle\Exception\ParameterNotReceivedException
     */
    public function testParameterNotReceivedException()
    {
        $parameters = array(
            'Ds_Signature'       => 'X',
        );

        $result = $this->redsysManager->processResult($parameters);

    }

    /**
     * @expectedException PaymentSuite\RedsysBundle\Exception\InvalidSignatureException
     */
    public function testInvalidSignatureException()
    {
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

        $this->redsysManager->processResult($parameters);

    }

    /**
     * @expectedException PaymentSuite\PaymentCoreBundle\Exception\PaymentException
     */
    public function testPaymentError()
    {
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
            'Ds_Signature'       => '3142396C4337FDD6DED470919FBB0BC54D512C9E',
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
            ->with($this->equalTo($this->paymentBridge));

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
            ->with($this->equalTo($this->paymentBridge));

        $this->redsysManager->processResult($parameters);

    }

    public function testPaymentSuccess()
    {
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
            'Ds_Signature'       => '9A163AA5034368367665866E62D603A5A92C5D35',
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
            ->with($this->equalTo($this->paymentBridge));

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
            ->with($this->equalTo($this->paymentBridge));

        $this
            ->paymentEventDispatcher
            ->expects($this->once())
            ->method('notifyPaymentOrderSuccess')
            ->with($this->equalTo($this->paymentBridge));

        $this->redsysManager->processResult($parameters);

    }
}
