<?php

require_once '../vendor/autoload.php';

use Oscarpb\Bitarray\BitArray;


echo "<h2>setBit, unsetBit and getBit example:</h2>";

$bitarray = new BitArray(8);
$bitarray->setBit(0);
$bitarray->setBit(2);
$bitarray->setBit(4);
$bitarray->setBit(6);

$bitarray->unsetBit(0);

if ($bitarray->getBit(0)) {
    echo "Bit 0 is set.";
} else {
    echo "Bit 0 is not set.";
}
echo "<br>";

if ($bitarray->getBit(4)) {
    echo "Bit 4 is set.";
} else {
    echo "Bit 4 is not set.";
}
echo "<br>";


echo "Bit array print: ";
echo $bitarray->print();
echo "<br><br>";
