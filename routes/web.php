<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\TermController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [UserAuthController::class, 'login'])->middleware('alreadyLoggedIn');
Route::get('register', [UserAuthController::class, 'register'])->middleware('alreadyLoggedIn');
Route::get('verify', [UserAuthController::class, 'verify'])->name('auth.verify');
Route::post('create', [UserAuthController::class, 'create'])->name('auth.create');
Route::post('check', [UserAuthController::class, 'check'])->name('auth.check');
Route::get('profile', [UserAuthController::class, 'profile'])->middleware('isLogged');
Route::get('logout', [UserAuthController::class, 'logout']);
Route::get('resendEmail/id/{id}', [UserAuthController::class, 'resendEmail']);

Route::resource('users', UserAuthController::class);
Route::get('search', [UserAuthController::class, 'search'])->name('users.search');
Route::get('userUnverify/id/{id}', [UserAuthController::class, 'unverify'])->name('users.unverify')->middleware('isLogged');
Route::delete('userDelete/{id}', [UserAuthController::class, 'delete'])->name('users.delete')->middleware('isLogged');


Route::resource('terms', TermController::class)->middleware('isLogged');
Route::get('termsPublish/id/{id}', [TermController::class, 'publish'])->name('terms.publish')->middleware('isLogged');
Route::get('termsUnpublish/id/{id}', [TermController::class, 'unpublish'])->name('terms.unpublish')->middleware('isLogged');
Route::get('termsConditions', [TermController::class, 'lastActiveTerm'])->name('term.last');
