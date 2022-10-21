<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductAjaxController;
use App\Http\Controllers\EmployeeAjaxController;

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
Route::resource('products-ajax-crud', ProductAjaxController::class);
Route::resource('employees-ajax-crud', EmployeeAjaxController::class);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';
