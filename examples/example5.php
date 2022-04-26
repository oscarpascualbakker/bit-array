<?php

require_once '../vendor/autoload.php';

use Oscarpb\Bitarray\BitArray;


echo "<h2>NOT example:</h2>";

$bitarray = new BitArray(8);
$bitarray->setBit(0);
$bitarray->setBit(2);
$bitarray->setBit(4);
$bitarray->setBit(6);

echo "Bit array print: ";
$bitarray->print();
echo "<br><br>";

$bitarray->not();

echo "Bit array after NOT: ";
$bitarray->print();
echo "<br><br>";
