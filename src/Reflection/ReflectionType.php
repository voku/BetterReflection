<?php

declare(strict_types=1);

namespace Roave\BetterReflection\Reflection;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use function array_key_exists;
use function strtolower;

abstract class ReflectionType
{
    private const BUILT_IN_TYPES = [
        'int'      => null,
        'float'    => null,
        'string'   => null,
        'bool'     => null,
        'callable' => null,
        'self'     => null,
        'parent'   => null,
        'array'    => null,
        'iterable' => null,
        'object'   => null,
        'void'     => null,
        'mixed'    => null,
    ];

    /** @var Identifier|Name|UnionType */
    private $type;

    /** @var bool */
    private $allowsNull;

    /**
     * @param Identifier|Name|NullableType|UnionType $type
     */
    protected function __construct($type, bool $allowsNull)
    {
        $this->type       = $type;
        $this->allowsNull = $allowsNull;
    }

    /**
     * @param Identifier|Name|NullableType|UnionType $type
     */
    public static function createFromTypeAndReflector($type) : self
    {
        $allowsNull = false;
        if ($type instanceof NullableType) {
            $type       = $type->type;
            $allowsNull = true;
        }

        if ($type instanceof Identifier || $type instanceof Name) {
            return new ReflectionNamedType($type, $allowsNull);
        }

        return new ReflectionUnionType($type, $allowsNull);
    }

    /**
     * Does the parameter allow null?
     */
    public function allowsNull() : bool
    {
        return $this->allowsNull;
    }

    /**
     * Checks if it is a built-in type (i.e., it's not an object...)
     *
     * @see https://php.net/manual/en/reflectiontype.isbuiltin.php
     */
    public function isBuiltin() : bool
    {
        if ($this->type instanceof Identifier) {
            return array_key_exists(strtolower($this->type->name), self::BUILT_IN_TYPES);
        }

        return false;
    }

    /**
     * Convert this string type to a string
     */
    abstract public function __toString() : string;
}
