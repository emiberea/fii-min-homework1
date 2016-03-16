<?php

namespace MIN\AppBundle\Model\Operand;

class ConstantOperand implements OperandInterface
{
    /** @var float $value */
    private $value;

    /**
     * @param float $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return strval($this->value);
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }
}
