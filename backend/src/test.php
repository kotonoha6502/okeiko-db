<?php


function test () :string {
    $a = OkeikoDb\Data\Maybe::just(1);

    return match_(OkeikoDb\Data\Maybe::class)
        ->case(OkeikoDb\Data\Maybe\Nothing::class, function () :int {
            return false;
        })
        ->case(OkeikoDb\Data\Maybe\Just::class, function (int $value) :bool {
            return true;
        })
        ->on($a);
}
