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

namespace PaymentSuite\PayUBundle\Model;

use JMS\Serializer\Annotation as JMS;

use PaymentSuite\PayuBundle\Model\Abstracts\PayuResult;

/**
 * TransactionResponseDetail Result Model
 */
class TransactionResponseDetailResult extends PayuResult
{
    /**
     * @var TransactionResponseDetailPayload
     * @JMS\Type("PaymentSuite\PayuBundle\Model\TransactionResponseDetailPayload")
     *
     * payload
     */
    protected $payload;
}
