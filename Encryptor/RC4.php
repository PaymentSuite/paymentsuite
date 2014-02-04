<?php

namespace PaymentSuite\BanwireGatewayBundle\Encryptor;

class RC4
{
    private $_sbox = array();

    private $_key = array();

    /**
     * Semilla o palabra clave para la encriptacion.
     *
     * @var string $semilla
     */
    private $_semilla = NULL;

    public function __construct($semilla = NULL)
    {
        $this->_semilla = $semilla;
    }

    private function _RC4Initialize($strPwd)
    {
        $tempSwap = 0;
        $i = 0;
        $b = 0;
        $intLength = 0;
        $intLength = strlen($strPwd);
        for($i = 0; $i <= 255; $i++)
        { // For a = 0 To 255
            $this->_key[$i] = ord(substr($strPwd,$i%$intLength,1));
            $this->_sbox[$i] = $i;
        }
        $b = 0;
        for($i = 0; $i <= 255; $i++)
        { // For a = 0 To 255
            $b = ($b + $this->_sbox[$i] + $this->_key[$i])%256;
            $tempSwap = $this->_sbox[$i];
            $this->_sbox[$i] = $this->_sbox[$b];
            $this->_sbox[$b] = $tempSwap;
        }
    }

    private function _Salaa($plaintxt, $key)
    {
        $this->_RC4Initialize($key);
        $temp = 0;
        $a = 0;
        $i = 0;
        $j = 0;
        $k;
        $cipherby = 0;
        $cipher = "";
        for($a = 0; $a < strlen($plaintxt); $a++)
        {
            $i = ($i + 1)%256;
            $j = ($j + $this->_sbox[$i])%256;
            $temp = $this->_sbox[$i];
            $this->_sbox[$i] = $this->_sbox[$j];
            $this->_sbox[$j] = $temp;
            $k = $this->_sbox[($this->_sbox[$i] + $this->_sbox[$j])%256];
            $cipherby = ord(substr($plaintxt,$a,1)) ^ $k;
            $cipher = $cipher.chr($cipherby);
        }
        return $cipher;
    }

    /**
     * Convierte una cadena a Hexadecimal.
     *
     * @access private
     * @return string
     */
    private function _StringToHexString($b)
    {
        $sb = "";
        for($i = 0; $i < strlen($b); $i++)
        {
            $tmpb = $b;
            $v = ord(substr($tmpb, $i, 1)) & 0xFF;
            if($v < 16)
                $sb = $sb.'0';
            $sb = $sb.dechex($v);
        }
        return $sb;
    }

    /**
     * Convierte un Hexadecimal a cadena.
     *
     * @access private
     * @return string
     */
    private function _HexStringToString($s)
    {
        $Result = "";
        $len = strlen($s)/2;
        for($i=0; $i < $len; $i++)
        {
            $index = $i * 2;
            $v = intval(substr($s,$index,2),16);
            $Result = $Result.chr($v);
        }
        return $Result;
    }

    /**
     * Ejecuta los metodos necesarios para encriptar.
     *
     * @access public
     * @return string
     */
    public function encrypt($string = NULL, $semilla = NULL)
    {
        $this->_semilla = is_null($semilla) ? $this->_semilla : $semilla;
        if(is_null($this->_semilla) || is_null($string)) return FALSE;
        return $this->_StringToHexString($this->_Salaa($string, $this->_semilla));
    }

    /**
     * Ejecuta los metodos necesarios para desencriptar.
     *
     * @access public
     * @return string
     */
    public function decrypt($string = NULL, $semilla = NULL)
    {
        $this->_semilla = is_null($semilla) ? $this->_semilla : $semilla;
        if(is_null($this->_semilla) || is_null($string)) return FALSE;
        return $this->_Salaa($this->_HexStringToString($string), $this->_semilla);
    }

}