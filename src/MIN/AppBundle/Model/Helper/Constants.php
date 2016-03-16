<?php

namespace MIN\AppBundle\Model\Helper;

final class Constants
{
    const SOLUTION_PRECISION = 3;
    const PRECISION = 5;
    const HC_MAX_STEPS = 20;
    const USE_FIRST_IMPROVEMENT = true;
    const AG_POPULATION_SIZE = 100;
    const AG_MAX_ITERATIONS = 200;
    const AG_CROSSOVER_REPLACEMENT = 0.7;
    const AG_MUTATION_RATE = 0.1;
    const AG_HC_CHANCE = 0.25;
}
