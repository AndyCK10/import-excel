<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\MemberPointImportController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/', [MemberPointImportController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [MemberPointImportController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route::get('/import', [ImportController::class, 'showForm'])->name('excel.import');

    Route::get('/points', [MemberPointImportController::class, 'index'])->name('points.index');
    Route::post('/points-preview', [MemberPointImportController::class, 'previewExcel'])->name('points.preview');
    Route::post('/points-import', [MemberPointImportController::class, 'importExcel'])->name('points.import');

    // Route::post('/point/import', [MemberPointImportController::class, 'importExcel'])->name('import.users');

    Route::get('/oldformdata', [MemberPointImportController::class, 'create'])->name('oldformdata');
    Route::post('/oldformdata', [MemberPointImportController::class, 'create'])->name('oldformdata');
});

require __DIR__.'/auth.php';
