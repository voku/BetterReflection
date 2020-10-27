<?php

declare(strict_types=1);

namespace Roave\BetterReflectionTest\Reflection;

use PHPUnit\Framework\TestCase;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflectionTest\Fixture\ClassWithToString;
use Roave\BetterReflectionTest\Fixture\ClassWithToStringAndStringable;
use Roave\BetterReflectionTest\Fixture\InterfaceWithToString;
use Roave\BetterReflectionTest\Fixture\InterfaceWithToStringAndStringable;
use Stringable;
use const PHP_VERSION_ID;

class ReflectionClassStringableTest extends TestCase
{
    public function tearDown() : void
    {
        BetterReflection::$phpVersion = PHP_VERSION_ID;
    }

    public function dataToString() : array
    {
        return [
            [ClassWithToString::class],
            [InterfaceWithToString::class],
        ];
    }

    /**
     * @dataProvider dataToString
     */
    public function testNoStringableOnPhp7(string $className) : void
    {
        BetterReflection::$phpVersion = 70400;
        $reflection                   = ReflectionClass::createFromName($className);
        self::assertFalse($reflection->implementsInterface(Stringable::class));
        self::assertNotContains(Stringable::class, $reflection->getInterfaceNames());
    }

    /**
     * @dataProvider dataToString
     */
    public function testStringableOnPhp8(string $className) : void
    {
        BetterReflection::$phpVersion = 80000;
        $reflection                   = ReflectionClass::createFromName($className);
        self::assertTrue($reflection->implementsInterface(Stringable::class));
        self::assertContains(Stringable::class, $reflection->getInterfaceNames());
    }

    public function dataToStringAndStringable() : array
    {
        return [
            [ClassWithToStringAndStringable::class],
            [InterfaceWithToStringAndStringable::class],
        ];
    }

    /**
     * @dataProvider dataToStringAndStringable
     */
    public function testStringableOnlyOnce(string $className) : void
    {
        BetterReflection::$phpVersion = 80000;
        $reflection                   = ReflectionClass::createFromName($className);
        self::assertTrue($reflection->implementsInterface(Stringable::class));
        self::assertSame([Stringable::class], $reflection->getInterfaceNames());
    }
}
