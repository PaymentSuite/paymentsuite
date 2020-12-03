<?php

namespace PaymentSuite\RedsysBundle\Services;

use PaymentSuite\RedsysBundle\Exception\CurrencyNotSupportedException;
use PaymentSuite\RedsysBundle\Util\CurrencyNumber;

/**
 * @author Gerard Rico <grico@wearemarketing.com>
 */
final class Redsys3dSecureBuilder
{
    private $parameters = [];

    public function addCardholderName(string $name): self
    {
        return $this->addTextField('cardholderName', $name, 45);
    }

    public function addEmail(string $email): self
    {
        return $this->addTextField('email', $email, 254);
    }

    public function addHomePhone(string $prefix, string $number): self
    {
        return $this->addPhone('homePhone', $prefix, $number);
    }

    public function addMobilePhone(string $prefix, string $number): self
    {
        return $this->addPhone('mobilePhone', $prefix, $number);
    }

    public function addWorkPhone(string $prefix, string $number): self
    {
        return $this->addPhone('workPhone', $prefix, $number);
    }

    public function addShippingAddressLine1(string $addressLine): self
    {
        return $this->addTextField('shipAddrLine1', $addressLine, 50);
    }

    public function addShippingAddressLine2(string $addressLine): self
    {
        return $this->addTextField('shipAddrLine2', $addressLine, 50);
    }

    public function addShippingAddressLine3(string $addressLine): self
    {
        return $this->addTextField('shipAddrLine3', $addressLine, 50);
    }

    public function addShippingAddressCity(string $city): self
    {
        return $this->addTextField('shipAddrCity', $city, 50);
    }

    public function addShippingAddressPostalCode(string $postalCode): self
    {
        return $this->addTextField('shipAddrPostCode', $postalCode, 16);
    }

    /**
     * @param string $state ISO 3166-2 code of the shipping address state.
     *
     * @return $this
     */
    public function addShippingAddressState(string $state): self
    {
        return $this->addTextField('shipAddrState', $state, 3);
    }

    /**
     * @param string $country ISO 3166-1 code of the shipping address country
     *
     * @return Redsys3dSecureBuilder
     */
    public function addShippingAddressCountry(string $country): self
    {
        return $this->addTextField('shipAddrCountry', $country, 3);
    }

    public function addBillingAddressLine1(string $addressLine): self
    {
        return $this->addTextField('billAddrLine1', $addressLine, 50);
    }

    public function addBillingAddressLine2(string $addressLine): self
    {
        return $this->addTextField('billAddrLine2', $addressLine, 50);
    }

    public function addBillingAddressLine3(string $addressLine): self
    {
        return $this->addTextField('billAddrLine3', $addressLine, 50);
    }

    public function addBillingAddressCity(string $city): self
    {
        return $this->addTextField('billAddrCity', $city, 50);
    }

    public function addBillingAddressPostalCode(string $postalCode): self
    {
        return $this->addTextField('billAddrPostCode', $postalCode, 16);
    }

    /**
     * @param string $state ISO 3166-2 code of the billing address state.
     *
     * @return $this
     */
    public function addBillingAddressState(string $state): self
    {
        return $this->addTextField('billAddrState', $state, 3);
    }

    /**
     * @param string $country ISO 3166-1 code of the billing address country
     *
     * @return Redsys3dSecureBuilder
     */
    public function addBillingAddressCountry(string $country): self
    {
        return $this->addTextField('billAddrCountry', $country, 3);
    }

    /**
     * @param \DateTime $date DateTime when the client was authenticated
     *
     * @return Redsys3dSecureBuilder
     */
    public function addAuthenticationDate(\DateTime $date): self
    {
        $this->parameters['threeDSRequestorAuthenticationInfo']['threeDSReqAuthTimestamp'] = $date->format('YmdHi');

        return $this;
    }

    /**
     * @param \DateTime|null $date Last time client password was changed. Use null if the password was never changed
     *
     * @return Redsys3dSecureBuilder
     */
    public function addLastPasswordChangeDate(?\DateTime $date): self
    {
        if (is_null($date)) {
            $this->parameters['acctInfo']['chAccPwChangeInd'] = '01';
        } else {
            $this->parameters['acctInfo']['chAccPwChange'] = $date->format('YmdHi');
        }

        return $this;
    }

