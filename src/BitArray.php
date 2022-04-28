<?php

namespace Oscarpb\Bitarray;


use Oscarpb\Bitarray\DifferentSizeException;
use Oscarpb\Bitarray\LimitExceededException;


class BitArray
{

    /**
     * The total number of bits in the array
     *
     * @var int
     */
    private $totalBits;


    /**
     * The total number of integers we need for this array of bits.
     *
     * @var integer
     */
    private $arrayLength;


    /**
     * The array of integers (you know... the bits)
     *
     * @var array
     */
    private $array;


    /**
     * The number of used bits in the last integer
     *
     * @var int
     */
    private $usedBitsInLastInteger;


    /**
     * A mask to eliminate bits in a NOT operation
     *
     * @var int
     */
    private $rejectMask;


    /**
     * The number of bits in an integer (depends on the processor)
     *
     * @var int
     */
    private $size_of_integer_in_bits  = PHP_INT_SIZE * 8;



    public function __construct(int $totalBits)
    {
        $this->totalBits = $totalBits;

        // Calculate number of integers and set the array to 0.
        $this->arrayLength = ceil($totalBits / $this->size_of_integer_in_bits);
        $this->array       = array_fill(0, $this->arrayLength, 0);

        // Calculate number of used bits in the last integer
        $this->usedBitsInLastInteger = $totalBits % $this->size_of_integer_in_bits;

        // Create a mask like 000011111111 to AND the last integer and eliminate unnecessary bits
        $this->rejectMask = (0b1 << $this->usedBitsInLastInteger) - 1;
    }


    /**
     * Set the correct bit in the correct integer.
     *
     * @param int $bit
     * @return void
     */
    public function setBit($bit)
    {
        if ($bit > ($this->totalBits-1)) {
            throw new LimitExceededException;
        }

        // Calculate the bit we have to activate
        $integer_position = intdiv($bit, $this->size_of_integer_in_bits);
        $bit_position     = $bit % $this->size_of_integer_in_bits;

        // Create mask to set the bit
        $setMask = 0b1 << ($bit_position);

        // And set the bit in the right integer using OR
        $this->array[$integer_position] = $this->array[$integer_position] | $setMask;
    }


    /**
     * Unset the correct bit in the correct integer.
     *
     * @param int $bit
     * @return void
     */
    public function unsetBit($bit)
    {
        if ($bit > ($this->totalBits-1)) {
            throw new LimitExceededException;
        }

        // Calculate the bit we have to activate
        $integer_position = intdiv($bit, $this->size_of_integer_in_bits);
        $bit_position     = $bit % $this->size_of_integer_in_bits;

        // Create mask to unset the bit
        $unsetMask = ~(0b1 << ($bit_position));

        // And set the bit in the right integer using OR
        $this->array[$integer_position] = $this->array[$integer_position] & $unsetMask;
    }


    /**
     * Get the indicated bit from the correct integer.
     *
     * @param int $bit
     * @return void
     */
    public function getBit($bit)
    {
        if ($bit > ($this->totalBits-1)) {
            throw new LimitExceededException;
        }

        // Calculate the bit we have to activate
        $integer_position = intdiv($bit, $this->size_of_integer_in_bits);
        $bit_position     = $bit % $this->size_of_integer_in_bits;

        // Create mask to get the bit
        $getMask = 0b1 << ($bit_position);

        // Perform AND to the right integer to know if the bit is set
        $result = $this->array[$integer_position] & $getMask;

        // It's not possible to return $result > 0, because in PHP the very
        // first bit in an integer indicates the sign (1 => negative)
        return $result != 0;
    }


    /**
     * Return one segment (an integer).
     *
     * This is needed to perform AND, OR and XOR operations with other bitarrays.
     *
     * @param int $segment
     * @return int
     */
    public function getSegment($segment)
    {
        if ($segment > $this->arrayLength) {
            throw new LimitExceededException;
        }

        return $this->array[$segment];
    }


    /**
     * Perform AND operation with another bitarray.
     *
     * @param BitArray $secondBitArray
     * @return void
     */
    public function and(BitArray $secondBitArray)
    {
        // BitArray must have same size
        if ($secondBitArray->size() != $this->totalBits) {
            throw new DifferentSizeException();
        }

        for ($i = 0; $i < $this->arrayLength; $i++) {
            $this->array[$i] = $this->array[$i] & $secondBitArray->getSegment($i);
        }
    }


