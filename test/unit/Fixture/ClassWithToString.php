<?php

namespace Roave\BetterReflectionTest\Fixture;

class ClassWithToString
{

    public function __toString(): string
    {
        return 'foo';
    }

}
