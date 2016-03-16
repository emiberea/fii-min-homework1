<?php

namespace MIN\AppBundle\Model\Helper;

class Utils
{
    /**
     * @param int $noOfBits
     * @return int
     */
    public static function generate($noOfBits)
    {
        $result = 0;
        for ($i = 0; $i < $noOfBits; $i++) {
            $b = mt_rand(0, 1) == 0;
            $result |= $b ? 1 << $i : 0;
        }

        return $result;
    }
}
