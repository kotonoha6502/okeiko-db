<?php

namespace OkeikoDb\Data;

/**
 * @ssp-adt
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
     * @param T $value
     * @return Maybe<T>
     */
    public static function just ($value) :Maybe {
        return new Maybe\Just($value);
    }
}
