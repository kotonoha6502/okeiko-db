<?php


namespace SspStaff\ADT\Internal;

/**
 * Class CaseEval
 * @package SspStaff\ADT\Internal
 * @template R
 */
abstract class CaseEval
{
    /**
     * @template R
     * @param string $caseClass
     * @param \Closure(mixed...):R $eval
     * @return CaseEval<R>
     */
    public static function case (string $caseClass, \Closure $eval) :CaseEval {
        return new CaseEval\Case_ ($caseClass, $eval);
    }

    /**
     * @template R
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

    public function getEval(): \Closure
    {
        switch (true) {
            case $this instanceof CaseEval\Case_:
                return $this->eval;

            case $this instanceof CaseEval\Default_:
                return $this->eval;
        }
    }
}
