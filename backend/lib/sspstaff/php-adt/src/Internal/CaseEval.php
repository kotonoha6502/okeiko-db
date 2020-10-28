<?php


namespace SspStaff\ADT\Internal;

use SspStaff\ADT\Exceptions\PatternMatchException;

/**
 * @template R
 */
abstract class CaseEval
{
    /**
     * @param string $caseClass
     * @param \Closure(mixed...):R $eval
     * @return CaseEval<R>
     */
    public static function case (string $caseClass, \Closure $eval) :CaseEval {
        return new CaseEval\Case_ ($caseClass, $eval);
    }

    /**
     * @param \Closure():R $eval
     * @return CaseEval<R>
     */
    public static function default (\Closure $eval) :CaseEval {
        return new CaseEval\Default_($eval);
    }

    /**
     * @param object $test
     * @return bool
     */
    public function isMatched (object $test) :bool {
        if (!is_object($test)) {
            throw new \InvalidArgumentException("Pattern-matching on non-object value is not supported");
        }
        $fullQualified = get_class($test);

        switch (true) {
            case $this instanceof CaseEval\Case_:
                return $this->caseClass === $fullQualified;

            case $this instanceof CaseEval\Default_:
                return true;
        }

        throw new \RuntimeException("Should not happen exception - pattern match failure");
    }

    /**
     * @param mixed $value
     * @return R
     */
    public function eval($value)
    {
        switch (true) {
            case $this instanceof CaseEval\Case_:
                return ($this->eval)(...$this->getConstructorArgs($value));

            case $this instanceof CaseEval\Default_:
                return ($this->eval)();
        }

        throw new \RuntimeException("Should not happen exception - pattern match failure");
    }

    /**
     * @param mixed $value
     * @return array
     */
    private function getConstructorArgs ($value) :array {
        try {
            $reflectionObject = new \ReflectionObject($value);
        }
        catch (\Throwable $e) {
            throw new PatternMatchException("Should not happen exception");
        }

        $args = [];
        $constructor = $reflectionObject->getConstructor();
        if ($constructor instanceof \ReflectionMethod) {
            foreach ($constructor->getParameters() as $parameter) {
                try {
                    $prop = $reflectionObject->getProperty($parameter->getName());
                    $prop->setAccessible(true);
                    $args[] = $prop->getValue($value);
                }
                catch (\ReflectionException $e) {
                    throw new PatternMatchException(
                        "Failed to re-produce arguments to data constructor",
                        $e->getCode(),
                        $e->getPrevious()
                    );
                }
            }
        }

        return $args;
    }
}
