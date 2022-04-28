<?php

require_once '../vendor/autoload.php';

use Oscarpb\Bitarray\BitArray;


echo "<h2>Create from string example:</h2>";

// You can use a normal string, or the 'fancy' output from BitArray,
// BUT the lenght must be set in advance (attention: mind the spaces)
$string = '001010111001110101010100101011100111010101010010101110011101010101001010111001110101010100101011100111010101010010101110011101010101';

$bitarray = new BitArray(strlen($string));
$bitarray->fillFromString($string);

echo "<p>Original bit array: </p>";
echo "<p>$string</p>";

echo "<p>New bit array (fancy mode): </p>";
echo $bitarray->print(true);

echo "<p>New bit array per segments (fancy mode):</p> ";
echo $bitarray->print_segments(true);