<?php

namespace PaymentSuite\StripeBundle\ValueObject;

interface EditableStripeTransaction
{
    public function setDescription($description);
    public function setEmail($email);
    public function setMetadata($metadata);
}
