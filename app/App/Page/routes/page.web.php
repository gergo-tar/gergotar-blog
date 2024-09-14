<?php

use App\Page\Livewire\Home;
use App\Page\Livewire\About;
use App\Page\Livewire\Contact;
use Illuminate\Support\Facades\Route;

Route::localized(function () {
    Route::get('/', Home::class)->name('home');
    Route::get('about', About::class)->name('about');
    Route::get('contact', Contact::class)->name('contact');
});
