<?php

namespace MIN\AppBundle\Model\Genetic;

use MIN\AppBundle\Model\Equation\Equation;
use MIN\AppBundle\Model\Helper\Constants;
use MIN\AppBundle\Model\Helper\Utils;
use MIN\AppBundle\Model\Solver\HillClimbingSolver;

class GeneticSolver
{

    /** @var integer $noOfBits */
    private $noOfBits;

    /** @var Equation $equation */
    private $equation;

    public function solve(Equation $equation)
    {
        $this->equation = $equation;
        $maxValue = $this->equation->getConstraint()->getMaxValue();

        $this->noOfBits = (int) ceil(log($maxValue * pow(10, Constants::SOLUTION_PRECISION))) + 1;
        $noOfGenes = $this->equation->getN()->getValue();
//        $this->equation->getX()->setValues(array());
        $this->equation->getX()->setValues(new \SplFixedArray($noOfGenes));

        $pk = $this->generateFirstPopulation($noOfGenes);
        $this->evaluatePopulation($pk);

        $prevFittest = $this->getFittest($pk);
        echo $prevFittest . "\n";

        $steps = 0;
        while ($steps++ < Constants::AG_MAX_ITERATIONS) {
            $newPopulation = $this->selectPopulation($pk);
            $oldChromosomes = $newPopulation->getChromosomes();
            $newChromosomes = $this->crossoverChromosomes($pk);

            $chromosomeArr = array_merge($oldChromosomes, $newChromosomes);
            $newPopulation->setChromosomes($chromosomeArr);

            $this->mutatePopulation($newPopulation);

            // improve using hil climbing
            $this->improveUsingHillClimbing($newPopulation);

            $this->evaluatePopulation($newPopulation);
            $pk = $newPopulation;

            $currFittest = $this->getFittest($pk);
            if ($prevFittest->getEval() > $currFittest->getEval()) {
                $prevFittest = $currFittest;
                echo "\nNew fittest: " . $prevFittest . "\n\n";
            } else {
                $pk->getChromosomes()[0] = $prevFittest->cloneInstance();
            }

            echo "Current fittest: " . $currFittest . "\n";
        }

        $this->setEquationSolution($prevFittest);

        return $prevFittest;
    }

    /**
     * @return Equation
     */
    public function getEquation()
    {
        return $this->equation;
    }

    /**
     * @param int $noOfGenes
     * @return Population
     */
    private function generateFirstPopulation($noOfGenes)
    {
        $result = new Population();
        for ($i = 0; $i < Constants::AG_POPULATION_SIZE; $i++) {
            $result->addChromosome($this->generateChromosome($noOfGenes));
        }

        return $result;
    }

    /**
     * @param int $noOfGenes
     * @return Chromosome
     */
    private function generateChromosome($noOfGenes)
    {
        $result = array();
        for ($i = 0; $i < $noOfGenes; $i++) {
            $result[$i] = Utils::generate($this->noOfBits);
        }

        return new Chromosome($result);
    }

    /**
     * @param Population $population
     */
    private function evaluatePopulation(Population $population)
    {
        $fitness = 0;
        $totalEval = 0;

        foreach ($population->getChromosomes() as $chromosome) {
            $eval = $this->evaluateChromosome($chromosome);
            $chromosome->setFitness(round(1 / $eval, Constants::PRECISION));
            $chromosome->setEval($eval);

            $fitness += $chromosome->getFitness();
        }

        $population->setEval($totalEval);
        $population->setFitness($fitness);
    }

    /**
     * @param Chromosome $chromosome
     * @return float
     */
    private function evaluateChromosome(Chromosome $chromosome)
    {
        // TODO: review here
        for ($i = 0; $i < count($chromosome->getGenes()); $i++) {
            $this->equation->getX()->getValues()[$i] = $this->equation->getConstraint()->wrapValue($chromosome->getGenes()[$i], $this->noOfBits, Constants::PRECISION);
        }

        return round($this->equation->getValue(), Constants::PRECISION);
    }

