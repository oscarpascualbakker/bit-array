<?php

use PHPUnit\Framework\TestCase;
use Oscarpb\Bitarray\BitArray;
use Oscarpb\Bitarray\LimitExceededException;
use Oscarpb\Bitarray\DifferentSizeException;
use Oscarpb\Bitarray\NotAllowedCharactersException;

class BitArrayTest extends TestCase
{

    /**
     * @covers \Oscarpb\Bitarray\LimitExceededException
     * @covers \Oscarpb\Bitarray\Bitarray::setBit
     * @covers \Oscarpb\Bitarray\Bitarray::unsetBit
     * @covers \Oscarpb\Bitarray\Bitarray::getBit
     */
    public function test_one_bit_bitarray()
    {
        $bitarray = new BitArray(1);
        $this->assertInstanceOf(BitArray::class, $bitarray);

        $bitarray->setBit(0);
        $this->assertTrue($bitarray->getBit(0));

        $bitarray->unsetBit(0);
        $this->assertFalse($bitarray->getBit(0));

        $this->expectException(LimitExceededException::class);
        $bitarray->setBit(1);
    }


    /**
     * @covers \Oscarpb\Bitarray\LimitExceededException
     * @covers \Oscarpb\Bitarray\Bitarray::setBit
     * @covers \Oscarpb\Bitarray\Bitarray::unsetBit
     * @covers \Oscarpb\Bitarray\Bitarray::getBit
     */
    public function test_one_integer_bitarray()
    {
        $bitarray = new BitArray(PHP_INT_SIZE * 8);
        $this->assertInstanceOf(BitArray::class, $bitarray);

        $bitarray->setBit(0);
        $this->assertTrue($bitarray->getBit(0));

        $bitarray->setBit(63);
        $this->assertTrue($bitarray->getBit(63));

        $bitarray->unsetBit(63);
        $this->assertFalse($bitarray->getBit(63));

        $this->expectException(LimitExceededException::class);
        $bitarray->setBit(64);
    }


    /**
     * @covers \Oscarpb\Bitarray\LimitExceededException
     * @covers \Oscarpb\Bitarray\Bitarray::setBit
     * @covers \Oscarpb\Bitarray\Bitarray::unsetBit
     * @covers \Oscarpb\Bitarray\Bitarray::getBit
     */
    public function test_two_integers_bitarray()
    {
        // Let's do it with a two integers bit array, but using only one extra bit in the second integer
        $bitarray = new BitArray((PHP_INT_SIZE * 8) + 1);
        $this->assertInstanceOf(BitArray::class, $bitarray);

        $bitarray->setBit(0);
        $this->assertTrue($bitarray->getBit(0));

        $bitarray->setBit(63);
        $this->assertTrue($bitarray->getBit(63));

        $bitarray->unsetBit(63);
        $this->assertFalse($bitarray->getBit(63));

        $bitarray->setBit(64);
        $this->assertTrue($bitarray->getBit(64));

        $this->expectException(LimitExceededException::class);
        $bitarray->setBit(65);


        // The same but with a complete two integers bit array
        $bitarray = new BitArray(PHP_INT_SIZE * 8 * 2);
        $this->assertInstanceOf(BitArray::class, $bitarray);

        $bitarray->setBit(0);
        $this->assertTrue($bitarray->getBit(0));

        $bitarray->setBit(63);
        $this->assertTrue($bitarray->getBit(63));

        $bitarray->unsetBit(63);
        $this->assertFalse($bitarray->getBit(63));

        $bitarray->setBit(64);
        $this->assertTrue($bitarray->getBit(64));

        $bitarray->setBit(127);
        $this->assertTrue($bitarray->getBit(127));

        $this->expectException(LimitExceededException::class);
        $bitarray->setBit(65);
    }


