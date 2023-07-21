<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentsController;

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

Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
Route::get('/classes', [ClassesController::class, 'index'])->name('classes.index');