    /**
     * @param Population $pk
     * @return Chromosome|null
     * @throws \Exception
     */
    private function getFittest(Population $pk)
    {
        $fittest = array_key_exists(0, $pk->getChromosomes()) ? $pk->getChromosomes()[0] : null;
        if (!$fittest) {
            throw new \Exception('There is no first chromosome');
        }

        foreach ($pk->getChromosomes() as $chromosome) {
            if ($fittest->getFitness() > $chromosome->getFitness()) {
                $fittest = $chromosome;
            }
        }

        return $fittest;
    }

    private function selectPopulation(Population $pk)
    {
        $copyCount = (int)((1 - Constants::AG_CROSSOVER_REPLACEMENT) * count($pk->getChromosomes()) + 0.5);
        $toCopy = array();

        while (count($toCopy) != $copyCount) {
            $toCopy[] = $this->chooseChromosome($pk);
        }

        return new Population($toCopy);
    }

    private function chooseChromosome(Population $pk)
    {
        $r = round(Utils::randomFloat(), Constants::PRECISION);
        $sum = 0;

        foreach ($pk->getChromosomes() as $chromosome) {
            $sum += $chromosome->getFitness() / $pk->getFitness();
            if ($r < $sum) {
                return $chromosome;
            }
        }

        // TODO: review if this works
        return $pk->getChromosomes()[count($pk->getChromosomes()) - 1];
    }

    /**
     * @param Population $pk
     * @return array
     */
    private function crossoverChromosomes(Population $pk)
    {
        $result = array();
        $countToCrossover = Constants::AG_CROSSOVER_REPLACEMENT * count($pk->getChromosomes());

        for ($i = 0; $i < $countToCrossover / 2; $i++) {
            $first = $this->chooseChromosome($pk);
            $second = $this->chooseChromosome($pk);
            $crossoverPosition = mt_rand(0, count($first->getGenes()) - 1);

            $firstGenes = array();
            for ($j = 0; $j < count($first->getGenes()); $j++) {
                if ($j  < $crossoverPosition) {
                    $firstGenes[$j] = $first->getGenes()[$j];
                } else {
                    $firstGenes[$j] = $second->getGenes()[$j];
                }
            }

            $result[] = new Chromosome($firstGenes);

            $secondGenes = array();
            for ($j = 0; $j < count($first->getGenes()); $j++) {
                if ($i  < $crossoverPosition) {
                    $secondGenes[$j] = $second->getGenes()[$j];
                } else {
                    $secondGenes[$j] = $first->getGenes()[$j];
                }
            }
            $result[] = new Chromosome($secondGenes);
        }

        return $result;
    }

    private function mutatePopulation(Population $population)
    {
        $chromosomes = $population->getChromosomes();
        $chromosomesSize = count($chromosomes);

        for ($i = 0; $i < Constants::AG_MUTATION_RATE * $chromosomesSize; $i++) {
            $selected = mt_rand(0, $chromosomesSize - 1);
            $chromosome = $chromosomes[$selected];

            $selGene = mt_rand(0, count($chromosome->getGenes()) - 1);
            $chromosome->getGenes()[$selGene] = $this->switchBit($chromosome->getGenes()[$selGene], mt_rand(0, $this->noOfBits - 1));
        }
    }

    /**
     * @param Population $pkp1
     */
    private function improveUsingHillClimbing(Population $pkp1)
    {
        $hillClimbingSolver = new HillClimbingSolver();

        foreach ($pkp1->getChromosomes() as $chromosome) {
            $r = Utils::randomFloat();
            if ($r < Constants::AG_HC_CHANCE) {
                $solver = $hillClimbingSolver->solve($this->equation, $chromosome->getGenes());
                $chromosome->setEval($solver->getFunctionResult());
                $chromosome->setGenes($solver->getValues());
                $chromosome->setFitness(round(1 / $solver->getFunctionResult(), Constants::PRECISION));
            }
        }
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
     * @param Chromosome $best
     */
    private function setEquationSolution(Chromosome $best)
    {
        $values = $this->equation->getX()->getValues();
        for ($i = 0; $i < count($values); $i++) {
            $values[$i] = $this->equation->getConstraint()->wrapValue($best->getGenes()[$i], $this->noOfBits, Constants::PRECISION);
        }
    }
}