    /**
     * @covers \Oscarpb\Bitarray\LimitExceededException
     * @covers \Oscarpb\Bitarray\Bitarray::setBit
     * @covers \Oscarpb\Bitarray\Bitarray::getBit
     */
    public function test_big_bitarray()
    {
        $bitarray = new BitArray(2651);
        $this->assertInstanceOf(BitArray::class, $bitarray);

        $bitarray->setBit(0);
        $this->assertTrue($bitarray->getBit(0));

        $bitarray->setBit(2650);
        $this->assertTrue($bitarray->getBit(2650));

        $this->expectException(LimitExceededException::class);
        $bitarray->setBit(2651);
    }


    /**
     * @covers \Oscarpb\Bitarray\DifferentSizeException
     * @covers \Oscarpb\Bitarray\Bitarray::setBit
     * @covers \Oscarpb\Bitarray\Bitarray::or
     */
    public function test_operations_on_different_sized_bitarrays()
    {
        $bitarray1 = new BitArray(8);
        $this->assertInstanceOf(BitArray::class, $bitarray1);
        $bitarray1->setBit(0);
        $bitarray1->setBit(2);
        $bitarray1->setBit(4);
        $bitarray1->setBit(6);

        $bitarray2 = new BitArray(9);
        $this->assertInstanceOf(BitArray::class, $bitarray2);
        $bitarray2->setBit(1);
        $bitarray2->setBit(3);
        $bitarray2->setBit(5);
        $bitarray2->setBit(7);

        $this->expectException(DifferentSizeException::class);
        $bitarray1->or($bitarray2);
    }


    /**
     * @covers \Oscarpb\Bitarray\Bitarray::setBit
     * @covers \Oscarpb\Bitarray\Bitarray::getBit
     * @covers \Oscarpb\Bitarray\Bitarray::or
     */
    public function test_or_on_two_bitarrays()
    {
        $bitarray1 = new BitArray(8);
        $this->assertInstanceOf(BitArray::class, $bitarray1);
        $bitarray1->setBit(0);
        $bitarray1->setBit(2);
        $bitarray1->setBit(4);
        $bitarray1->setBit(6);

        $bitarray2 = new BitArray(8);
        $this->assertInstanceOf(BitArray::class, $bitarray2);
        $bitarray2->setBit(1);
        $bitarray2->setBit(3);
        $bitarray2->setBit(5);
        $bitarray2->setBit(7);

        $bitarray1->or($bitarray2);

        $this->assertTrue($bitarray1->getBit(0));
        $this->assertTrue($bitarray1->getBit(1));
        $this->assertTrue($bitarray1->getBit(2));
        $this->assertTrue($bitarray1->getBit(3));
        $this->assertTrue($bitarray1->getBit(4));
        $this->assertTrue($bitarray1->getBit(5));
        $this->assertTrue($bitarray1->getBit(6));
        $this->assertTrue($bitarray1->getBit(7));
    }


    /**
     * @covers \Oscarpb\Bitarray\Bitarray::setBit
     * @covers \Oscarpb\Bitarray\Bitarray::getBit
     * @covers \Oscarpb\Bitarray\Bitarray::and
     */
    public function test_and_on_two_bitarrays()
    {
        $bitarray1 = new BitArray(8);
        $bitarray1->setBit(0);
        $bitarray1->setBit(2);
        $bitarray1->setBit(4);
        $bitarray1->setBit(6);

        $bitarray2 = new BitArray(8);
        $bitarray2->setBit(0);
        $bitarray2->setBit(3);
        $bitarray2->setBit(4);
        $bitarray2->setBit(7);

        $bitarray1->and($bitarray2);

        $this->assertTrue($bitarray1->getBit(0));
        $this->assertTrue($bitarray1->getBit(4));

        $this->assertFalse($bitarray1->getBit(1));
        $this->assertFalse($bitarray1->getBit(2));
        $this->assertFalse($bitarray1->getBit(3));
        $this->assertFalse($bitarray1->getBit(5));
        $this->assertFalse($bitarray1->getBit(6));
        $this->assertFalse($bitarray1->getBit(7));
    }


