<?php

declare(strict_types=1);

namespace Roave\BetterReflectionTest\Reflection;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Roave\BetterReflection\Reflection\ReflectionType;
use Roave\BetterReflection\Reflector\Reflector;

/**
 * @covers \Roave\BetterReflection\Reflection\ReflectionType
 */
class ReflectionTypeTest extends TestCase
{
    /** @var Reflector|MockObject */
    private $reflector;

    protected function setUp() : void
    {
        parent::setUp();

        $this->reflector = $this->createMock(Reflector::class);
    }

    public function testCreateFromType() : void
    {
        $typeInfo = ReflectionType::createFromTypeAndReflector(new Identifier('string'));
        self::assertInstanceOf(ReflectionType::class, $typeInfo);
    }

    public function testAllowsNull() : void
    {
        $noNullType = ReflectionType::createFromTypeAndReflector(new Identifier('string'));
        self::assertFalse($noNullType->allowsNull());

        $allowsNullType = ReflectionType::createFromTypeAndReflector(new NullableType(new Identifier('string')));
        self::assertTrue($allowsNullType->allowsNull());
    }

    public function testIsBuiltin() : void
    {
        self::assertTrue(ReflectionType::createFromTypeAndReflector(new Identifier('string'))->isBuiltin());
        self::assertTrue(ReflectionType::createFromTypeAndReflector(new Identifier('int'))->isBuiltin());
        self::assertTrue(ReflectionType::createFromTypeAndReflector(new Identifier('array'))->isBuiltin());
        self::assertTrue(ReflectionType::createFromTypeAndReflector(new Identifier('object'))->isBuiltin());
        self::assertTrue(ReflectionType::createFromTypeAndReflector(new Identifier('iterable'))->isBuiltin());
        self::assertFalse(ReflectionType::createFromTypeAndReflector(new Name('foo'))->isBuiltin());
        self::assertFalse(ReflectionType::createFromTypeAndReflector(new Name\FullyQualified('foo'))->isBuiltin());
    }

    public function testGetName() : void
    {
        self::assertSame('int', ReflectionType::createFromTypeAndReflector(new Identifier('int'))->getName());
        self::assertSame('string', ReflectionType::createFromTypeAndReflector(new Identifier('string'))->getName());
        self::assertSame('array', ReflectionType::createFromTypeAndReflector(new Identifier('array'))->getName());
        self::assertSame('callable', ReflectionType::createFromTypeAndReflector(new Identifier('callable'))->getName());
        self::assertSame('bool', ReflectionType::createFromTypeAndReflector(new Identifier('bool'))->getName());
        self::assertSame('float', ReflectionType::createFromTypeAndReflector(new Identifier('float'))->getName());
        self::assertSame('void', ReflectionType::createFromTypeAndReflector(new Identifier('void'))->getName());
        self::assertSame('object', ReflectionType::createFromTypeAndReflector(new Identifier('object'))->getName());
        self::assertSame('iterable', ReflectionType::createFromTypeAndReflector(new Identifier('iterable'))->getName());

        self::assertSame('Foo\Bar\Baz', ReflectionType::createFromTypeAndReflector(new Name('Foo\Bar\Baz'))->getName());
		self::assertSame('Foo\Bar\Baz', ReflectionType::createFromTypeAndReflector(new Name\FullyQualified('Foo\Bar\Baz'))->getName());
    }

    public function testImplicitCastToString() : void
    {
		self::assertSame('int', (string) ReflectionType::createFromTypeAndReflector(new Identifier('int')));
		self::assertSame('string', (string) ReflectionType::createFromTypeAndReflector(new Identifier('string')));
		self::assertSame('array', (string) ReflectionType::createFromTypeAndReflector(new Identifier('array')));
		self::assertSame('callable', (string) ReflectionType::createFromTypeAndReflector(new Identifier('callable')));
		self::assertSame('bool', (string) ReflectionType::createFromTypeAndReflector(new Identifier('bool')));
		self::assertSame('float', (string) ReflectionType::createFromTypeAndReflector(new Identifier('float')));
		self::assertSame('void', (string) ReflectionType::createFromTypeAndReflector(new Identifier('void')));
		self::assertSame('object', (string) ReflectionType::createFromTypeAndReflector(new Identifier('object')));
		self::assertSame('iterable', (string) ReflectionType::createFromTypeAndReflector(new Identifier('iterable')));

		self::assertSame('Foo\Bar\Baz', (string) ReflectionType::createFromTypeAndReflector(new Name('Foo\Bar\Baz')));
		self::assertSame('Foo\Bar\Baz', (string) ReflectionType::createFromTypeAndReflector(new Name\FullyQualified('Foo\Bar\Baz')));
    }
}
