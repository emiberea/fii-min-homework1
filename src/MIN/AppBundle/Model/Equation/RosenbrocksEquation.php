<?php

namespace MIN\AppBundle\Model\Equation;

use MIN\AppBundle\Model\Expression\BooleanExpressionInterface;
use MIN\AppBundle\Model\Expression\FloatExpressionInterface;
use MIN\AppBundle\Model\Functions\SumFunction;
use MIN\AppBundle\Model\Helper\Variable;
use MIN\AppBundle\Model\Operand\ConstantOperand;
use MIN\AppBundle\Model\Operand\IndexOperand;
use MIN\AppBundle\Model\Operand\VariableOperand;
use MIN\AppBundle\Model\Operator\BetweenOperator;
use MIN\AppBundle\Model\Operator\MinusOperator;
use MIN\AppBundle\Model\Operator\MultiplyOperator;
use MIN\AppBundle\Model\Operator\PlusOperator;
use MIN\AppBundle\Model\Operator\PowOperator;

class RosenbrocksEquation
{
    const EQUATION_STRING = 'sum(100·(x(i+1)-x(i)^2)^2+(1-x(i))^2), i=1:n-1; -2.048<=x(i)<=2.048';

    /** @var Variable $n */
    private $n;

    /** @var Variable $X */
    private $X;

    /** @var BooleanExpressionInterface $constraint */
    private $constraint;

    /** @var FloatExpressionInterface $expression */
    private $expression;

    /** @var RosenbrocksEquation $instance */
    private static $instance;

    /**
     * @return RosenbrocksEquation
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new RosenbrocksEquation();
        }

        return self::$instance;
    }

    /**
     * @param $n
     * @return Equation
     */
    public function getEquation($n)
    {
        $this->buildVariables();
        $this->buildConstraint();
        $this->buildExpression();
        $this->setVariablesValues($n);

        return new Equation($this->expression, $this->n, $this->X, $this->constraint, self::EQUATION_STRING);
    }

    private function buildVariables()
    {
        $this->n = new Variable('n');
        $this->X = new Variable('x');
    }

    private function buildConstraint()
    {
        $this->constraint = new BetweenOperator($this->X, new ConstantOperand(-2.048), new ConstantOperand(2.048));
    }

    private function buildExpression()
    {
        $indexOperand = new IndexOperand(new ConstantOperand(1), new MinusOperator(new VariableOperand(null, $this->n), new ConstantOperand(1)));

        $two = new ConstantOperand(2);
        $xOperand = new VariableOperand($indexOperand, $this->X);
        $diff = new MinusOperator(new VariableOperand($indexOperand, $this->X, 1), new PowOperator($xOperand, $two));
        $square = new PowOperator($diff, $two);
        $prod = new MultiplyOperator(new ConstantOperand(100), $square);
        $square2 = new PowOperator(new MinusOperator(new ConstantOperand(1), $xOperand), $two);
        $plus = new PlusOperator($prod, $square2);

        $this->expression = new SumFunction($plus, $indexOperand);
    }

    /**
     * @param $n
     */
    private function setVariablesValues($n)
    {
        $this->n->setValue($n);
    }
}
