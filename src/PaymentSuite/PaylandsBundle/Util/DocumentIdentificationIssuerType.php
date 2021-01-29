<?php


namespace PaymentSuite\PaylandsBundle\Util;

use PaymentSuite\PaylandsBundle\Exception\InvalidValueException;

/**
 * @author Juanjo Martínez <jmartinez@wearemarketing.com>
 */
final class DocumentIdentificationIssuerType
{
    const OPTIONS = [
        'STATE_GOVERNMENT',
        'FEDERAL_GOVERNMENT',
        'MONEY_TRANSMITTER',
        'PROFESSIONAL_ASSOCIATION',
    ];

    private $value;

    /**
     * DocumentIdentificationIssuerType constructor.
     * @param $value
     */
    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function create(string $value): self
    {
        if(!in_array($value, self::OPTIONS)){
            throw new InvalidValueException(self::class, $value);
        }

        return new self($value);
    }

    public function __toString()
    {
        return $this->value;
    }
}
