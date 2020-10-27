<?php

namespace SspStaff\ADT\Internal\CaseEval;

use SspStaff\ADT\Internal\CaseEval;

/**
 * @template R
 * @extends CaseEval<R>
 */
final class Case_ extends CaseEval
{
    /** @var string */
    protected $caseClass;

    /** @var \Closure(mixed...):R */
    protected $eval;

    /**
     * Case_ constructor.
     * @param string $caseClass
     * @param \Closure(mixed...):R $eval
     */
    public function __construct (string $caseClass, \Closure $eval) {
        $this->caseClass = $caseClass;
        $this->eval = $eval;
    }

}
