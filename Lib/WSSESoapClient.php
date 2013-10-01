<?php

namespace Scastells\PagosonlineBundle\Lib;

class WSSESoapClient extends \SoapClient
{
    private $user = '';
    private $pass = '';

    public function __construct($url, $user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
        parent::__construct($url);
    }

    function __doRequest($request, $location, $action, $version, $one_way = Null) {

        //var_dump($request);
        $doc = new \DOMDocument('1.0');
        $doc->loadXML($request);

        $objWSSE = new WSSESoap($doc);

        $objWSSE->addUserToken($this->user, $this->pass, FALSE);

        //var_dump($objWSSE->saveXML());
        return parent::__doRequest($objWSSE->saveXML(), $location, $action, $version);
    }

}