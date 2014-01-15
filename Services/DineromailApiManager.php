<?php


namespace Scastells\DineromailApiBundle\Services;

use Mmoreram\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use Mmoreram\PaymentCoreBundle\Exception\PaymentAmountsNotMatchException;
use Mmoreram\PaymentCoreBundle\Exception\PaymentOrderNotFoundException;
use Mmoreram\PaymentCoreBundle\Services\PaymentEventDispatcher;
use Mmoreram\PaymentCoreBundle\Exception\PaymentException;
use Scastells\DineromailApiBundle\DineromailApiMethod;
use SoapVar;

/**
 * DineroMailAPi manager
 */
class DineromailApiManager
{

    /**
     * @var PaymentEventDispatcher
     *
     * Payment event dispatcher
     */
    protected $paymentEventDispatcher;



    /**
     * @var PaymentBridgeInterface
     *
     * Payment bridge interface
     */
    protected $paymentBridge;


    /**
     * soap structure
     */
    protected $soapCredentials;


    /**
     * @var string
     *
     * user dineromailapi
     */
    private $apiUserName;


    /**
     * @var string
     *
     * url api dineromailapi
     */
    private $apiPassword;

    /**
     * @var string
     *
     * url api dineromailapi ns
     */
    private $apiNs;


    /**
     * @var string                           $credit_card
     *
     * url api dineromailapi prefix
     */
    private $apiPrefix;


    /**
     * @var logger
     *
     */
    private $logger;

    /**
     * @var string
     *
     * string wsdl soapClient
     */
    private $wsdl;

    /**
     * Construct method for dineromailapi manager
     *
     * @param PaymentEventDispatcher $paymentEventDispatcher Event dispatcher
     * @param PaymentBridgeInterface $paymentBridge Payment Bridge
     * @param $apiUserName
     * @param $apiPassword
     * @param $logger
     * @param $ns
     * @param $apiPrefix
     * @param $wsdl
     * @param dineromail_api_debug
     */
    public function __construct(PaymentEventDispatcher $paymentEventDispatcher, PaymentBridgeInterface $paymentBridge,
                                $apiUserName, $apiPassword, $logger, $ns, $apiPrefix, $wsdl, $dineromail_api_debug)
    {
        $this->paymentEventDispatcher = $paymentEventDispatcher;
        $this->paymentBridge = $paymentBridge;
        $this->apiUserName = $apiUserName;
        $this->apiPassword = $apiPassword;
        $this->logger = $logger;
        $this->apiNs = $ns;
        $this->apiPrefix = $apiPrefix;
        $this->wsdl = $wsdl;
        $this->dineromail_api_debug = $dineromail_api_debug;
    }


    /**
     * Tries to process a payment through DineromailApi
     *
     * @param DineromailApiMethod $paymentMethod Payment method
     * @param float $amount Amount
     * @param env
     *
     * @throws PaymentAmountsNotMatchException
     * @throws PaymentOrderNotFoundException
     * @return DineromailAPiManager Self object
     */
    public function processPayment(DineromailApiMethod $paymentMethod, $amount)
    {
        /// first check that amounts are the same
        $paymentBridgeAmount = (float) $this->paymentBridge->getAmount() * 100;
        /**
         * If both amounts are different, execute Exception
         */
        if (abs($amount - $paymentBridgeAmount) > 0.00001) {

            throw new PaymentAmountsNotMatchException;
        }


        /**
         * At this point, order must be created given a cart, and placed in PaymentBridge
         *
         * So, $this->paymentBridge->getOrder() must return an object
         */
        $this->paymentEventDispatcher->notifyPaymentOrderLoad($this->paymentBridge, $paymentMethod);

        /**
         * Order Not found Exception must be thrown just here
         */
        if (!$this->paymentBridge->getOrder()) {

            throw new PaymentOrderNotFoundException;
        }

        $this->paymentEventDispatcher->notifyPaymentOrderCreated($this->paymentBridge, $paymentMethod);

        $extraData = $this->paymentBridge->getExtraData();

        //params to send dineromailapi api

        $cardYear = substr($paymentMethod->getCardExpYear(), -2);
        $cardExp = str_pad($paymentMethod->getCardExpMonth(), 2, '0', STR_PAD_LEFT) . '/' . $cardYear;

        $items = array();

        foreach ($extraData['dinero_mail_api_items'] as $key => $dineroMailApiItem) {
            $items[]= array(
                'Amount'        => $dineroMailApiItem['amount'],
                'Currency'      => $this->paymentBridge->getCurrency(),
                'Code'          => '',
                'Description'   => '',//$dineroMailApiItem['name'],
                'Name'          => $dineroMailApiItem['name'],
                'Quantity'      => $dineroMailApiItem['quantity']
            );
        }

        $buyer = array(
            'Name'      => $extraData['customer_firstname'],
            'LastName'  => $extraData['customer_lastname'],
            'Email'     => $extraData['customer_email'],
            'Address'   => $extraData['correspondence_address'],
            'Phone'     => $extraData['customer_phone'],
            'Country'   => $extraData['customer_country'],
            'City'      => $extraData['correspondence_city']
        );

        $creditCard = array(
            'Installment'       => $paymentMethod->getCardQuota(),
            'CreditCardNumber'  => $paymentMethod->getCardNum(),
            'Holder'            => $paymentMethod->getCardName(),
            'ExpirationDate'    => $cardExp,
            'SecurityCode'      => $paymentMethod->getCardSecurity(),
            'DocumentNumber'    => '1234567', //@TODO this can not be null, set customer document number in AR??
            'Address'           => '',
            'AddressNumber'     => '',
            'AddressComplement' => '',
            'ZipCode'           => '',
            'Neighborhood'      => '',
            'City'              => '',
            'State'             => '',
            'Country'           => ''
        );

        $result  = $this->processSoap($items, $buyer,$creditCard, $paymentMethod->getCardType());

        $this->processTransaction($result, $paymentMethod);

        return $this;
    }

