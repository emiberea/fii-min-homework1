<?php

namespace MIN\AppBundle\Model\Equation;

use MIN\AppBundle\Model\Expression\BooleanExpressionInterface;
use MIN\AppBundle\Model\Expression\FloatExpressionInterface;
use MIN\AppBundle\Model\Functions\CosFunction;
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

class RastriginsEquation
{
    const EQUATION_STRING = '10 * n + sum(x(i) ^ 2 - 10 * cos(2 * pi * x(i))), i=1:n; -5.12<=x(i)<=5.12';

    /** @var Variable $n */
    private $n;

    /** @var Variable $X */
    private $X;

    /** @var BooleanExpressionInterface $constraint */
    private $constraint;

    /** @var FloatExpressionInterface $expression */
    private $expression;

    /** @var RastriginsEquation $instance */
    private static $instance;

    /**
     * @return RastriginsEquation
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new RastriginsEquation();
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
        $this->constraint = new BetweenOperator($this->X, new ConstantOperand(-5.12), new ConstantOperand(5.12));
    }

    private function buildExpression()
    {
        $nMultiplied10 = new MultiplyOperator(new ConstantOperand(10), new VariableOperand(null, $this->n));
        $sumExpression = $this->getSumExpression();

        $this->expression = new PlusOperator($nMultiplied10, $sumExpression);
    }

    private function getSumExpression()
    {
        $index = new IndexOperand(new ConstantOperand(1), new VariableOperand(null, $this->n));
        $xOperand = new VariableOperand($index, $this->X);
        $pow = new PowOperator($xOperand, new ConstantOperand(2));

        $cosExpression = new MultiplyOperator(new ConstantOperand(2 * M_PI), $xOperand);
        $tenMultiplyCos = new MultiplyOperator(new ConstantOperand(10), new CosFunction($cosExpression));
        $powMinusCos = new MinusOperator($pow, $tenMultiplyCos);

        return new SumFunction($powMinusCos, $index);
    }

    /**
     * @param $n
     */
    private function setVariablesValues($n)
    {
        $this->n->setValue($n);
    }
}
