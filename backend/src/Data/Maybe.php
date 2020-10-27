<?php

namespace OkeikoDb\Data;

/**
 * Class Maybe
 * @package OkeikoDb\Data
 * @template T
 */
abstract class Maybe
{
    /**
     * @return Maybe<mixed>
     */
    public static function nothing() :Maybe {
        return new Maybe\Nothing;
    }

    /**
     * @template A
     * @param A $value
     * @return Maybe<A>
     */
    public static function just ($value) :Maybe {
        return new Maybe\Just($value);
    }
}
