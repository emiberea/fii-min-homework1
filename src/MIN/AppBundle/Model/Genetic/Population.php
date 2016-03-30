<?php

namespace MIN\AppBundle\Model\Genetic;

class Population
{
    /** @var array|Chromosome[] $chromosomes */
    private $chromosomes;

    /** @var float $fitness */
    private $fitness;

    /** @var float $eval */
    private $eval;

    /**
     * Population constructor.
     * @param array|null $chromosomes
     */
    public function __construct(array $chromosomes = null)
    {
        $this->chromosomes = $chromosomes;
    }

    /**
     * @return array|Chromosome[]
     */
    public function getChromosomes()
    {
        return $this->chromosomes;
    }

    /**
     * @param array|Chromosome[] $chromosomes
     * @return $this
     */
    public function setChromosomes($chromosomes)
    {
        $this->chromosomes = $chromosomes;

        return $this;
    }

    /**
     * @param Chromosome $chromosome
     * @return $this
     */
    public function addChromosome(Chromosome $chromosome)
    {
        $this->chromosomes[] = $chromosome;

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
}
