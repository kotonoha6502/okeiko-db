<?php


namespace SspStaff\ADT\Internal\CaseList;


use SspStaff\ADT\Internal\CaseList;

/**
 * Class Cons
 * @package SspStaff\ADT\Internal\CaseList
 * @template T
 * @extends CaseList<T>
 */
final class Cons extends CaseList
{
    /**
     * @var T
     */
    protected $head;

    /**
     * @var CaseList<T>
     */
    protected $tail;

    /**
     * Cons constructor.
     * @param T $head
     * @param CaseList<T> $tail
     */
    public function __construct($head, CaseList $tail) {
        $this->head = $head;
        $this->tail = $tail;
    }
}