    /**
     * @param int $purchaseCount Number of purchases performed by the client on the last 6 months
     *
     * @return Redsys3dSecureBuilder
     */
    public function addPurchaseCount(int $purchaseCount): self
    {
        $this->parameters['acctInfo']['nbPurchaseAccount'] = $purchaseCount;

        return $this;
    }

    /**
     * @param int $transactionCount Number of transactions performed by the client on the last 24 hours
     *
     * @return $this
     */
    public function addDayTransactionCount(int $transactionCount): self
    {
        $this->parameters['acctInfo']['txnActivityDay'] = $transactionCount;

        return $this;
    }

    /**
     * @param int $transactionCount Number of transactions performed by the client on the last year
     *
     * @return $this
     */
    public function addYearTransactionCount(int $transactionCount): self
    {
        $this->parameters['acctInfo']['txnActivityYear'] = $transactionCount;

        return $this;
    }

    public function addLastShippingAddressUsageDate(?\DateTime $dateTime): self
    {
        if (is_null($dateTime)) {
            $this->parameters['acctInfo']['shipAddressUsageInd'] = '01';

            return $this;
        }

        $this->parameters['acctInfo']['shipAddressUsage'] = $dateTime->format('Ymd');
        $daysSinceLastUsage = $dateTime->diff(new \DateTime(), true)->days;

        if ($daysSinceLastUsage < 30) {
            $this->parameters['acctInfo']['shipAddressUsageInd'] = '02';
        } elseif ($daysSinceLastUsage <= 60) {
            $this->parameters['acctInfo']['shipAddressUsageInd'] = '03';
        } else {
            $this->parameters['acctInfo']['shipAddressUsageInd'] = '04';
        }

        return $this;
    }

    public function addClientNameEqualsShippingName(bool $isEqual): self
    {
        $this->parameters['acctInfo']['shipNameIndicator'] = $isEqual ? '01' : '02';

        return $this;
    }

    public function addSuspiciousActivityDetected(bool $detected): self
    {
        $this->parameters['acctInfo']['suspiciousAccActivity'] = !$detected ? '01' : '02';

        return $this;
    }

    public function addDeliveryEmail(string $email): self
    {
        $this->parameters['merchantRiskIndicator']['deliveryEmailAddress'] = substr($email, 0, 254);

        return $this;
    }

    /**
     * @param int|null $days Delivery days. Null for electronic delivery
     * @return $this
     */
    public function addDeliveryTimeframe(?int $days): self
    {
        if (is_null($days)) {
            $this->parameters['merchantRiskIndicator']['deliveryTimeframe'] = '01';
        } elseif (0 == $days) {
            $this->parameters['merchantRiskIndicator']['deliveryTimeframe'] = '02';
        } elseif (1 == $days) {
            $this->parameters['merchantRiskIndicator']['deliveryTimeframe'] = '03';
        } else {
            $this->parameters['merchantRiskIndicator']['deliveryTimeframe'] = '04';
        }

        return $this;
    }

    /**
     * @param int $amount Gift amount without decimals
     *
     * @return $this
     */
    public function addGiftCardAmount(int $amount): self
    {
        $this->parameters['merchantRiskIndicator']['giftCardAmount'] = $amount;

        return $this;
    }

    /**
     * @param int $count Number of purchased gift cards
     * @return $this
     */
    public function addGiftCardCount(int $count): self
    {
        $this->parameters['merchantRiskIndicator']['giftCardCount'] = $count;

        return $this;
    }

    /**
     * @param string $code ISO 4217 currency code
     *
     * @return Redsys3dSecureBuilder
     *
     * @throws CurrencyNotSupportedException
     */
    public function addGiftCardCurrency(string $code): self
    {
        $this->parameters['merchantRiskIndicator']['giftCardCurr'] = CurrencyNumber::fromCode($code);

        return $this;
    }

    /**
     * @param \DateTime $date Expected date of availability of the merchandise in purchases with reservation
     *
     * @return $this
     */
    public function addPreOrderDate(\DateTime $date): self
    {
        $this->parameters['merchantRiskIndicator']['preOrderDate'] = $date->format('Ymd');

        return $this;
    }

    /**
     * @param bool $isAvailable Pre order merchandise is available
     *
     * @return Redsys3dSecureBuilder
     */
    public function addPreOrderMerchandiseAvailable(bool $isAvailable): self
    {
        $this->parameters['merchantRiskIndicator']['preOrderPurchaseInd'] = $isAvailable ? '01' : '02';

        return $this;
    }

