<?php

namespace MIN\AppBundle\Service;

class ComputeService
{
    public function doStuff()
    {

        $binarydata = pack("nvc*", 0x1234, 0x5678, 65, 66);
        $x = random_bytes(4);
        $x = decbin(4);
        return $x;
    }

    public function generateN($startNumber, $endNumber, $precision)
    {
        $N = log($endNumber - $startNumber, 2) * pow(10, $precision);
//var_dump($endNumber - $startNumber);
//var_dump(log($endNumber - $startNumber, 2));
//var_dump(pow(10, $precision));
        return $N;
    }

    public function generateBitString($N)
    {
        $bitString = '';
        for ($i = 0; $i < $N; $i++) {
            $bitString .= mt_rand(0, 1);
        }

        return $bitString;
    }

    public function binaryToFloat($binary, $start, $end, $N)
    {
        $float = bindec($binary); // real number between 0 and 1
        $result = ($float / (pow(2, $N) - 1)) * ($end - $start) + $start; // [0, end - start]

        return $result;
    }
}
