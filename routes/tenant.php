<?php

declare(strict_types=1);

use App\Models\Offer;
use App\Models\Sale;
use App\SaleStatus;
use App\Support\Spotlight;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Volt::route('/login', 'login')->name('login');

    Route::get('/spotlight2', [Spotlight::class, 'search'])->name('mary.spotlight');

    Volt::route('/', 'client.index')->name('client.index');
    Volt::route('/service', 'client.service')->name('client.service');
    Volt::route('/contact', 'client.contact')->name('client.contact');
    Volt::route('/location', 'client.location')->name('client.location');

    Route::get('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');

    Route::middleware('auth')->group(function () {

        Volt::route('/seans', 'client.profil.seans')
            ->name('client.profil.seans');
        Volt::route('/randevu', 'client.profil.appointment')
            ->name('client.profil.appointment');
        Volt::route('/teklif', 'client.profil.offers')
            ->name('client.profil.offers');
        Volt::route('/kupon', 'client.profil.coupons')
            ->name('client.profil.coupons');
        Volt::route('/taksit', 'client.profil.taksits')
            ->name('client.profil.taksits');

        Route::prefix('admin')->group(function () {
            Volt::route('/', 'test')->name('admin.index');

            Route::prefix('action')->group(function () {

                Volt::route('/note', 'admin.actions.client_note_add')
                    ->name('admin.actions.client_note_add')
                    ->middleware('can:action_client_add_note');

                Volt::route('/create_service', 'admin.actions.client_create_service')
                    ->name('admin.actions.client_create_service')
                    ->middleware('can:action_client_create_service');

                Volt::route('/transfer_service', 'admin.actions.client_transfer_service')
                    ->name('admin.actions.client_transfer_service')
                    ->middleware('can:action_client_transfer_service');

                Volt::route('/add_label', 'admin.actions.client_add_label')
                    ->name('admin.actions.client_add_label')
                    ->middleware('can:action_client_add_label');

                Volt::route('/use_service', 'admin.actions.client_use_service')
                    ->name('admin.actions.client_use_service')
                    ->middleware('can:action_client_use_service');

                Volt::route('/client_create', 'admin.actions.client_create')
                    ->name('admin.actions.client_create')
                    ->middleware('can:action_client_create');

                Volt::route('/product_sale', 'admin.actions.client_product_sale')
                    ->name('admin.actions.client_product_sale')
                    ->middleware('can:action_client_product_sale');

                Volt::route('/sale_create', 'admin.actions.client_sale.create')
                    ->name('admin.actions.client_sale_create')
                    ->middleware('can:action_client_sale');

                Volt::route('/coupon_create', 'admin.actions.create_coupon')
                    ->name('admin.actions.create_coupon')
                    ->middleware('can:action_create_coupon');

                Volt::route('/offer_create', 'admin.actions.client_create_offer')
                    ->name('admin.actions.client_create_offer')
                    ->middleware('can:action_client_create_offer');

                Volt::route('/taksit_create', 'admin.actions.client_create_taksit')
                    ->name('admin.actions.client_create_taksit')
                    ->middleware('can:action_client_create_taksit');

                Volt::route('/appointment_create', 'admin.actions.client_create_appointment')
                    ->name('admin.actions.client_create_appointment')
                    ->middleware('can:action_client_create_appointment');

                Volt::route('/appointment_close', 'admin.actions.close_appointment')
                    ->name('admin.actions.close_appointment')
                    ->middleware('can:action_close_appointment');

                /*Route::prefix('/offer')->group(function () {

                    Volt::route('/customer', 'admin.actions.client_offer.customer')
                        ->name('admin.actions.client_offer_customer');
                    Volt::route('/create', 'admin.actions.client_offer.create')
                        ->name('admin.actions.client_offer_create');

                })->middleware('can:action_client_offer');*/

                Volt::route('/adisyon', 'admin.actions.adisyon.index')
                    ->name('admin.actions.adisyon_create')
                    ->middleware('can:action_adisyon_create');

                Volt::route('/reminder', 'admin.actions.create_reminder')
                    ->name('admin.actions.create_reminder')
                    ->middleware('can:action_create_reminder');

                Volt::route('/payment_tracking', 'admin.actions.create_payment_tracking')
                    ->name('admin.actions.create_payment_tracking')
                    ->middleware('can:action_create_payment_tracking');

                Volt::route('/tahsilat', 'admin.actions.client_tahsilat')
                    ->name('admin.actions.tahsilat')
                    ->middleware('can:action_client_tahsilat');

            });

            Route::prefix('kasa')->group(function () {
                Volt::route('/', 'admin.kasa.index')->name('admin.kasa')->middleware('can:page_kasa');
                Volt::route('/detail', 'admin.kasa.detail')->name('admin.kasa.detail')->middleware('can:page_kasa_detail');
                Volt::route('/mahsup', 'admin.actions.kasa.mahsup')->name('admin.kasa.mahsup')->middleware('can:kasa_mahsup');
                Volt::route('/payment', 'admin.actions.kasa.make_payment')->name('admin.kasa.make_payment')->middleware('can:kasa_make_payment');
            });

            Route::prefix('appointment')->group(function () {
                Volt::route('/', 'admin.appointment.index')->name('admin.appointment')->middleware('can:page_randevu');
            });

            Route::prefix('agenda')->group(function () {
                Volt::route('/', 'admin.agenda.index')->name('admin.agenda')->middleware('can:page_agenda');
            });

            Route::prefix('request')->group(function () {
                Volt::route('/', 'admin.talep.index')->name('admin.talep')->middleware('can:page_talep');
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
        })->middleware('role:admin,staff');
    });

    Route::get('/apptest', function () {

        $info = [
            'branch_id' => 2,
            'search_date_first' => '2024-11-01',
            'search_date_last' => '2024-11-15',
            'category_id' => 1,
            'duration' => 60,
            'type' => 'range',
        ];

        $info_multiple = [
            'branch_id' => 2,
            'category_id' => 1,
            'duration' => 60,
            'type' => 'multiple',
            'dates' => [
                '2024-11-04',
                '2024-11-03',
                '2024-11-02',
            ],
        ];

        dump(\App\Actions\Appointment\CheckAvailableAppointments::run($info));
        //dump(\App\Actions\Appointment\CheckAvailableAppointments::run($info_multiple));

    });

    Route::get('/ta', function () {
        $branch = \App\Models\Branch::first();
        dump($branch->isOpen(\Carbon\Carbon::createFromFormat('Y-m-d', '2024-11-03')->toDateTime()));
        dump($branch->startTimeByDay(\Carbon\Carbon::createFromFormat('Y-m-d', '2024-11-03')->toDateTime())->start()->format('H:i'));
        dump($branch->startTimeByDay(\Carbon\Carbon::createFromFormat('Y-m-d', '2024-11-03')->toDateTime())->end()->format('H:i'));
    });

    Route::get('/per', function () {
        dd(Auth::user()->getAllPermissions());
    });

    //Route::get('/login', Login::class)->name('login');

    Route::get('c', function () {
        $sale = Sale::all();

        foreach ($sale as $sale) {
            $sale->status = SaleStatus::success;
            $sale->save();
        }

    });

    Route::get('/tt', function () {

        $offer = Offer::where('id', 8)->with('items')->first();

        foreach ($offer->items as $item) {
            dump($item);
        }

    });

});
