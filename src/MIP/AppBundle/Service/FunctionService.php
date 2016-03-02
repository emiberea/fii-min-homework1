<?php

namespace MIP\AppBundle\Service;

class FunctionService
{
    /** @var ComputeService $computeService */
    private $computeService;

    /**
     * FunctionService constructor.
     * @param ComputeService $computeService
     */
    public function __construct(ComputeService $computeService)
    {
        $this->computeService = $computeService;
    }

    public function rastrigin($precision = 1)
    {
        $startNumber = -5.12;
        $endNumber = 5.12;
        $N = $this->computeService->generateN($startNumber, $endNumber, $precision);
        $n = 10;

        $byteArr = [];
        $decimalArr = [];
        for ($i = 0; $i < $n; $i++) {
            $byteArr[] = $this->computeService->generateBitString($N);
            $decimalArr[] = $this->computeService->binaryToFloat($byteArr[$i], $startNumber, $endNumber, $precision);
        }

        $sum = 0;
        foreach ($decimalArr as $decimal) {
            $sum += pow($decimal, 2) - 10 * cos(2 * M_PI * $decimal);
        }

        $result = 10 * $n + $sum;
        var_dump($byteArr);
        var_dump($decimalArr);
        var_dump($result);

        return $result;
    }
}
