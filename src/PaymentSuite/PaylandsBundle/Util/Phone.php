<?php


namespace PaymentSuite\PaylandsBundle\Util;

use PaymentSuite\PaylandsBundle\Util\Interfaces\ArrayableInterface;

/**
 * @author Juanjo Martínez <jmartinez@wearemarketing.com>
 */
final class Phone implements ArrayableInterface
{
    private $number;

    private $prefix;

    private function __construct(string $number, string $prefix = null)
    {
        $this->number = $number;
        $this->prefix = $prefix;
    }

    public static function create(string $number, string $prefix = null): self
    {
        return new self($number, $prefix);
    }

    public function toArray(): array
    {
        return array_filter([
            'number' => $this->number,
            'prefix' => $this->prefix,
        ]);
    }
}
