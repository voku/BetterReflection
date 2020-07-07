<?php

declare(strict_types=1);

namespace Roave\BetterReflection\Reflection;

use PhpParser\Node\Identifier;
use PhpParser\Node\Name;

class ReflectionNamedType extends ReflectionType
{
    /** @var string */
    private $name;

    /**
     * @param Identifier|Name $type
     */
    public function __construct($type, bool $allowsNull)
    {
        parent::__construct($type, $allowsNull);
        $this->name = (string) $type;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function __toString() : string
    {
        $name = '';
        if ($this->allowsNull()) {
            $name .= '?';
        }

        return $name . $this->getName();
    }
}
