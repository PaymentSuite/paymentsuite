<?php

/**
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PayUBundle\Model;

use JMS\Serializer\Annotation as JMS;

use PaymentSuite\PayuBundle\Model\Abstracts\PayuReportResponse;

/**
 * TransactionResponseDetail Response Model
 */
class TransactionResponseDetailResponse extends PayuReportResponse
{
    /**
     * @var TransactionResponseDetailResult
     * @JMS\Type("PaymentSuite\PayuBundle\Model\TransactionResponseDetailResult")
     *
     * result
     */
    protected $result;

    /**
     * @var string
     * @JMS\Type("string")
     *
     * error
     */
    protected $error;

    /**
     * Sets Error
     *
     * @param string $error Error
     *
     * @return TransactionResponseDetailResponse Self object
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get Error
     *
     * @return string Error
     */
    public function getError()
    {
        return $this->error;
    }
}
