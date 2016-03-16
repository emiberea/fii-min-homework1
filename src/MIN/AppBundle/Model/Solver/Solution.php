<?php

namespace MIN\AppBundle\Model\Solver;

class Solution
{
    /** @var array $values */
    private $values;
    
    /** @var float $functionResult */
    private $functionResult;

    public function __construct(array $values = null, $functionResult = null)
    {
        $this->values = $values;
        $this->functionResult = $functionResult;
    }
    
    public function __toString()
    {
        return 'Solution{values=' . implode(' ', $this->values) . ', functionResult=' . $this->functionResult . '}';
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setValues(array $values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * @return float
     */
    public function getFunctionResult()
    {
        return $this->functionResult;
    }

    /**
     * @param float $functionResult
     * @return $this
     */
    public function setFunctionResult($functionResult)
    {
        $this->functionResult = $functionResult;

        return $this;
    }
}
