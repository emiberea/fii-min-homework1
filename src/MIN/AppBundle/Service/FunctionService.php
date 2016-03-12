<?php

namespace MIN\AppBundle\Service;

class FunctionService
{
    /** @var ComputeService $computeService */
    private $computeService;

    /**
     * @param ComputeService $computeService
     */
    public function __construct(ComputeService $computeService)
    {
        $this->computeService = $computeService;
    }

    public function rastriginHillClimbing($n = 10, $precision = 1)
    {
        $startNumber = -5.12;
        $endNumber = 5.12;
        $N = $this->computeService->generateN($startNumber, $endNumber, $precision);

        $tMax = $n * $N;
        $t = 0;

        $best = null;
        $vector = $this->initVector($n, $N);
        while ($t < $tMax) {
            $local = false;
            $vc = [];
            for ($i = 0; $i < $n; $i++) {
                $vc[] = $this->computeService->binaryToFloat($vector[$i], $startNumber, $endNumber, $precision);
            }
            $evalVc = $this->rastriginEval($vc);

            while (!$local) {
                $vectorNew = $this->generateNeighbor($vector);
                $vn = [];
                for ($i = 0; $i < $n; $i++) {
                    $vn[] = $this->computeService->binaryToFloat($vectorNew[$i], $startNumber, $endNumber, $precision);
                }
                $evalVn = $this->rastriginEval($vn);
//                var_dump($evalVc);
//                var_dump($evalVn);
//                var_dump($evalVn < $evalVc);
//                var_dump($evalVc < $evalVn);
//                die;
                if ($evalVn < $evalVc) {
                    $evalVc = $evalVn;
                    $vector = $vectorNew;
                    $local = true;
                }

            }

            if ($evalVc < $best) {
                $best = $evalVc;
            }
//var_dump($evalVc);die;
            $t++;
        }
//var_dump($best);die;
        return true;
    }

    public function griewangkHillClimbing($n = 10, $precision = 1)
    {
        $startNumber = -600;
        $endNumber = 600;
        $N = $this->computeService->generateN($startNumber, $endNumber, $precision);

        $tMax = $n * $N;
        $t = 0;

        $best = null;
        $vector = $this->initVector($n, $N);
        while ($t < $tMax) {
            $local = false;
            $vc = [];
            for ($i = 0; $i < $n; $i++) {
                $vc[] = $this->computeService->binaryToFloat($vector[$i], $startNumber, $endNumber, $precision);
            }
            $evalVc = $this->griewangkEval($vc);

            while (!$local) {
                $vectorNew = $this->generateNeighbor($vector);
                $vn = [];
                for ($i = 0; $i < $n; $i++) {
                    $vn[] = $this->computeService->binaryToFloat($vectorNew[$i], $startNumber, $endNumber, $precision);
                }
                $evalVn = $this->griewangkEval($vn);
//                var_dump($evalVc);
//                var_dump($evalVn);
//                var_dump($evalVn < $evalVc);
//                var_dump($evalVc < $evalVn);
//                die;
                if ($evalVn < $evalVc) {
                    $evalVc = $evalVn;
                    $vector = $vectorNew;
                    $local = true;
                }
            }

            if ($evalVc < $best) {
                $best = $evalVc;
            }
var_dump($evalVc);die;
            $t++;
        }
//var_dump($best);die;
        return true;
    }

