<?php

/**
 * PayuBundle for Symfony2
 *
 * This Bundle is part of Symfony2 Payment Suite
 *
 * @package PayuBundle
 */

namespace PaymentSuite\PayUBundle\Factory;

use PaymentSuite\PayuBundle\Model\User;

/**
 * Class UserFactory
 */
class UserFactory
{
    /**
     * Creates an instance of User model
     *
     * @return User model
     */
    public function create()
    {
        $user = new User();

        return $user;
    }
}
