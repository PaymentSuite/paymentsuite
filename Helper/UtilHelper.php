<?php
namespace dpcat237\GoogleWalletBundle\Helper;

use Symfony\Component\Templating\Helper\Helper;
use dpcat237\GoogleWalletBundle\Helper\JWTHelper;

/**
 * Class for time functions
 */
class UtilHelper extends Helper
{
    /**
     * @var string
     */
    public $name = 'UtilHelper';


    /**
     * Returns the canonical name of this helper.
     *
     * @return string The canonical name
     */
    public function getName()
    {
        return $this->name;
    }

    public static function log($msg) {
        error_log($msg);
    }

    /**
     * We use the dollar amounts as micro dollars to keep floating arithmetic sane
     */
    public static function to_dollars($micro_dollars) {
        $d = floatval($micro_dollars) / 1000000 ;
        return number_format($d, 2);
    }

    public static function assert_input($inputs, $required) {
        foreach ($required as $key => $value) {
            if (!in_array($key, $inputs)) {
                header('HTTP/1.0 400 Bad request', true, 400);
                echo "Did not receive $key in the request" ;
                exit();
            }
        }
    }

    public static function encodeJwt($json, $merchantSecret) {
        $jwt = JWTHelper::encode($json, $merchantSecret);

        return $jwt;
    }

    public static function getEmptyClass()
    {
        $stdClass = new stdClass();

        return $stdClass;
    }
}

class stdClass{}