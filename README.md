PHP BitArray
======================

### A super-fast BitArray for PHP
-----------------------------

This project handles super-fast arrays of bits stored internally as integers.

It is as simple as creating the bit array indicating its length, and you are not limited to one integer:
~~~
$bitarray = new BitArray(2129);
~~~

This package offers methods to get, set and unset bits, and the typical logical bitwise operators _and_, _or_, _xor_ and _not_ methods.

Installation
------------

Using composer: either

~~~
$ composer require oscarpb/bitarray:0.1.0
~~~

or create a `composer.json` file containing

~~~json
{
    "require": {
        "oscarpb/bitarray": "0.1.0"
    }
}
~~~
and run
~~~
$ composer install
~~~

Create a `test.php` file containing
~~~php
<?php
require __DIR__ . '/vendor/autoload.php';

use Oscarpb\Bitarray\BitArray;

$bitarray = new BitArray(8);
$bitarray->setBit(0);
$bitarray->setBit(2);
$bitarray->setBit(4);
$bitarray->setBit(6);
$bitarray->print();
~~~
This should print:
~~~
01010101
~~~

Other options:
~~~php
<?php
require __DIR__ . '/vendor/autoload.php';

use Oscarpb\Bitarray\BitArray;

$bitarray = new BitArray(8);
$bitarray->setBit(2);
$bitarray->setBit(4);
$bitarray->unsetBit(2);

$bitarray->getBit(4);  // true
$bitarray->getBit(2);  // false (it has been unset)

$bitarray2 = new BitArray(8);
$bitarray2->setBit(7);
$bitarray->not();
$bitarray->and($b);
$bitarray->or($b);
$bitarray->xor($b);
~~~

See the [examples](https://github.com/oscarpascualbakker/bit-array/tree/master/examples) folder for more information.

