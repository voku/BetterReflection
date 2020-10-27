<?php

namespace Roave\BetterReflectionTest\Fixture;

interface InterfaceWithToStringAndStringable extends \Stringable
{

    public function __toString(): string;

}
