<?php

class Foo
{
    public $hasDefault = 123;
    public $noDefault;

    public $defaultWithConstant = 'foo' . \Roave\BetterReflectionTest\Fixture\BY_CONST;
}
