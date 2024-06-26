<?php

namespace APY\DataGridBundle\Grid\Exception;

/**
 * Class UnexpectedTypeException.
 *
 * @author  Quentin Ferrer
 */
class UnexpectedTypeException extends \InvalidArgumentException implements ExceptionInterface
{
    /**
     * Constructor.
     *
     * @param string $value
     * @param int    $expectedType
     */
    public function __construct($value, $expectedType)
    {
        parent::__construct(sprintf('Expected argument of type "%s", "%s" given', $expectedType,
            is_object($value) ? $value::class : gettype($value)));
    }
}
