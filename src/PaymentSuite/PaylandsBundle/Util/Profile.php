<?php


namespace PaymentSuite\PaylandsBundle\Util;

use PaymentSuite\PaylandsBundle\Util\Interfaces\ArrayableInterface;

/**
 * @author Juanjo Martínez <jmartinez@wearemarketing.com>
 */
final class Profile implements ArrayableInterface
{
    private $firstName;

    private $lastName;

    private $cardholderName;

    private $email;

    private $documentIdentificationIssuerType;

    private $documentIdentificationType;

    private $documentIdentificationNumber;

    private $birthdate;

    private $sourceOfFunds;

    private $occupation;

    private $socialSecurityNumber;

    private $phone;

    private $workPhone;

    private $homePhone;

    private $mobilePhone;

    private function __construct(
        string $firstName,
        string $lastName,
        string $cardholderName = null,
        string $email = null,
        DocumentIdentificationIssuerType $documentIdentificationIssuerType = null,
        DocumentIdentificationType $documentIdentificationType = null,
        string $documentIdentificationNumber = null,
        \DateTime $birthdate = null,
        string $sourceOfFunds = null,
        string $occupation = null,
        string $socialSecurityNumber = null,
        Phone $phone = null,
        Phone $workPhone = null,
        Phone $homePhone = null,
        Phone $mobilePhone = null
    )
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->cardholderName = $cardholderName;
        $this->email = $email;
        $this->documentIdentificationIssuerType = $documentIdentificationIssuerType;
        $this->documentIdentificationType = $documentIdentificationType;
        $this->documentIdentificationNumber = $documentIdentificationNumber;
        $this->birthdate = $birthdate;
        $this->sourceOfFunds = $sourceOfFunds;
        $this->occupation = $occupation;
        $this->socialSecurityNumber = $socialSecurityNumber;
        $this->phone = $phone;
        $this->workPhone = $workPhone;
        $this->homePhone = $homePhone;
        $this->mobilePhone = $mobilePhone;
    }

    public static function create(
        string $firstName,
        string $lastName,
        string $cardholderName = null,
        string $email = null,
        DocumentIdentificationIssuerType $documentIdentificationIssuerType = null,
        DocumentIdentificationType $documentIdentificationType = null,
        string $documentIdentificationNumber = null,
        \DateTime $birthdate = null,
        string $sourceOfFunds = null,
        string $occupation = null,
        string $socialSecurityNumber = null,
        Phone $phone = null,
        Phone $workPhone = null,
        Phone $homePhone = null,
        Phone $mobilePhone = null
    ): self
    {

        return new self(
            $firstName,
            $lastName,
            $cardholderName,
            $email,
            $documentIdentificationIssuerType,
            $documentIdentificationType,
            $documentIdentificationNumber, $birthdate,
            $sourceOfFunds,
            $occupation,
            $socialSecurityNumber,
            $phone,
            $workPhone,
            $homePhone,
            $mobilePhone
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'cardholder_name' => $this->cardholderName,
            'email' => $this->email,
            'document_identification_issuer_type' => (string) $this->documentIdentificationIssuerType,
            'document_identification_type' => (string) $this->documentIdentificationType,
            'document_identification_number' => $this->documentIdentificationNumber,
            'birthdate' => $this->birthdate ? $this->birthdate->format('Y-m-d') : $this->birthdate,
            'source_of_funds' => $this->sourceOfFunds,
            'occupation' => $this->occupation,
            'social_security_number' => $this->socialSecurityNumber,
            'phone' => $this->phone ? $this->phone->toArray() : null,
            'work_phone' => $this->workPhone ? $this->workPhone->toArray() : null,
            'home_phone' => $this->homePhone ? $this->homePhone->toArray() : null,
            'mobile_phone' => $this->mobilePhone ? $this->mobilePhone->toArray() : null,
        ]);
    }
}
