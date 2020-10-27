<?php


namespace SspStaff\ADT\Internal;


use SspStaff\ADT\MatchExpression;

/**
 * @template T
 */
class MatchExpressionPrototype
{
    /** @var class-string<T>  */
    private $className;

    public function __construct (string $className) {
        $this->className = $className;
    }

    /**
     * @template R
     * @param string $caseClass
     * @param \Closure(mixed...):R $caseBranch
     * @return MatchExpression<T, R>
     */
    public function case (string $caseClass, \Closure $caseBranch) :MatchExpression {
        /** @var NonEmpty<CaseEval<R>> $caseBranches */
        $caseBranches = new NonEmpty(CaseEval::case($caseClass, $caseBranch), CaseList::nil());
        return new MatchExpression($this->className, $caseBranches);
    }

    /**
     * @template R
     * @param \Closure():R $eval
     * @return MatchExpression<T, R>
     */
    public function default (\Closure $eval) :MatchExpression {
        /** @var NonEmpty<CaseEval<R>> $caseBranches */
        $caseBranches = new NonEmpty(CaseEval::default($eval), CaseList::nil());
        return new MatchExpression($this->className, $caseBranches);
    }

}