    /**
     * @param bool $isReorder Indicates if the user has purchased the merchandise previously
     *
     * @return Redsys3dSecureBuilder
     */
    public function addReorderIndicator(bool $isReorder): self
    {
        $this->parameters['merchantRiskIndicator']['reorderItemsInd'] = !$isReorder ? '01' : '02';

        return $this;
    }

    public function addShippingToCustomerMainAddress(): self
    {
        $this->parameters['merchantRiskIndicator']['shipIndicator'] = '01';

        return $this;
    }

    public function addShippingToNotCustomerMainAddress(): self
    {
        $this->parameters['merchantRiskIndicator']['shipIndicator'] = '02';

        return $this;
    }

    public function addShippingToNotCustomerAddress(): self
    {
        $this->parameters['merchantRiskIndicator']['shipIndicator'] = '03';

        return $this;
    }

    public function addShippingToPickupPoint(): self
    {
        $this->parameters['merchantRiskIndicator']['shipIndicator'] = '04';

        return $this;
    }

    public function addShippingDigitalMerchandise(): self
    {
        $this->parameters['merchantRiskIndicator']['shipIndicator'] = '05';

        return $this;
    }

    public function addShippingElectronicTickets(): self
    {
        $this->parameters['merchantRiskIndicator']['shipIndicator'] = '06';

        return $this;
    }

    public function addShippingOthers(): self
    {
        $this->parameters['merchantRiskIndicator']['shipIndicator'] = '07';

        return $this;
    }

    public function get(): array
    {
        $this->addAddressMatch();
        $this->addPasswordChangeIndicator();

        return $this->parameters;
    }

    private function addPhone(string $field, string $prefix, string $number): self
    {
        $this->parameters[$field] = [
            'cc' => substr($prefix, 0, 3),
            'subscriber' => substr($number, 0, 15),
        ];

        return $this;
    }

    private function addTextField(string $field, string $text, int $maxLength): self
    {
        $this->parameters[$field] = substr($text, 0, $maxLength);

        return $this;
    }

    private function addAddressMatch(): void
    {
        $shippingAddress = array_filter([
            $this->parameters['shipAddrLine1'] ?? null,
            $this->parameters['shipAddrLine2'] ?? null,
            $this->parameters['shipAddrLine3'] ?? null,
            $this->parameters['shipAddrCity'] ?? null,
            $this->parameters['shipAddrPostCode'] ?? null,
            $this->parameters['shipAddrState'] ?? null,
            $this->parameters['shipAddrCountry'] ?? null,
        ]);

        $billingAddress = array_filter([
            $this->parameters['billAddrLine1'] ?? null,
            $this->parameters['billAddrLine2'] ?? null,
            $this->parameters['billAddrLine3'] ?? null,
            $this->parameters['billAddrCity'] ?? null,
            $this->parameters['billAddrPostCode'] ?? null,
            $this->parameters['billAddrState'] ?? null,
            $this->parameters['billAddrCountry'] ?? null,
        ]);

        if (empty($shippingAddress) && empty($billingAddress)) {
            return;
        }

        $this->parameters['addrMatch'] = ($shippingAddress == $billingAddress) ? 'Y' : 'N';
    }

    private function addPasswordChangeIndicator(): void
    {
        $passChangeFormattedDate = $this->parameters['acctInfo']['chAccPwChange'] ?? null;

        if (is_null($passChangeFormattedDate)) {
            return;
        }

        $passChangeDate = \DateTime::createFromFormat('YmdHi', $passChangeFormattedDate);

        $authFormattedDate = $this->parameters['threeDSRequestorAuthenticationInfo']['threeDSReqAuthTimestamp'] ?? null;
        $authDate = \DateTime::createFromFormat('YmdHi', $authFormattedDate);

        if ($authDate && $authDate <= $passChangeDate) {
            $this->parameters['acctInfo']['chAccPwChangeInd'] = '02';

            return;
        }

        $daysSinceChange = $passChangeDate->diff(new \DateTime(), true)->days;

        if ($daysSinceChange < 30) {
            $this->parameters['acctInfo']['chAccPwChangeInd'] = '03';
        } elseif ($daysSinceChange <= 60) {
            $this->parameters['acctInfo']['chAccPwChangeInd'] = '04';
        } else {
            $this->parameters['acctInfo']['chAccPwChangeInd'] = '05';
        }

    }
}
