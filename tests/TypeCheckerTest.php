<?php declare(strict_types=1);

namespace Benrowe\Properties;

use PHPUnit\Framework\TestCase;

class TypeCheckerTest extends TestCase
{
    /**
     * @dataProvider providerTypeCheck
     */
    public function testCheckType($value, $type, $isTrue)
    {
        $checker = new TypeChecker($value);
        $is = $checker->check($type);
        $isTrue ? $this->assertTrue($is) : $this->assertFalse($is);
    }

    public function testCheckTypeAsClosure()
    {
        $checker = new TypeChecker('val');
        $is = $checker->checkAsClosure(function ($val) {
            return gettype($val) !== 'integer';
        });
        $this->assertTrue($is);

        $checker = new TypeChecker(1);
        $is = $checker->checkAsClosure(function ($val) {
            return gettype($val) !== 'integer';
        });
        $this->assertFalse($is);
    }

    public function testCheckTypeAsClosureInvalid()
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage("Return value for closure is not boolean");
        $checker = new TypeChecker('val');
        $is = $checker->checkAsClosure(function ($val) {
            return 'ha!';
        });
    }

    /**
     * @dataProvider providerInvalidTypes
     */
    public function testUnsupportedType(string $type)
    {
        $this->expectException(TypeException::class);
        $this->expectExceptionMessage("Unknown type check for \"$type\"");
        $checker = new TypeChecker('val');
        $checker->check($type);
    }

    public function providerTypeCheck(): array
    {
        return [
            ['im a string', 'string', true],
            [1, 'string', false],
            ['string', 'string|int', true],
            [1, 'string|int', true],
            [1.0, 'string|int', false],
            [['foo', 'bar'], 'string[]', true],
            [['foo', 'bar'], 'string[]|int', true],
            [1, 'string[]|int', true],
            [1.0, 'string[]|int', false],
        ];
    }

    public function providerInvalidTypes(): array
    {
        return [
            ['!'],
            ['|'],
            ['||'],
            ['0']
        ];
    }
}
