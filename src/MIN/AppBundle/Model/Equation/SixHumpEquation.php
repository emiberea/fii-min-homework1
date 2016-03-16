<?php

namespace MIN\AppBundle\Model\Equation;

use MIN\AppBundle\Model\Expression\BooleanExpressionInterface;
use MIN\AppBundle\Model\Expression\FloatExpressionInterface;
use MIN\AppBundle\Model\Helper\Variable;
use MIN\AppBundle\Model\Operand\ConstantOperand;
use MIN\AppBundle\Model\Operand\IndexOperand;
use MIN\AppBundle\Model\Operand\VariableOperand;
use MIN\AppBundle\Model\Operator\BetweenOperator;
use MIN\AppBundle\Model\Operator\DivideOperator;
use MIN\AppBundle\Model\Operator\MinusOperator;
use MIN\AppBundle\Model\Operator\MultiplyOperator;
use MIN\AppBundle\Model\Operator\PlusOperator;
use MIN\AppBundle\Model\Operator\PowOperator;

class SixHumpEquation
{
    const EQUATION_STRING = '(4-2.1·x1^2+x1^4/3)·x1^2+x1·x2+(-4+4·x2^2)·x2^2, -3<=x1<=3, -2<=x2<=2';

    /** @var Variable $n */
    private $n;

    /** @var Variable $X */
    private $X;

    /** @var BooleanExpressionInterface $constraint */
    private $constraint;

    /** @var FloatExpressionInterface $expression */
    private $expression;

    /** @var SixHumpEquation $instance */
    private static $instance;

    /**
     * @return SixHumpEquation
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new SixHumpEquation();
        }

        return self::$instance;
    }

    /**
     * @return Equation
     */
    public function getEquation()
    {
        $this->buildVariables();
        $this->buildConstraint();
        $this->buildExpression();
        $this->setVariablesValues(2);

        return new Equation($this->expression, $this->n, $this->X, $this->constraint, self::EQUATION_STRING);
    }

    private function buildVariables()
    {
        $this->n = new Variable('n');
        $this->X = new Variable('x');
    }

    private function buildConstraint()
    {
        $this->constraint = new BetweenOperator($this->X, new ConstantOperand(-2), new ConstantOperand(2));
    }

    private function buildExpression()
    {
        $indexOperand = new IndexOperand(new ConstantOperand(1), new MinusOperator(new VariableOperand(null, $this->n), new ConstantOperand(1)));
        $indexOperand->next();

        $x1 = new VariableOperand($indexOperand, $this->X);
        $x2 = new VariableOperand($indexOperand, $this->X, 1);

        $x1Squared = new PowOperator($x1, new ConstantOperand(2));
        $this->expression = new ConstantOperand(4);
        $this->expression = new MinusOperator($this->expression, new MultiplyOperator(new ConstantOperand(2.1), $x1Squared));
        $this->expression = new PlusOperator($this->expression, new DivideOperator(new PowOperator($x1, new ConstantOperand(4)), new ConstantOperand(3)));
        $this->expression = new MultiplyOperator($this->expression, $x1Squared);

        $this->expression = new PlusOperator($this->expression, new MultiplyOperator($x1, $x2));
        $x2Squared = new PowOperator($x2, new ConstantOperand(2));
        $thirdMemberOfSum = new MultiplyOperator(new PlusOperator(new ConstantOperand(-4), new MultiplyOperator(new ConstantOperand(4), $x2Squared)), $x2Squared);

        $this->expression = new PlusOperator($this->expression, $thirdMemberOfSum);
    }

    /**
     * @param $n
     */
    private function setVariablesValues($n)
    {
        $this->n->setValue($n);
    }
}
