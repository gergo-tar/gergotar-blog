<?php

use App\Blog\Livewire\Blog;
use App\Blog\Livewire\PostShow;
use Illuminate\Support\Facades\Route;

Route::localized(function () {
    Route::prefix('blog')->group(function () {
        Route::get('/', Blog::class)->name('blog');

        Route::name('blog.')->group(function () {
            Route::prefix('posts')->name('posts.')->group(function () {
                Route::get('{slug}', PostShow::class)->name('show');
            });
        });
    });
});
