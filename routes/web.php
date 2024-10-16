<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/login', 'login')->name('login');

//Route::get('/login', Login::class)->name('login');

Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});

Route::middleware('auth')->group(function () {
    Volt::route('/', 'test');
    Volt::route('/new', 'new')->name('new');
    Volt::route('/merkez', 'merkezim.merkez')->name('merkezim.merkez');

    Route::prefix('admin')->group(function () {

        Route::prefix('action')->group(function () {

            Volt::route('/note', 'admin.actions.client_note_add')
                ->name('admin.actions.client_note_add')
                ->middleware('can:action_client_add_note');

            Volt::route('/create_service', 'admin.actions.client_create_service')
                ->name('admin.actions.client_create_service')
                ->middleware('can:action_client_create_service');

            Volt::route('/client_create', 'admin.actions.client_create')
                ->name('admin.actions.client_create')
                ->middleware('can:action_client_create');

            Route::prefix('/sale')->group(function () {
                Volt::route('/create', 'admin.actions.client_sale.create')
                    ->name('admin.actions.client_sale_create');

            })->middleware('can:action_client_sale');

            Route::prefix('/offer')->group(function () {

                Volt::route('/customer', 'admin.actions.client_offer.customer')
                    ->name('admin.actions.client_offer_customer');
                Volt::route('/create', 'admin.actions.client_offer.create')
                    ->name('admin.actions.client_offer_create');

            })->middleware('can:action_client_offer');

        });

        Route::prefix('client')->group(function () {
            Volt::route('/', 'admin.client.index')
                ->middleware('can:clients')
                ->name('admin.clients');
            Volt::route('/{user?}', 'admin.client.profil.index')->name('admin.client.profil.index')->middleware('can:client_profil');
            Volt::route('/{user}/home', 'admin.client.profil.anasayfa')->name('admin.client.profil.anasayfa')->middleware('can:client_profil');
        });

        /*Route::prefix('client')->group(function () {
            Volt::route('/', 'admin.client.index')->name('admin.client.index');
            Volt::route('/{user?}', 'admin.client.profil.index')->name('admin.client.profil.index');
        });*/

        Route::prefix('sale')->group(function () {
            Volt::route('/create/{client?}', 'admin.sale.create')->name('admin.sale.create');
        });

        Route::prefix('defination')->group(function () {
            Volt::route('/branch', 'admin.settings.defination.branch.index')->name('admin.settings.defination.branch');
            Volt::route('/category', 'admin.settings.defination.category.index')->name('admin.settings.defination.category');
            Volt::route('/room', 'admin.settings.defination.room.index')->name('admin.settings.defination.room');
            Volt::route('/kasa', 'admin.settings.defination.kasa.index')->name('admin.settings.defination.kasa');
            Volt::route('/service', 'admin.settings.defination.service.index')->name('admin.settings.defination.service');
            Volt::route('/product', 'admin.settings.defination.product.index')->name('admin.settings.defination.product');

            Route::prefix('package')->group(function () {
                Volt::route('/', 'admin.settings.defination.package.index')->name('admin.settings.defination.package');
                Volt::route('/{package}/items', 'admin.settings.defination.package.items')->name('admin.settings.defination.package.items');
            })->middleware('can:defination_package');
        });
    });
});

Route::get('/per', function () {
    dd(Auth::user()->getAllPermissions());
});
