<?php


namespace SspStaff\ADT\Internal\CaseEval;


use SspStaff\ADT\Internal\CaseEval;

/**
 * @template R
 * @extends CaseEval<R>
 */
final class Default_ extends CaseEval
{
    /** @var \Closure():R */
    protected $eval;

    /**
     * Default_ constructor.
     * @param \Closure():R $eval
     */
    public function __construct (\Closure $eval) {
        $this->eval = $eval;
    }

}
