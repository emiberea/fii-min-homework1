<?php

namespace MIN\AppBundle\Model\Equation;

use MIN\AppBundle\Model\Expression\BooleanExpressionInterface;
use MIN\AppBundle\Model\Expression\FloatExpressionInterface;
use MIN\AppBundle\Model\Functions\CosFunction;
use MIN\AppBundle\Model\Functions\ProdFunction;
use MIN\AppBundle\Model\Functions\SqrtFunction;
use MIN\AppBundle\Model\Functions\SumFunction;
use MIN\AppBundle\Model\Helper\Variable;
use MIN\AppBundle\Model\Operand\ConstantOperand;
use MIN\AppBundle\Model\Operand\IndexOperand;
use MIN\AppBundle\Model\Operand\VariableOperand;
use MIN\AppBundle\Model\Operator\BetweenOperator;
use MIN\AppBundle\Model\Operator\DivideOperator;
use MIN\AppBundle\Model\Operator\MinusOperator;
use MIN\AppBundle\Model\Operator\PlusOperator;
use MIN\AppBundle\Model\Operator\PowOperator;

class GriewangksEquation
{
    const EQUATION_STRING = 'sum(x(i)^2/4000)-prod(cos(x(i)/sqrt(i)))+1, i=1:n; -600<=x(i)<= 600';

    /** @var Variable $n */
    private $n;

    /** @var Variable $X */
    private $X;

    /** @var BooleanExpressionInterface $constraint */
    private $constraint;

    /** @var FloatExpressionInterface $expression */
    private $expression;

    /** @var GriewangksEquation $instance */
    private static $instance;

    /**
     * @return GriewangksEquation
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new GriewangksEquation();
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
        $this->constraint = new BetweenOperator($this->X, new ConstantOperand(-600), new ConstantOperand(600));
    }

    private function buildExpression()
    {
        $indexOperand = new IndexOperand(new ConstantOperand(1), new VariableOperand(null, $this->n));
        $pow = new PowOperator(new VariableOperand($indexOperand, $this->X), new ConstantOperand(2));
        $div = new DivideOperator($pow, new ConstantOperand(4000));
        $sum = new SumFunction($div, $indexOperand);

        $div = new DivideOperator(new VariableOperand(null, $this->X), new SqrtFunction($indexOperand));
        $cos = new CosFunction($div);
        $prod = new ProdFunction($cos, new IndexOperand(new ConstantOperand(1), new VariableOperand(null, $this->n)));

        $plus = new MinusOperator($sum, $prod);

        $this->expression = new PlusOperator($plus, new ConstantOperand(1));
    }

    /**
     * @param $n
     */
    private function setVariablesValues($n)
    {
        $this->n->setValue($n);
    }
}
