<?php

use App\Livewire\GuestList;
use App\Livewire\LandingPage;
use App\Livewire\LandingPage\Home;
use App\Livewire\RSVP\RespondUser;
use App\Livewire\LandingPage\About;
use App\Livewire\RSVP\RespondedPage;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::get('/rsvp', RespondUser::class)->name('rsvp');
Route::get('/rsvp/thank-you/{guestName}', RespondedPage::class)->name('tq_rsvp');
Route::get('/guest/list', GuestList::class)->name('guest_list');

require __DIR__ . '/auth.php';

// Route::get('/', Home::class)->name('home');
// Route::get('/about', About::class)->name('about');
