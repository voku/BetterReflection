<?php

declare(strict_types=1);

namespace Roave\BetterReflection\Reflection\Adapter;

use LogicException;
use ReflectionType as CoreReflectionType;
use Roave\BetterReflection\Reflection\ReflectionNamedType as BetterReflectionNamedType;
use Roave\BetterReflection\Reflection\ReflectionType as BetterReflectionType;
use Roave\BetterReflection\Reflection\ReflectionUnionType as BetterReflectionUnionType;
use function get_class;
use function sprintf;

class ReflectionType
{
    private function __construct()
    {
    }

    public static function fromReturnTypeOrNull(?BetterReflectionType $betterReflectionType) : ?CoreReflectionType
    {
        if ($betterReflectionType === null) {
            return null;
        }

        if ($betterReflectionType instanceof BetterReflectionNamedType) {
            return new ReflectionNamedType($betterReflectionType);
        }

        if ($betterReflectionType instanceof BetterReflectionUnionType) {
            return new ReflectionUnionType($betterReflectionType);
        }

        throw new LogicException(sprintf('%s is not supported.', get_class($betterReflectionType)));
    }
}
