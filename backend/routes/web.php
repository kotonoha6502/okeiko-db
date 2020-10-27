<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', function () :array {
    $a = OkeikoDb\Data\Maybe::nothing();

    return match_(OkeikoDb\Data\Maybe::class)
        ->case(OkeikoDb\Data\Maybe\Nothing::class, function () :bool {
            return false;
        })
        ->case(OkeikoDb\Data\Maybe\Just::class, function (int $value) :bool {
            return true;
        })
        ->on($a);

});

Route::any('{catchall}', function () {
    return view('welcome');
})->where('catchall', '.*');