    /**
     * @param array() $items
     * @param array() $buyer
     * @param array() $creditCard
     * @param string $cardType
     *
     * @return object
     */
    private function processSoap($items, $buyer, $creditCard, $cardType)
    {
        $provider = $this->apiPrefix.$cardType;
        $subject = '';
        $message = '';

        if ($this->dineromail_api_debug) {
            // this is a debug environment
            //to debug dineromail API, we use the credit card select
            //to map success/fail values to pass to the transaction_id
            //field, which dineromail API uses
            switch ($cardType)
            {
                case 'VISA': // OK
                    $merchantTransactionId = '1';
                    break;
                case 'MASTER': // DENIED
                    $merchantTransactionId = '2';
                    break;
                case 'AMEX': // ERROR
                    $merchantTransactionId = '3';
                    break;
                default: // ERROR
                    $merchantTransactionId = '4';
                    break;
            }
        } else {
            $merchantTransactionId =  $this->paymentBridge->getOrderId(). '#'.  date('Ymdhis');
        }

         $uniqueMessageId = date('Ymdhis') . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

         $stringItems = '';
         $soapItems = array();
         foreach($items as &$item){
             /* UGLY HACK NEEDED UNTIL PAYMENTBRIDGE HIERARCHY WILL BE REFACTORED */
             $item['Amount'] = number_format($item['Amount'] / 100, 2, '.', '');
             $stringItems .= $item['Amount'].$item['Code'].$item['Currency'].$item['Description'].$item['Name'].$item['Quantity'];
             $soapItems[] = $this->soapVar($item, 'Item');
         }

        $stringBuyer = $buyer['Name'].$buyer['LastName'].$buyer['Email'].$buyer['Address'].$buyer['Phone'].$buyer['Country'].
            $buyer['City'];
        $stringCreditCard = $creditCard['Installment'].$creditCard['CreditCardNumber'].$creditCard['Holder'].
            $creditCard['ExpirationDate'].$creditCard['SecurityCode'].$creditCard['DocumentNumber'].$creditCard['Address'].
            $creditCard['AddressNumber'].$creditCard['AddressComplement'].$creditCard['ZipCode'].$creditCard['Neighborhood'].
            $creditCard['City'].$creditCard['State'].$creditCard['Country'];
        $cadena = $merchantTransactionId.$uniqueMessageId.$stringItems.$stringBuyer.$stringCreditCard.$provider.$subject.
            $message.$this->apiPassword;
        $hash = MD5($cadena);

        $client = new \SoapClient($this->wsdl, array('trace' => 1, 'exceptions' => 1));
        $soapCredentials = $this->soapVar(array('APIUserName' => $this->apiUserName, 'APIPassword' => $this->apiPassword), 'APICredential');

        $soapBuyer = $this->soapVar($buyer,'Buyer');
        $soapCreditCard = $this->soapVar($creditCard,'CreditCard');

        $request = array(
            'Credential'            => $soapCredentials,
            'Crypt'                 => false,
            'MerchantTransactionId' => $merchantTransactionId,
            'Items'                 => $soapItems,
            'Buyer'                 => $soapBuyer,
            'Provider'              => $provider,
            'CreditCard'            => $soapCreditCard,
            'Subject'               => $subject,
            'Message'               => $message,
            'UniqueMessageId'       => $uniqueMessageId,
            'Hash'                  => $hash
        );
        $this->logger->addInfo('Request Send DineromailApi'.'processTransaction Response', $request);

        return $client->DoPAymentWithCreditCard($request)->DoPaymentWithCreditCardResult;
    }


    /**
     * @param $data
     * @param $typeName
     * @return SoapVar
     */
    private function soapVar($data, $typeName)
    {
        return new SoapVar($data, SOAP_ENC_OBJECT, $typeName, $this->apiNs);
    }


    /**
     *
     * @param $result \soap response
     * @param DineromailApiMethod $paymentMethod Payment method
     *
     * @throws \Mmoreram\PaymentCoreBundle\Exception\PaymentException
     * @return DineromailApiMethod Self object
     *
     */
    private function processTransaction($result, DineromailApiMethod $paymentMethod)
    {
        $this->logger->addInfo($paymentMethod->getPaymentName().'processTransaction Result', get_object_vars($result));

        $paymentMethod->setDineromailApiReference($result->MerchantTransactionId);
        $paymentMethod->setDineromailApiTransactionId($result->TransactionId);

        /**
         * Payment paid done
         *
         * Paid process has ended ( No matters result )
         */
        $this->paymentEventDispatcher->notifyPaymentOrderDone($this->paymentBridge, $paymentMethod);

        switch ($result->Status)
        {
            case 'OK':
            case 'COMPLETED':
                $this->paymentEventDispatcher->notifyPaymentOrderSuccess($this->paymentBridge, $paymentMethod);
                break;
            case 'PENDING':
                break;
            default:
                $this->paymentEventDispatcher->notifyPaymentOrderFail($this->paymentBridge, $paymentMethod);
                throw new PaymentException;
        }

        return $this;
    }
}
