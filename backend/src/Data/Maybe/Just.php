<?php


namespace OkeikoDb\Data\Maybe;


use OkeikoDb\Data\Maybe;

/**
 * Class Just
 * @package OkeikoDb\Data\Maybe
 * @template T
 * @extends Maybe<T>
 */
final class Just extends Maybe
{
    /** @var T */
    protected $value;

    /**
     * Just constructor.
     * @param T $value
     */
    public function __construct ($value) {
        $this->value = $value;
    }
}
