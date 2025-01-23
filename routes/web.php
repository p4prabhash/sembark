<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UrlController;


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

Route::get('/', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);


// Route::middleware(['auth', 'superadmin'])->group(function () {
// });
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'index'])->name('dashboard');
    Route::get('/invited-customers', [InvitationController::class, 'viewInvitedCustomers'])->name('invited.customers');
    Route::get('/createinvite', [InvitationController::class, 'createinvite'])->name('invitation.create');
    Route::post('/invite', [InvitationController::class, 'sendInvite'])->name('send.invite');
    // members
    Route::get('/members', [MemberController::class, 'members'])->name('members');
    Route::get('/createinvitemember', [MemberController::class, 'createinvitemember'])->name('invitation.createmember');
    Route::post('/invitemember', [MemberController::class, 'sendInvitemember'])->name('send.invitemember');
    
      // URL Shortener Routes
      Route::get('/urls/create', [UrlController::class, 'create'])->name('urls.create');
      Route::post('/urlstore', [UrlController::class, 'store'])->name('urls.store');
      Route::get('/urls', [UrlController::class, 'index'])->name('urls.index');
      Route::get('urls/download', [UrlController::class, 'downloadCsv'])->name('urls.download');

});


