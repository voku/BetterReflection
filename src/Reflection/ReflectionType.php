<?php

declare(strict_types=1);

namespace Roave\BetterReflection\Reflection;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;

abstract class ReflectionType
{
    /** @var bool */
    private $allowsNull;

    protected function __construct(bool $allowsNull)
    {
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
     * Convert this string type to a string
     */
    abstract public function __toString() : string;
}