    /**
     * Perform OR operation with another bitarray.
     *
     * @param BitArray $secondBitArray
     * @return void
     */
    public function or(BitArray $secondBitArray)
    {
        if ($secondBitArray->size() != $this->totalBits) {
            throw new DifferentSizeException();
        }

        for ($i = 0; $i < $this->arrayLength; $i++) {
            $this->array[$i] = $this->array[$i] | $secondBitArray->getSegment($i);
        }
    }


    /**
     * Perform XOR operation with another bitarray.
     *
     * @param BitArray $secondBitArray
     * @return void
     */
    public function xor(BitArray $secondBitArray)
    {
        if ($secondBitArray->size() != $this->totalBits) {
            throw new DifferentSizeException();
        }

        for ($i = 0; $i < $this->arrayLength; $i++) {
            $this->array[$i] = $this->array[$i] ^ $secondBitArray->getSegment($i);
        }
    }


    /**
     * Apply NOT to current bitarray.
     *
     * If bitarray is not as long as an integer, when performing a NOT operation,
     * there will appear a lot of 1's. To avoid this from happening, we perform
     * an AND operation with a reject mask, where all the unused bits are 0's.
     *
     * @return void
     */
    public function not()
    {
        $this->array[0] = ~$this->array[0] & $this->rejectMask;

        if ($this->arrayLength > 1) {
            for ($i = 1; $i < $this->arrayLength; $i++) {
                $this->array[$i] = ~$this->array[$i];
            }
        }
    }


    /**
     * Return the total number of bits in the array
     *
     * @return int
     */
    public function size()
    {
        return $this->totalBits;
    }


    /**
     * This method fills a bitarray with a string representing the array.
     * Only 0's and 1's are accepted values.
     *
     * @param string $string
     * @return void
     */
    public function fillFromString($string)
    {
        // Sanitize input string
        $clean_string = strrev(preg_replace('/\s+/', '', $string));
        $length       = strlen($clean_string);

        // Ensure there are only 0's and 1's
        if (preg_match('/[^0-1]/', $clean_string)) {
            throw new NotAllowedCharactersException;
        }

        // Ensure current bit array is set to 0
        for ($i = 0; $i < $this->arrayLength; $i++) {
            $this->array[$i] = 0;
        }

        // Fill!
        for ($i = 0; $i < $length; $i++) {
            if ($clean_string[$i] == 1) {
                $this->setBit($i);
            }
        }
    }


    /**
     * Return the complete bitarray as a string.
     *
     * @param bool $fancy Wether the output should be fancy or not
     * @return string
     */
    public function print($fancy = false)
    {
        $return    = '';
        $separator = '';
        if ($fancy) {
            $separator = ' ';
        }

        $return  = strrev(implode($separator, str_split(strrev(str_pad(decbin($this->array[$this->arrayLength-1]), $this->usedBitsInLastInteger, "0", STR_PAD_LEFT)), 8)));
        $return .= $separator;
        for($i = $this->arrayLength-2; $i >= 0; $i--) {
            $return .= implode($separator, str_split(str_pad(decbin($this->array[$i]), $this->size_of_integer_in_bits, "0", STR_PAD_LEFT), 8));
            $return .= $separator;
        }

        return $return;
    }


    /**
     * Return the different integers of the bitarray as a string
     *
     * @param bool $fancy Wether the output should be fancy or not
     * @return string
     */
    public function print_segments($fancy = false)
    {
        $return    = '';
        $separator = '';
        if ($fancy) {
            $separator = ' ';
        }

        $return  = "Integer ".($this->arrayLength-1).": " . strrev(implode($separator, str_split(strrev(str_pad(decbin($this->array[$this->arrayLength-1]), $this->usedBitsInLastInteger, "0", STR_PAD_LEFT)), 8)));
        $return .= "<br><br>";

        for($i = $this->arrayLength-2; $i >= 0; $i--) {
            $return .= "Integer $i: " . implode($separator, str_split(str_pad(decbin($this->array[$i]), $this->size_of_integer_in_bits, "0", STR_PAD_LEFT), 8));
            $return .= "<br><br>";
        }

        return $return;
    }


}
