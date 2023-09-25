<?php

use App\Models\Teacher;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('tes', function(){
    $subjects = Teacher::with('subjects')->find(auth()->user()->userable->userable_id);
    dd($subjects->toArray());
    // $subjects = Teacher::with('subjects')->find(1);
    // return $subjects;
});