<?php

namespace MIN\AppBundle\Model\Equation;

use MIN\AppBundle\Model\Expression\BooleanExpressionInterface;
use MIN\AppBundle\Model\Expression\FloatExpressionInterface;
use MIN\AppBundle\Model\Helper\Variable;

class Equation
{
    /** @var Variable $n */
    private $n;

    /** @var Variable $X */
    private $X;

    /** @var BooleanExpressionInterface $constraint */
    private $constraint;

    /** @var FloatExpressionInterface $expression */
    private $expression;

    /** @var string $equationStr */
    public $equationStr = null;

    /**
     * @param FloatExpressionInterface $expression
     * @param Variable $n
     * @param Variable $X
     * @param BooleanExpressionInterface $constraint
     * @param null $equationStr
     */
    public function __construct(FloatExpressionInterface $expression, Variable $n, Variable $X, BooleanExpressionInterface $constraint, $equationStr = null)
    {
        $this->expression = $expression;
        $this->n = $n;
        $this->X = $X;
        $this->constraint = $constraint;
        $this->equationStr = $equationStr;
    }

    public function __toString()
    {
//        var_dump($this->constraint);
//        var_dump($this->constraint->getValue());die;
//        var_dump($this->expression->getValue() . ', ' . $this->constraint->getValue());
        return $this->expression->getValue() . ', ' . $this->constraint->getValue();
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->expression->getValue();
    }

    /**
     * @return BooleanExpressionInterface
     */
    public function getConstraint()
    {
        return $this->constraint;
    }

    /**
     * @return Variable
     */
    public function getN()
    {
        return $this->n;
    }

    /**
     * @return Variable
     */
    public function getX()
    {
        return $this->X;
    }
}
