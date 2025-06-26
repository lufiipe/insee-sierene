<?php

namespace LuFiipe\InseeSierene\Tests;

use LuFiipe\InseeSierene\Utils\VarType;

/**
 * Type utils tester
 */
class UtilsVarTypeTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testGetTypeName(): void
    {
        $this->assertEquals('NULL', VarType::getTypeName(null));
        $this->assertEquals('integer', VarType::getTypeName(42));
        $this->assertEquals('stdClass', VarType::getTypeName(new \stdClass()));
    }
}
