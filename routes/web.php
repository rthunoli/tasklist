<?php
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LivewireController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\QueryController;
use Illuminate\Http\Request;
// use App\Http\Controllers\PaginationController;
// use App\Http\Controllers\SearchController;
// use App\Http\Controllers\TaskController;
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

Route::get('/', function () {
    return view('home');
})->middleware(['auth'])->name('home');

Route::get('/reports', function () {
    return view('reports');
})->middleware(['auth'])->name('reports');

Route::get('/about', function () {
    return view('about');
})->middleware(['auth'])->name('about');


Route::get('/selectdb/{db}', function ($db) {
    session()->put('db', $db);
    return back();
})->middleware(['auth'])->name('selectdb');


Route::get('/session', function () {
    return session()->all();
})->middleware(['auth'])->name('session');


// Route::get('/token', function (Request $request) {
//     $token = $request->session()->token();
//     return $token;

// });

// Route::get('/paginate', [PaginationController::class, 'index']);

// Route::get('/old-search', [SearchController::class, 'index'])->middleware('auth')->name('old-search');

Route::get('/livewire-test',[LivewireController::class,'test'])->name('livewire-test');

Route::get('/search',[LivewireController::class,'xsearch'])->middleware(['auth','dbok'])->name('search');

Route::get('/transport_defaulters',[ReportController::class,'transport_defaulters'])
    ->middleware(['auth','dbok'])->name('transport_defaulters');

Route::get('/transport_paid',[ReportController::class,'transport_paid'])
    ->middleware(['auth','dbok'])->name('transport_paid');

Route::get('/fee_paid',[ReportController::class,'fee_paid'])
    ->middleware(['auth','dbok'])->name('fee_paid');

Route::get('/fee_paid_headwise',[ReportController::class,'fee_paid_headwise'])
    ->middleware(['auth','dbok'])->name('fee_paid_headwise');

Route::get('/printer-test',[PrintController::class,'index'])->name('printer-test');

Route::get('/query-test', [QueryController::class,'index'])
    ->middleware(['auth','dbok'])->name('query-test');


require __DIR__ . '/auth.php';

// Route::resource('tasks', TaskController::class)->middleware('auth');
