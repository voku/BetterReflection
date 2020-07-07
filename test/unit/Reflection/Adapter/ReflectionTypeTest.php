<?php

declare(strict_types=1);

namespace Roave\BetterReflectionTest\Reflection\Adapter;

use PhpParser\Node\Identifier;
use PHPUnit\Framework\TestCase;
use ReflectionClass as CoreReflectionClass;
use ReflectionType as CoreReflectionType;
use ReflectionUnionType;
use Roave\BetterReflection\Reflection\Adapter\ReflectionNamedType as ReflectionNamedTypeAdapter;
use Roave\BetterReflection\Reflection\Adapter\ReflectionType as ReflectionTypeAdapter;
use Roave\BetterReflection\Reflection\Adapter\ReflectionUnionType as ReflectionUnionTypeAdapter;
use Roave\BetterReflection\Reflection\ReflectionNamedType as BetterReflectionNamedType;
use Roave\BetterReflection\Reflection\ReflectionType as BetterReflectionType;
use function array_combine;
use function array_map;
use function class_exists;
use function get_class_methods;

/**
 * @covers \Roave\BetterReflection\Reflection\Adapter\ReflectionType
 */
class ReflectionTypeTest extends TestCase
{
    public function coreReflectionTypeNamesProvider() : array
    {
        $methods = get_class_methods(CoreReflectionType::class);

        return array_combine($methods, array_map(static function (string $i) : array {
            return [$i];
        }, $methods));
    }

    /**
     * @dataProvider coreReflectionTypeNamesProvider
     */
    public function testCoreNamedReflectionTypes(string $methodName) : void
    {
        $reflectionTypeAdapterReflection = new CoreReflectionClass(ReflectionNamedTypeAdapter::class);
        self::assertTrue($reflectionTypeAdapterReflection->hasMethod($methodName));
    }

    /**
     * @dataProvider coreReflectionTypeNamesProvider
     */
    public function testCoreUnionReflectionTypes(string $methodName) : void
    {
        if (! class_exists(ReflectionUnionType::class)) {
            $this->markTestSkipped('ReflectionUnionType does not exist.');
        }

        $reflectionTypeAdapterReflection = new CoreReflectionClass(ReflectionUnionTypeAdapter::class);
        self::assertTrue($reflectionTypeAdapterReflection->hasMethod($methodName));
    }

    public function methodExpectationProvider() : array
    {
        return [
            ['__toString', null, '', []],
            ['allowsNull', null, true, []],
            ['isBuiltin', null, true, []],
        ];
    }

    /**
     * @param mixed   $returnValue
     * @param mixed[] $args
     *
     * @dataProvider methodExpectationProvider
     */
    public function testAdapterMethods(string $methodName, ?string $expectedException, $returnValue, array $args) : void
    {
        $reflectionStub = $this->createMock(BetterReflectionNamedType::class);

        if ($expectedException === null) {
            $reflectionStub->expects($this->once())
                ->method($methodName)
                ->with(...$args)
                ->will($this->returnValue($returnValue));
        }

        if ($expectedException !== null) {
            $this->expectException($expectedException);
        }

        $adapter = ReflectionTypeAdapter::fromReturnTypeOrNull($reflectionStub);
        $adapter->{$methodName}(...$args);
    }

    public function testFromReturnTypeOrNullWithNull() : void
    {
        self::assertNull(ReflectionTypeAdapter::fromReturnTypeOrNull(null));
    }

    public function testFromReturnTypeOrNullWithBetterReflectionType() : void
    {
        self::assertInstanceOf(ReflectionNamedTypeAdapter::class, ReflectionTypeAdapter::fromReturnTypeOrNull($this->createMock(BetterReflectionNamedType::class)));
    }

    public function testSelfIsNotBuiltin() : void
    {
        $betterReflectionType  = BetterReflectionType::createFromTypeAndReflector(new Identifier('self'));
        $reflectionTypeAdapter = ReflectionTypeAdapter::fromReturnTypeOrNull($betterReflectionType);

        self::assertFalse($reflectionTypeAdapter->isBuiltin());
    }

    public function testParentIsNotBuiltin() : void
    {
        $betterReflectionType  = BetterReflectionType::createFromTypeAndReflector(new Identifier('parent'));
        $reflectionTypeAdapter = ReflectionTypeAdapter::fromReturnTypeOrNull($betterReflectionType);

        self::assertFalse($reflectionTypeAdapter->isBuiltin());
    }
}
