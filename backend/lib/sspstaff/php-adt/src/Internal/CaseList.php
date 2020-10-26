<?php


namespace SspStaff\ADT\Internal;

/**
 * Class CaseList
 * @package SspStaff\Support\MatchExpression
 * @template T
 */
abstract class CaseList
{
    /**
     * @return CaseList<mixed>
     */
    public static function nil () : CaseList {
        return new CaseList\Nil;
    }

    /**
     * @template S
     * @param S $x
     * @param CaseList<S> $xs
     * @return CaseList<S>
     */
    public static function cons ($x, CaseList $xs) :CaseList {
        return new CaseList\Cons($x, $xs);
    }

    /**
     * @return null|array{ 0: T, 1: CaseList<T> }
     */
    public function uncons () {
        switch (true) {
            case $this instanceof CaseList\Nil:
                return null;

            case $this instanceof CaseList\Cons:
                return [ $this->head, $this->tail ];
        }

        throw new \RuntimeException("Should not happen exception - pattern match failure");
    }

}
