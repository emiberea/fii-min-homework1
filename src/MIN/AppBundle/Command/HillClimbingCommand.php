<?php

namespace MIN\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HillClimbingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('min:app:hc')
            ->setDescription('Hill Climbing homework 1.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startNumber = -5.12;
        $endNumber = 5.12;
        $precision = 1;
        $computeService = $this->getContainer()->get('min_app.service.compute');

        $N = $computeService->generateN($startNumber, $endNumber, $precision);
        var_dump($N);


        return 1;
    }
}
