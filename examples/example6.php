<?php

require_once '../vendor/autoload.php';

use Oscarpb\Bitarray\BitArray;


echo "<h2>Byte printing example:</h2>";

$bitarray1 = new BitArray(148);
$bitarray1->setBit(24);
$bitarray1->setBit(59);
$bitarray1->setBit(77);
$bitarray1->setBit(91);
$bitarray1->setBit(97);
$bitarray1->setBit(101);
$bitarray1->setBit(121);
$bitarray1->setBit(133);
$bitarray1->setBit(147);

echo "Bit array 1: ";
$bitarray1->print();
echo "<br><br>";

$bitarray2 = new BitArray(148);
$bitarray2->setBit(12);
$bitarray2->setBit(55);
$bitarray2->setBit(67);
$bitarray2->setBit(88);
$bitarray2->setBit(100);
$bitarray2->setBit(102);
$bitarray2->setBit(116);
$bitarray2->setBit(121);
$bitarray2->setBit(132);
$bitarray2->setBit(139);
$bitarray2->setBit(141);
$bitarray2->setBit(143);

echo "Bit array 2: ";
$bitarray2->print();
echo "<br><br>";

$bitarray1->or($bitarray2);

echo "Bit array 1 after OR with bit array 2: ";
$bitarray1->print();
echo "<br><br>";
