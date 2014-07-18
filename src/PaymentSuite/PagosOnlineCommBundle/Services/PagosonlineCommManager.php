<?php
namespace PaymentSuite\PagosOnlineCommBundle\Services;

use DOMDocument;
use SoapClient;
use PaymentSuite\PagosonlineCommBundle\Lib\WSSESoap;

class PagosonlineCommManager extends SoapClient
{
    /**
     * @var string
     *
     * user pagosonline
     */
    private $userId;

    /**
     * @var string
     *
     * wsdl pagosonline
     */
    private $wsdl;

    /**
     * @var string
     *
     * password pagosonline
     */
    private $password;

    public function __construct($userId, $password, $wsdl)
    {
        $this->userId = $userId;
        $this->password = $password;
        $this->wsdl = $wsdl;
        parent::__construct($this->wsdl);
    }

    public function __doRequest($request, $location, $action, $version, $one_way = Null)
    {
        //var_dump($request);
        $doc = new DOMDocument('1.0');
        $doc->loadXML($request);

        $objWSSE = new WSSESoap($doc);

        $objWSSE->addUserToken($this->userId, $this->password, FALSE);

        //var_dump($objWSSE->saveXML());
        return parent::__doRequest($objWSSE->saveXML(), $location, $action, $version);
    }
}
