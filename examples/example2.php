<?php

require_once '../vendor/autoload.php';

use Oscarpb\Bitarray\BitArray;


echo "<h2>OR example:</h2>";

$bitarray1 = new BitArray(8);
$bitarray1->setBit(0);
$bitarray1->setBit(2);
$bitarray1->setBit(4);
$bitarray1->setBit(6);

echo "Bit array 1 print: ";
echo $bitarray1->print();
echo "<br><br>";

$bitarray2 = new BitArray(8);
$bitarray2->setBit(0);
$bitarray2->setBit(3);
$bitarray2->setBit(4);
$bitarray2->setBit(7);

echo "Bit array 2 print: ";
echo $bitarray2->print();
echo "<br><br>";

$bitarray1->or($bitarray2);

echo "Bit array 1 after OR with bit array 2: ";
echo $bitarray1->print();
echo "<br><br>";