    public function rosenbrockHillClimbing($n = 10, $precision = 1)
    {
        $startNumber = -2.048;
        $endNumber = 2.048;
        $N = $this->computeService->generateN($startNumber, $endNumber, $precision);

        $tMax = $n * $N;
        $t = 0;

        $best = null;
        $vector = $this->initVector($n, $N);
        while ($t < $tMax) {
            $local = false;
            $vc = [];
            for ($i = 0; $i < $n; $i++) {
                $vc[] = $this->computeService->binaryToFloat($vector[$i], $startNumber, $endNumber, $precision);
            }
            $evalVc = $this->rosenbrockEval($vc);

            while (!$local) {
                $vectorNew = $this->generateNeighbor($vector);
                $vn = [];
                for ($i = 0; $i < $n; $i++) {
                    $vn[] = $this->computeService->binaryToFloat($vectorNew[$i], $startNumber, $endNumber, $precision);
                }
                $evalVn = $this->rosenbrockEval($vn);
//                var_dump($evalVc);
//                var_dump($evalVn);
//                var_dump($evalVn < $evalVc);
//                var_dump($evalVc < $evalVn);
//                die;
                if ($evalVn < $evalVc) {
                    $evalVc = $evalVn;
                    $vector = $vectorNew;
                    $local = true;
                }

            }

            if ($evalVc < $best) {
                $best = $evalVc;
            }
            var_dump($evalVc);die;
            $t++;
        }
//var_dump($best);die;
        return true;
    }

    public function initVector($n, $N)
    {
        $byteArr = [];
        for ($i = 0; $i < $n; $i++) {
            $byteArr[] = $this->computeService->generateBitString($N);
        }

        return $byteArr;
    }

    public function rastriginEval(array $decimalVector = array())
    {
        $sum = 0;
        foreach ($decimalVector as $decimal) {
            $sum += pow($decimal, 2) - 10 * cos(2 * M_PI * $decimal);
        }

        $result = 10 * count($decimalVector) + $sum;

        return $result;
    }

    public function griewangkEval(array $decimalVector = array())
    {
        $sum = 0;
        foreach ($decimalVector as $decimal) {
            $sum += pow($decimal, 2) / 4000;
        }

        $prod = 1;
        for ($i = 1; $i <= count($decimalVector); $i++) {
            $prod *= cos($decimalVector[$i - 1] / sqrt($i));
        }

        $result = $sum - $prod + 1;

        return $result;
    }

    public function rosenbrockEval(array $decimalVector = array())
    {
        $sum = 0;
        for ($i = 1; $i <= count($decimalVector) - 1; $i++) {
            $var1 = $decimalVector[$i] - pow($decimalVector[$i - 1], 2);
            $var2 = 1 - $decimalVector[$i - 1];
            $sum += 100 * pow($var1, 2) + pow($var2, 2);
        }

        return $sum;
    }

    public function sixHumpCamelBackEval(array $decimalVector1 = array(), array $decimalVector2 = array())
    {
        $sum = 0;
        for ($i = 1; $i <= count($decimalVector1); $i++) {
            $var1 = $decimalVector1[$i - 1];
            $var2 = $decimalVector2[$i - 1];
            $sum += (4 - 2.1 * pow($var1, 2) + pow($var1, 4/3)) * pow($var1, 2) + ($var1 * $var2) + (-4 + 4 * pow($var2, 2)) * pow($var2, 2);
        }

        return $sum;
    }

    public function generateNeighbor(array $decimalVector = array())
    {
//        var_dump($decimalVector);
        $count = count($decimalVector);
        $elementPos = mt_rand(0, $count - 1);
        $element = $decimalVector[$elementPos];

        $elementLength = strlen($element);
        $randomBitPosition = mt_rand(0, $elementLength - 1);
        $char = substr($element, $randomBitPosition, 1);

        if ($char == '1') {
            $newChar = '0';
        } else {
            $newChar = '1';
        }

        $charFirstPart = substr($element, 0, $randomBitPosition);
//        $charLastPartTest = substr($element, $randomBitPosition, $elementLength - 1);
        $charLastPart = $newChar . substr($element, $randomBitPosition + 1, $elementLength - 1);
        $newElement = $charFirstPart . $charLastPart;

        $decimalVector[$elementPos] = $newElement;
//        var_dump($decimalVector);
//        var_dump($elementPos);
//        die;
//        var_dump($charFirstPart);
//        var_dump($charFirstPart);
//        var_dump($charLastPart);
//        var_dump($charLastPart2);
//        var_dump($char);
//        var_dump($newChar);
//        var_dump($randomBitPosition);
//        var_dump($element);
//        var_dump($newElement);
//        var_dump($elementLength);
//
//        die;
        return $decimalVector;
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
