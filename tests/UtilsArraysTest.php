<?php

namespace LuFiipe\InseeSierene\Tests;

use LuFiipe\InseeSierene\Utils\Arrays;

/**
 * Array utils tester
 */
class UtilsArraysTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testRemoveEmptyElements(): void
    {
        $expected = [
            1 => '01',
            2 => '02',
            999 => 'end',
        ];
        $actual = [
            1 => '01',
            'delete' => null,
            2 => '02',
            3 => '',
            5 => false,
            999 => 'end',
        ];
        $actual = Arrays::removeEmptyElements($actual);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function testSortElementsWithSeparator(): void
    {
        $expected = [
            'value',
            'sub' => '1,2,a,b,c',
        ];
        $actual = [
            'value',
            'sub' => 'c,a,2,b,1',
        ];
        $actual = Arrays::sortElementsWithSeparator($actual, ',');

        $this->assertEquals($expected, $actual);
    }
}
