<?php


namespace SspStaff\ADT\Internal;

/** @template T */
final class NonEmpty
{
    /**
     * @var T
     */
    private $head;
    /**
     * @var CaseList<T>
     */
    private $tail;

    /**
     * NonEmpty constructor.
     * @param T $head
     * @param CaseList<T> $tail
     */
    public function __construct ($head, CaseList $tail) {
        $this->head = $head;
        $this->tail = $tail;
    }

    /**
     * @param T $x
     * @param NonEmpty<T> $xs
     * @return NonEmpty<T>
     */
    public static function cons ($x, $xs) {
        return new NonEmpty($x, $xs->toCaseList());
    }

    /**
     * @return array{ 0: T, 1: CaseList<T> }
     */
    public function uncons () {
        return [ $this->head, $this->tail ];
    }

    /**
     * @return CaseList<T>
     */
    public function toCaseList () :CaseList {
        return CaseList::cons($this->head, $this->tail);
    }
}