    /**
     * @covers \Oscarpb\Bitarray\Bitarray::setBit
     * @covers \Oscarpb\Bitarray\Bitarray::getBit
     * @covers \Oscarpb\Bitarray\Bitarray::xor
     */
    public function test_xor_on_two_bitarrays()
    {
        $bitarray1 = new BitArray(8);
        $bitarray1->setBit(0);
        $bitarray1->setBit(2);
        $bitarray1->setBit(4);
        $bitarray1->setBit(6);

        $bitarray2 = new BitArray(8);
        $bitarray2->setBit(0);
        $bitarray2->setBit(3);
        $bitarray2->setBit(4);
        $bitarray2->setBit(7);

        $bitarray1->xor($bitarray2);

        $this->assertFalse($bitarray1->getBit(0));
        $this->assertFalse($bitarray1->getBit(1));
        $this->assertFalse($bitarray1->getBit(4));
        $this->assertFalse($bitarray1->getBit(5));

        $this->assertTrue($bitarray1->getBit(2));
        $this->assertTrue($bitarray1->getBit(3));
        $this->assertTrue($bitarray1->getBit(6));
        $this->assertTrue($bitarray1->getBit(7));
    }


    /**
     * @covers \Oscarpb\Bitarray\Bitarray::setBit
     * @covers \Oscarpb\Bitarray\Bitarray::getBit
     * @covers \Oscarpb\Bitarray\Bitarray::not
     */
    public function test_not_on_bitarray()
    {
        $bitarray = new BitArray(8);
        $bitarray->setBit(0);
        $bitarray->setBit(2);
        $bitarray->setBit(4);
        $bitarray->setBit(6);

        $bitarray->not();

        $this->assertFalse($bitarray->getBit(0));
        $this->assertFalse($bitarray->getBit(2));
        $this->assertFalse($bitarray->getBit(4));
        $this->assertFalse($bitarray->getBit(6));

        $this->assertTrue($bitarray->getBit(1));
        $this->assertTrue($bitarray->getBit(3));
        $this->assertTrue($bitarray->getBit(5));
        $this->assertTrue($bitarray->getBit(7));
    }


    /**
     * @covers \Oscarpb\Bitarray\Bitarray::getBit
     * @covers \Oscarpb\Bitarray\Bitarray::createFromString
     */
    public function test_create_bitarray_from_string()
    {
        $string = '00110101110001100010';

        $bitarray = new BitArray(strlen($string));
        $bitarray->fillFromString($string);

        $this->assertFalse($bitarray->getBit(0));
        $this->assertFalse($bitarray->getBit(2));
        $this->assertFalse($bitarray->getBit(3));
        $this->assertFalse($bitarray->getBit(4));
        $this->assertFalse($bitarray->getBit(7));
        $this->assertFalse($bitarray->getBit(8));
        $this->assertFalse($bitarray->getBit(9));
        $this->assertFalse($bitarray->getBit(13));
        $this->assertFalse($bitarray->getBit(15));
        $this->assertFalse($bitarray->getBit(18));
        $this->assertFalse($bitarray->getBit(19));

        $this->assertTrue($bitarray->getBit(1));
        $this->assertTrue($bitarray->getBit(5));
        $this->assertTrue($bitarray->getBit(6));
        $this->assertTrue($bitarray->getBit(10));
        $this->assertTrue($bitarray->getBit(11));
        $this->assertTrue($bitarray->getBit(12));
        $this->assertTrue($bitarray->getBit(14));
        $this->assertTrue($bitarray->getBit(16));
        $this->assertTrue($bitarray->getBit(17));
    }


    /**
     * @covers \Oscarpb\Bitarray\Bitarray::createFromString
     * @covers \Oscarpb\Bitarray\NotAllowedCharactersException
     */
    public function test_create_bitarray_with_strange_characters()
    {
        $string = '0010.110';
        $this->expectException(NotAllowedCharactersException::class);
        $bitarray = new BitArray(strlen($string));
        $bitarray->fillFromString($string);

        $string = '00101102';
        $this->expectException(NotAllowedCharactersException::class);
        $bitarray = new BitArray(strlen($string));
        $bitarray->fillFromString($string);

        $string = 'a00101101';
        $this->expectException(NotAllowedCharactersException::class);
        $bitarray = new BitArray(strlen($string));
        $bitarray->fillFromString($string);
    }


}