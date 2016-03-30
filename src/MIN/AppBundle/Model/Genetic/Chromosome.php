<?php

namespace MIN\AppBundle\Model\Genetic;

class Chromosome
{
    /** @var array $genes */
    private $genes;

    /** @var float $fitness */
    private $fitness;

    /** @var float $eval */
    private $eval;

    /**
     * @param array $genes
     */
    public function __construct(array $genes)
    {
        $this->genes = $genes;
    }

    public function __toString()
    {
        return 'Chromosome{fitness=' . $this->fitness . ', eval=' . $this->eval . ', genes=' . implode(' ', $this->genes) . '}';
    }

    /**
     * @return array
     */
    public function getGenes()
    {
        return $this->genes;
    }

    /**
     * @param array $genes
     * @return $this
     */
    public function setGenes($genes)
    {
        $this->genes = $genes;

        return $this;
    }

    /**
     * @return float
     */
    public function getFitness()
    {
        return $this->fitness;
    }

    /**
     * @param float $fitness
     * @return $this
     */
    public function setFitness($fitness)
    {
        $this->fitness = $fitness;

        return $this;
    }

    /**
     * @return float
     */
    public function getEval()
    {
        return $this->eval;
    }

    /**
     * @param float $eval
     * @return $this
     */
    public function setEval($eval)
    {
        $this->eval = $eval;

        return $this;
    }

    /**
     * @return Chromosome
     */
    public function cloneInstance()
    {
        $destinationArray = $this->genes; // TODO: check if it works directly

        $chromosome = new Chromosome($destinationArray);
        $chromosome->setFitness($this->fitness);

        return $chromosome;
    }
}
