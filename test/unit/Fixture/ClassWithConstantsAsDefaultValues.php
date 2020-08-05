<?php

namespace Roave\BetterReflectionTest\Fixture {

    use Roave\BetterReflectionTest\FixtureOther\OtherClass;
    use const Roave\BetterReflectionTest\FixtureOther\OTHER_NAMESPACE_CONST;

	const THIS_NAMESPACE_CONST = 'this_namespace';
	const UNSURE_CONSTANT = 'here';

    class ParentClassWithConstant
    {
        public const PARENT_CONST = 'parent';
    }

    class ClassWithConstantsAsDefaultValues extends ParentClassWithConstant
    {
        public const MY_CONST = 'my';

        public function method($param1 = self::MY_CONST, $param2 = self::PARENT_CONST,
            $param3 = OtherClass::MY_CONST, $param4 = THIS_NAMESPACE_CONST,
            $param5 = OTHER_NAMESPACE_CONST, $param6 = GLOBAL_CONSTANT, $param7 = UNSURE_CONSTANT, string $param8 = PHP_EOL)
        {
        }
    }
}

namespace Roave\BetterReflectionTest\FixtureOther {

    const OTHER_NAMESPACE_CONST = 'other_namespace';

    class OtherClass
    {
        public const MY_CONST = 'other';
    }
}

namespace {

	const GLOBAL_CONSTANT = 1;
	const UNSURE_CONSTANT = 'there';

}
