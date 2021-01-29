<?php


namespace PaymentSuite\PaylandsBundle\Util;

use PaymentSuite\PaylandsBundle\Exception\InvalidValueException;

/**
 * @author Juanjo Martínez <jmartinez@wearemarketing.com>
 */
final class DocumentIdentificationType
{
    const OPTIONS = [
        'ALIEN_REGISTRATION_CARD',
        'ELECTOR_CREDENTIAL',
        'FISCAL_IDENTIFICATION_CODE',
        'ORIGIN_COUNTRY_IDENTIFICATION_CODE',
        'FOREIGN_IDENTIFICATION_DOCUMENT',
        'NATIONAL_IDENTITY_DOCUMENT',
        'OTHER_PHYSICAL_PERSON_DOCUMENTS',
        'DRIVER_LICENSE',
        'CONSULAR_REGISTRATION',
        'UNDER_AGE',
        'RESIDENCE_CARD',
        'TAX_IDENTIFICATION_NUMBER',
        'NON_DRIVER_LICENSE_PHOTO_ID',
        'VALID_PASSPORT',
        'DIPLOMAT_IDENTITY_CARD',
        'US_GOVERNMENT_ISSUER_ID',
        'UNIQUE_ID_ISSUED_BY_MT',
        'NATIONAL_IDENTITY_DOCUMENT'
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
