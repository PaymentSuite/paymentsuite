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

namespace PaymentSuite\PayUBundle\Model\Abstracts;

/**
 * Abstract Model class for report request models
 */
abstract class PayuReportRequest extends PayuRequest
{
    /**
     * @var PayuDetails
     *
     * details
     */
    protected $details;

    /**
     * Sets Details
     *
     * @param PayuDetails $details Details
     *
     * @return PayuReportRequest Self object
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get Details
     *
     * @return PayuDetails Details
     */
    public function getDetails()
    {
        return $this->details;
    }
}
