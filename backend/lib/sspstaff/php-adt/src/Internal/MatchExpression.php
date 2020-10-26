<?php


namespace SspStaff\Support\MatchExpression;

use Closure;
use SspStaff\ADT\Internal\CaseEval;
use SspStaff\ADT\Internal\CaseList;
use SspStaff\ADT\Internal\CaseList\Cons;
use SspStaff\ADT\Internal\NonEmpty;

/**
 * Class MatchExpression
 * @package SspStaff\Support\MatchExpression
 * @template T
 * @template R
 */
class MatchExpression
{
    /** @var class-string<T> */
    private $classname;

    /** @var NonEmpty<CaseEval<R>> */
    private $caseBranches;

    /**
     * MatchExpression constructor.
     * @param class-string<T> $classname
     * @param NonEmpty<CaseEval<R>> $caseBranches
     */
    public function __construct (string $classname, NonEmpty $caseBranches) {
        $this->classname = $classname;
        $this->caseBranches = $caseBranches;
    }

    /**
     * @param string $caseClass
     * @param Closure(mixed...):R $eval
     * @return MatchExpression<T, R>
     */
    public function case (string $caseClass, \Closure $eval) :MatchExpression {
        return new MatchExpression(
            $this->classname,
            NonEmpty::cons(CaseEval::case($caseClass, $eval), $this->caseBranches)
        );
    }

    public function default (\Closure $eval) :MatchExpression {
        return new MatchExpression(
            $this->classname,
            NonEmpty::cons(CaseEval::default($eval), $this->caseBranches)
        );
    }

    /**
     * @param T $value
     * @return R
     */
    public function on ($value) {
        $caseList = $this->caseBranches->toCaseList();
        while($caseList instanceof Cons) {
            /** @var CaseEval<R> $h
             *  @var CaseList<CaseEval<R>> $t  */
            list ($h, $t) = $caseList->uncons();

            if (!$h->isMatched($value)) {
                $caseList = $t;
                continue;
            }

            switch (true) {
                case $h instanceof CaseEval\Default_:
                    return $h->getEval()();
            }

            $caseList = $t;
        }
    }

    private function go () {

    }
}
