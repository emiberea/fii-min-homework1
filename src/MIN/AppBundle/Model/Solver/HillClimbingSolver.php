<?php

namespace MIN\AppBundle\Model\Solver;

use MIN\AppBundle\Model\Equation\Equation;
use MIN\AppBundle\Model\Helper\Constants;
use MIN\AppBundle\Model\Helper\Utils;

class HillClimbingSolver
{
    /** @var integer $noOfBits */
    private $noOfBits;

    /** @var Equation $equation */
    private $equation;

    public function solve(Equation $equation, array $startingSolution = null)
    {
        $this->equation = $equation;
        $size = $this->equation->getN()->getValue(); // 3
        $maxValue = $this->equation->getConstraint()->getMaxValue(); // 5.12

        $this->noOfBits = (int) ceil(log($maxValue * pow(10, Constants::SOLUTION_PRECISION))) + 1; // 10
//        $this->equation->getX()->setValues(array());
        $this->equation->getX()->setValues(new \SplFixedArray($size));

        $best = new Solution();
        $values = ($startingSolution == null) ? $this->select($size) : $startingSolution;
        $best->setValues($values);
        $best->setFunctionResult($this->evaluate($best->getValues()));

        $steps = 0;
        $precisionValue = 1 / pow(10, Constants::PRECISION);

        while ($steps++ < Constants::HC_MAX_STEPS && (abs($best->getFunctionResult() - $precisionValue) > $precisionValue)) {
            $current = new Solution();
            $current->setValues($this->select($size));
            $current->setFunctionResult($this->evaluate($current->getValues()));

            $steps2 = 0;
            while (!Constants::USE_FIRST_IMPROVEMENT || $steps2++ < 20) {
                $improved = $this->improve($current);
                if ($improved->getFunctionResult() < $current->getFunctionResult()) {
                    $current = $improved;
                } else {
                    break;
                }
            }

            if ($current->getFunctionResult() < $best->getFunctionResult()) {
                $best->setFunctionResult($current->getFunctionResult());
                $destinationArray = $current->getValues();
                $best->setValues($destinationArray);
            }
        }

        $this->setEquationSolution($best);

        return $best;
    }

    /**
     * @return Equation
     */
    public function getEquation()
    {
        return $this->equation;
    }

    /**
     * @param Equation $equation
     * @return $this
     */
    public function setEquation($equation)
    {
        $this->equation = $equation;

        return $this;
    }

    /**
     * @param int $len
     * @return array
     */
    private function select($len)
    {
        $result = array();
        for ($i = 0; $i < $len; $i++) {
            $result[$i] = Utils::generate($this->noOfBits);
        }

        return $result;
    }

    /**
     * @param array $solution
     * @return float
     */
    private function evaluate(array $solution)
    {
        $constraint = $this->equation->getConstraint();
        $x = $this->equation->getX();

        for ($i = 0; $i < count($solution); $i++) {
            $x->getValues()[$i] = $constraint->wrapValue($solution[$i], $this->noOfBits, Constants::PRECISION);
        }

        return round($this->equation->getValue(), Constants::PRECISION);
    }

    /**
     * @param Solution $solution
     * @return Solution
     */
    private function improve(Solution $solution)
    {
        $neighbour = $solution->getValues();
        $localBest = $solution->getFunctionResult();

        $bestCurrNeighbour = null;

        for ($i = 0; $i < count($neighbour); $i++) {
            $bestCurrNeighbour = $neighbour[$i];

            for ($j = 0; $j < $this->noOfBits; $j++) {
                $neighbour[$i] = $this->switchBit($solution->getValues()[$i], $j);
                $eval = $this->evaluate($neighbour);
                if ($eval < $localBest) {
                    $localBest = $eval;
                    $bestCurrNeighbour = $neighbour[$i];
                    if (Constants::USE_FIRST_IMPROVEMENT) {
                        return new Solution($neighbour, $localBest);
                    }
                }
            }

            $neighbour[$i] = $bestCurrNeighbour;
        }

        return new Solution($neighbour, $localBest);
    }

    /**
     * @param int $value
     * @param int $j
     * @return int
     */
    private function switchBit($value, $j)
    {
        return $value ^ (1 << $j);
    }

    /**
     * @param Solution $best
     */
    private function setEquationSolution(Solution $best)
    {
        $values = $this->getEquation()->getX()->getValues();
        for ($i = 0; $i < count($values); $i++) {
            $values[$i] = $this->equation->getConstraint()->wrapValue($best->getValues()[$i], $this->noOfBits, Constants::PRECISION);
        }
    }
}
