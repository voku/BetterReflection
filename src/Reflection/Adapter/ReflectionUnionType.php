<?php

declare(strict_types=1);

namespace Roave\BetterReflection\Reflection\Adapter;

use ReflectionUnionType as CoreReflectionUnionType;
use Roave\BetterReflection\Reflection\ReflectionUnionType as BetterReflectionType;
use function array_map;

class ReflectionUnionType extends CoreReflectionUnionType
{
    /** @var BetterReflectionType */
    private $betterReflectionType;

    public function __construct(BetterReflectionType $betterReflectionType)
    {
        $this->betterReflectionType = $betterReflectionType;
    }

    public function __toString() : string
    {
        return $this->betterReflectionType->__toString();
    }

    public function allowsNull() : bool
    {
        return $this->betterReflectionType->allowsNull();
    }

    /**
     * @return \ReflectionType[]
     */
    public function getTypes() : array
    {
        return array_map(static function (\Roave\BetterReflection\Reflection\ReflectionType $type) : \ReflectionType {
            return ReflectionType::fromReturnTypeOrNull($type);
        }, $this->betterReflectionType->getTypes());
    }

    public function isBuiltin() : bool
    {
        return false;
    }
}
