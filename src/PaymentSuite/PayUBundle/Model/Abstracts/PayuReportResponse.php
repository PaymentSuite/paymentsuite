<?php

/*
 * This file is part of the PaymentSuite package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

namespace PaymentSuite\PayUBundle\Model\Abstracts;

/**
 * Abstract Model class for report response models
 */
abstract class PayuReportResponse extends PayuResponse
{
    /**
     * @var PayuResult
     *
     * result
     */
    protected $result;

    /**
     * Sets Result
     *
     * @param PayuResult $result Result
     *
     * @return PayuReportResponse Self object
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get Result
     *
     * @return PayuResult Result
     */
    public function getResult()
    {
        return $this->result;
    }
}
