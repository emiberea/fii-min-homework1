<?php

namespace MIN\AppBundle\Command;

use MIN\AppBundle\Model\Homework2\Element;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Homework2Command extends ContainerAwareCommand
{
    /** @var InputInterface $input */
    protected $input;

    /** @var OutputInterface $output */
    protected $output;

    protected function configure()
    {
        $this
            ->setName('min:app:h2')
            ->setDescription('Hill Climbing homework 2.');
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

        Element::init();
        foreach (Element::$items as $item) {
            echo sprintf("x = %s (%s)\n fitness = %s\n neighbors = [%s] or [%s]\n 1st Better Neighbor = %s\n Best Neighbor = %s\n Landscape First Improvment: %s\n Landscape Best Improvment: %s\n",
                $item->getValue(),
                $item->getBits(),
                $item->getFitness(),
                $item->getNeighborsAsIntString(),
                $item->getNeighborsAsBinaryString(),
                $item->getFirstBetterNeighbor() != null ? $item->getFirstBetterNeighbor()->getValue() : ("--> " . $item->getValue()),
                $item->getBestNeighbor() != null ? $item->getBestNeighbor()->getValue() : ("--> " . $item->getValue()),
                $item->getLandscape_F_AsIntsString(),
                $item->getLandscape_B_AsIntsString()
            );
        }

        return null;
    }
}
