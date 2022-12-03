<?php

/**
 * Question 1.
 *
 * Write a method that, given two strings, can decide if one is a permutation of the other.
 *
 * The comparison must be case sensitive.
 */

function permutation(string $foo, string $bar): bool
{
    $foo = str_split($foo);
    sort($foo);

    $bar = str_split($bar);
    sort($bar);

    return $foo === $bar;
}


/**
 * Question 2:
 *
 * You are given two sorted arrays, A and B.
 * Write a method to merge B in to A in sorted order, and return the merged array.
 *
 * Assume the arrays both contain only integers or floats.
 * You must NOT use the built in PHP "array_" or sorting methods.
 * You MAY use "ksort()" to sort your array by keys at the end due to the way PHP handles arrays.
 */

function merge(array $a, array $b): array
{
    $result = [...$a, ...$b];
    bubbleSort($result);

    return $result;
}

function bubbleSort(&$haystack)
{
    $size = count($haystack);

    for($i = 0; $i < $size; $i++) {
        for ($j = 0; $j < $size - $i - 1; $j++) {
            if (!($haystack[$j] > $haystack[$j + 1])) {
                continue;
            }

            $aux = $haystack[$j];
            $haystack[$j] = $haystack[$j+1];
            $haystack[$j+1] = $aux;
        }
    }
}


/**
 * Question 3.
 *
 * Write a unit test for Question 2. Test as much as you see fit to prove the method
 * is both stable and produces the expected results.
 *
 * Assume you have access to PHPUnit, or create pseudo assertions.
 */

function test(): void
{
    // Set
    $a = [4,2,6,3,8,9];
    $b = [7,5,1,0];
    $expected = [0,1,2,3,4,5,6,7,8,9];

    // Action
    $result = merge($a, $b);

    // Assertions
    $this->assertSame($expected, $result);
}


/**
 * Question 4.
 *
 * The following code is designed to modify an initial value using any given integer.
 * The code also has a base modifier that increments by one every call, further offsetting the modification.
 *
 * There are a number of small bugs, fix the bugs so that the following script execution will output the string: "37 4"
 *
 * $b = new B(10);
 * $b->modify(3);
 * $b->modify(7);
 * $b->show();
 *
 */

interface IManipulation
{
    public function modify(int $change): void;

    public function show(): void;
}

abstract class A implements IManipulation
{
    protected $value;

    protected $changeBase = 2;

    public function __construct(int $initial)
    {
        $this->value = $initial;
    }

    public function modify(int $change): void
    {
        $this->value = $change;
    }
}

class B extends A
{
    protected $change = 0;

    public function modify(int $change): void
    {
        parent::modify($this->changeBase * $this->change + $this->value);

        $this->changeBase++;
    }

    public function show(): void
    {
        echo sprintf('%s %s', $this->value, $this->changeBase);
    }
}


/**
 * Question 5.
 *
 * a. Based on the fixed version of the code in Question 4, Create a new concrete implementation of IManipulation
 * that multiplies changeBase by 3 every call to modify(), BEFORE applying the same calculation found in B.
 *
 * b. Your new class should NOT be extendable.
 *
 * c. Ensure the "show()" method returns the outputs of $value and $changeBase, prefixed by the class name.
 *        an example would be: "(Foo): 47 4"
 *
 * d. Write additional getter methods to return $value and $changeBase from the class.
 *
 * The output from running the following code should be: "(Foo): 175 22"
 *
 * $b = new Foo(10);
 * $b->modify(3);
 * $b->modify(7);
 * $b->show();
 */

// write your code here...
class Foo
{

}


/**
 * Bonus Question!
 *
 * Write a method that outputs the fibonacci number of any give position in the fibonacci sequence.
 *
 * Echo the first 10 numbers of the sequence.
 *
 * My reference https://github.com/fnsc/katas/blob/main/src/FibonacciMemoized.php
 */

function fibonacci(int $index, array &$memo = []): int
{
    if (array_key_exists($index, $memo)) {
        return $memo["{$index}"];
    }

    if ($index <= 2) {
        return 1;
    }

    $memo["{$index}"] = fibonacci($index - 1, $memo) + fibonacci($index - 2, $memo);

    return $memo["{$index}"];
}

// ... echo
