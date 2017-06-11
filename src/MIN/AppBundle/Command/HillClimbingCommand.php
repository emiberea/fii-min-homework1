<?php

namespace MIN\AppBundle\Command;

use MIN\AppBundle\Model\Equation\GriewangksEquation;
use MIN\AppBundle\Model\Equation\RastriginsEquation;
use MIN\AppBundle\Model\Equation\RosenbrocksEquation;
use MIN\AppBundle\Model\Equation\SixHumpEquation;
use MIN\AppBundle\Model\Genetic\Chromosome;
use MIN\AppBundle\Model\Genetic\GeneticSolver;
use MIN\AppBundle\Model\Solver\HillClimbingSolver;
use MIN\AppBundle\Model\Solver\Solution;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HillClimbingCommand extends ContainerAwareCommand
{
    /** @var InputInterface $input */
    protected $input;

    /** @var OutputInterface $output */
    protected $output;

    /** @var HillClimbingSolver $hillClimbingSolver */
    protected static $hillClimbingSolver;

    /** @var GeneticSolver $geneticSolver */
    protected static $geneticSolver;

    protected function configure()
    {
        $this
            ->setName('min:app:hc')
            ->setDescription('Hill Climbing homework 1.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input  = $input;
        $this->output = $output;
        self::$hillClimbingSolver = $this->getContainer()->get('min_app.model.solver.hill_climbing');
        self::$geneticSolver = $this->getContainer()->get('min_app.model.solver.genetic');

        $this->printSolution(self::solveRastriginsWithHC(), 'Rastrigins');
//        $this->printSolution(self::solveGriewangksWithHC(), 'Griewangks');
//        $this->printSolution(self::solveRosenbrocksWithHC(), 'Rosenbrocks');
//        $this->printSolution(self::solveSixHumpWithHC(), 'SixHump');

//        $this->printSolutionGA(self::solveRastriginsWithGA(), 'Rastrigins');
//        $this->printSolutionGA(self::solveGriewangksWithGA(), 'Griewangks');
//        $this->printSolutionGA(self::solveRosenbrocksWithGA(), 'Rosenbrocks');
//        $this->printSolutionGA(self::solveSixHumpWithGA(), 'SixHump');

        return null;
    }

    private static function solveRastriginsWithHC()
    {
        $n = 3;
        return self::$hillClimbingSolver->solve(RastriginsEquation::getInstance()->getEquation($n));
    }

    private static function solveGriewangksWithHC()
    {
        $n = 3;
        return self::$hillClimbingSolver->solve(GriewangksEquation::getInstance()->getEquation($n));
    }

    private static function solveRosenbrocksWithHC()
    {
        $n = 3;
        return self::$hillClimbingSolver->solve(RosenbrocksEquation::getInstance()->getEquation($n));
    }

    private static function solveSixHumpWithHC()
    {
        return self::$hillClimbingSolver->solve(SixHumpEquation::getInstance()->getEquation());
    }

    private static function solveRastriginsWithGA()
    {
        $n = 30;
        return self::$geneticSolver->solve(RastriginsEquation::getInstance()->getEquation($n));
    }

    private static function solveGriewangksWithGA()
    {
        $n = 30;
        return self::$geneticSolver->solve(GriewangksEquation::getInstance()->getEquation($n));
    }

    private static function solveRosenbrocksWithGA()
    {
        $n = 30;
        return self::$geneticSolver->solve(RosenbrocksEquation::getInstance()->getEquation($n));
    }

    private static function solveSixHumpWithGA()
    {
        return self::$geneticSolver->solve(SixHumpEquation::getInstance()->getEquation());
    }

    private function printSolution(Solution $solution, $equationName)
    {
        $this->output->writeln('<info>' . $equationName . '</info>');
        $this->output->writeln(self::$hillClimbingSolver->getEquation()->equationStr);
//        $this->output->writeln(self::$hillClimbingSolver->getEquation());
        $this->output->writeln(implode(' ', self::$hillClimbingSolver->getEquation()->getX()->getValues()->toArray()));
        $this->output->writeln($solution->getFunctionResult());
        $this->output->writeln($solution->__toString() . "\n");
    }

    private function printSolutionGA(Chromosome $solution, $equationName)
    {
        $this->output->writeln('<info>' . $equationName . '</info>');
        $this->output->writeln(self::$geneticSolver->getEquation()->equationStr . "\n");
        $this->output->writeln(implode(' ', self::$geneticSolver->getEquation()->getX()->getValues()->toArray()) . "\n");
        $this->output->writeln($solution->__toString() . "\n");
    }
}
