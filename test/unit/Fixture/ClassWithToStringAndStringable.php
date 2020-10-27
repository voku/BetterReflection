<?php

namespace Roave\BetterReflectionTest\Fixture;

class ClassWithToStringAndStringable implements \Stringable
{

    public function __toString(): string
    {
        return 'foo';
    }

}
