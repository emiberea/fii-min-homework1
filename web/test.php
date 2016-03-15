<?php

$rand1 = gmp_random_bits(3); // random number from 0 to 7
$rand2 = gmp_random_bits(5); // random number from 0 to 31
//var_dump($rand1->serialize());
echo $rand1 . "\n";
echo $rand2 . "\n";
echo gmp_strval($rand1) . "\n";
echo gmp_strval($rand2) . "\n";

echo decbin(gmp_intval($rand1)) . "\n";
echo decbin(gmp_intval($rand2)) . "\n";