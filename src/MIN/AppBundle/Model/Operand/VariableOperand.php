<?php

namespace MIN\AppBundle\Model\Operand;

use MIN\AppBundle\Model\Helper\Variable;

class VariableOperand implements OperandInterface
{
    /** @var IndexOperand $indexOperand */
    private $indexOperand;

    /** @var Variable $variable */
    private $variable;

    /** @var int $delta */
    private $delta;

    /**
     * @param IndexOperand $indexOperand
     * @param Variable $variable
     * @param $delta
     */
    public function __construct(IndexOperand $indexOperand = null, Variable $variable, $delta = 0)
    {
        $this->indexOperand = $indexOperand;
        $this->variable = $variable;
        $this->delta = $delta;
    }

    public function __toString()
    {
        return $this->variable->getName() . ($this->indexOperand == null ? '' : '(i' . ($this->delta == 0 ? '' : (($this->delta < 0 ? '-' : '+') . $this->delta)) . ')');
    }

    /**
     * @return float
     */
    public function getValue()
    {
//        var_dump($this->variable->getValues());
//        var_dump($this->getIndex());
//        var_dump($this->delta);
//        die;
        $key = $this->getIndex() + $this->delta;
        return $this->variable->getValues()[$key];
    }

    /**
     * @return int
     */
    private function getIndex()
    {
        return $this->indexOperand == null ? 0 : ((int)$this->indexOperand->getValue() - 1);
    }
}
