<?php


use SspStaff\ADT\Internal\MatchExpressionPrototype;

if (!function_exists('match_')) {
    /**
     * @template T
     * @param class-string<T> $classname
     * @return MatchExpressionPrototype<T>
     */
    function match_(string $classname) : MatchExpressionPrototype {
        return new MatchExpressionPrototype($classname);
    }
}
