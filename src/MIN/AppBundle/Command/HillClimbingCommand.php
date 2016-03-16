<?php

namespace MIN\AppBundle\Command;

use MIN\AppBundle\Model\Equation\GriewangksEquation;
use MIN\AppBundle\Model\Equation\RastriginsEquation;
use MIN\AppBundle\Model\Equation\RosenbrocksEquation;
use MIN\AppBundle\Model\Equation\SixHumpEquation;
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

        $this->printSolution(self::solveRastriginsWithHC(), 'Rastrigins');
        $this->printSolution(self::solveGriewangksWithHC(), 'Griewangks');
        $this->printSolution(self::solveRosenbrocksWithHC(), 'Rosenbrocks');
        $this->printSolution(self::solveSixHumpWithHC(), 'SixHump');

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

    private function printSolution(Solution $solution, $equationName)
    {
        $this->output->writeln('<info>' . $equationName . '</info>');
        $this->output->writeln(self::$hillClimbingSolver->getEquation()->equationStr);
//        $this->output->writeln(self::$hillClimbingSolver->getEquation());
        $this->output->writeln(implode(' ', self::$hillClimbingSolver->getEquation()->getX()->getValues()->toArray()));
        $this->output->writeln($solution->getFunctionResult());
        $this->output->writeln($solution->__toString() . "\n");
    }
}
