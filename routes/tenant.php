<?php

declare(strict_types=1);

use App\AppointmentStatus;
use App\Enum\SettingsType;
use App\Jobs\SendReportPdfJob;
use App\Livewire\Reports\AppointmentReport;
use App\Livewire\Reports\ClientReport;
use App\Livewire\Reports\KasaReport;
use App\Livewire\Reports\SaleProductReport;
use App\Livewire\Reports\SaleReport;
use App\Livewire\Reports\ServiceReport;
use App\Livewire\Reports\TaksitReport;
use App\Livewire\Statistics\ClientStatistic;
use App\Livewire\Web\Profil\AppointmentPage;
use App\Livewire\Web\Profil\CouponPage;
use App\Livewire\Web\Profil\InvitePage;
use App\Livewire\Web\Profil\OfferPage;
use App\Livewire\Web\Profil\SeansPage;
use App\Livewire\Web\Profil\TaksitPage;
use App\Livewire\Web\Shop\PackagePage;
use App\Livewire\WizardPage;
use App\Models\Branch;
use App\Models\Offer;
use App\Models\Sale;
use App\Models\Settings;
use App\SaleStatus;
use App\Support\Spotlight;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Namu\WireChat\Events\MessageCreated;
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

    /* Route::any('broadcasting/auth', function () {
         dump('bur');

         return true;
     });*/

    Route::any('/paysuccess', function () {
        echo "<script type='text/javascript'>window.parent.postMessage({ type: 'triggerLivewireEvent', data: '".request()->get('id')."', status: 'success', message: 'Ödeme başarıyla alındı.' }, '*');
</script>";
    });
    Route::any('/payerror', function () {
        //dump(request()->all());
        echo "<script type='text/javascript'>window.parent.postMessage({ type: 'triggerLivewireEvent', data: '".request()->get('id')."', status: 'error', message: '".request()->get('fail_message')."' }, '*');
</script>";
    });
    Volt::route('/login', 'login')->name('login');

    Route::get('/logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');

    //Route::get('/spotlight2', [Spotlight::class, 'search'])->name('mary.spotlight');

    Volt::route('/', 'client.index')->name('client.index');
    Volt::route('/service', 'client.service')->name('client.service');
    Volt::route('/contact', 'client.contact')->name('client.contact');
    Volt::route('/location', 'client.location')->name('client.location');

    Route::get('/shop/packages', PackagePage::class)->name('client.shop.packages');

    Route::middleware('auth')->group(function () {

        Route::get('/seans', SeansPage::class)->name('client.profil.seans');
        Route::get('/randevu', AppointmentPage::class)->name('client.profil.appointment');
        Route::get('/taksit', TaksitPage::class)->name('client.profil.taksit');
        Route::get('/teklif', OfferPage::class)->name('client.profil.offer');
        Route::get('/kupon', CouponPage::class)->name('client.profil.coupon');
        Route::get('/davet', InvitePage::class)->name('client.profil.invite');

        Route::prefix('admin')->group(function () {
            Volt::route('/', 'admin-page')->name('admin.index');

            Route::prefix('action')->group(function () {

                /* Volt::route('/note', 'admin.actions.client_note_add')
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

            Route::prefix('/offer')->group(function () {

                     Volt::route('/customer', 'admin.actions.client_offer.customer')
                         ->name('admin.actions.client_offer_customer');
                     Volt::route('/create', 'admin.actions.client_offer.create')
                         ->name('admin.actions.client_offer_create');

                 })->middleware('can:action_client_offer');*/

                /* Volt::route('/adisyon', 'admin.actions.adisyon.index')
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
                     ->middleware('can:action_client_tahsilat');*/

            });

            Route::prefix('kasa')->group(function () {
                Volt::route('/', 'admin.kasa.index')->name('admin.kasa')->middleware('can:page_kasa');
                //Volt::route('/detail', 'admin.kasa.detail')->name('admin.kasa.detail')->middleware('can:page_kasa_detail');
                //Volt::route('/mahsup', 'admin.actions.kasa.mahsup')->name('admin.kasa.mahsup')->middleware('can:kasa_mahsup');
                //Volt::route('/payment', 'admin.actions.kasa.make_payment')->name('admin.kasa.make_payment')->middleware('can:kasa_make_payment');
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

            Route::prefix('reports')->group(function () {
                Route::get('/client', ClientReport::class)->name('admin.reports.client');
                Route::get('/sale', SaleReport::class)->name('admin.reports.sale');
                Route::get('/service', ServiceReport::class)->name('admin.reports.service');
                Route::get('/taksit', TaksitReport::class)->name('admin.reports.taksit');
                Route::get('/appointment', AppointmentReport::class)->name('admin.reports.appointment');
                Route::get('/kasa', KasaReport::class)->name('admin.reports.kasa');
                Route::get('/sale_product', SaleProductReport::class)->name('admin.reports.sale_product');
                Route::get('/talep', \App\Livewire\Reports\TalepReport::class)->name('admin.reports.talep');
                Route::get('/note', \App\Livewire\Reports\NoteReport::class)->name('admin.reports.note');
                Route::get('/adisyon', \App\Livewire\Reports\AdisyonReport::class)->name('admin.reports.adisyon');
                Route::get('/offer', \App\Livewire\Reports\OfferReport::class)->name('admin.reports.offer');
                Route::get('/coupon', \App\Livewire\Reports\CouponReport::class)->name('admin.reports.coupon');
                Route::get('/approve', \App\Livewire\Reports\ApproveReport::class)->name('admin.reports.approve');
            });

            Route::prefix('statistics')->group(function () {
                Route::get('/client', ClientStatistic::class)->name('admin.statistics.client');
            });

            Route::get('/wizard', WizardPage::class)->name('admin.wizard');

            /*Route::prefix('client')->group(function () {
                Volt::route('/', 'admin.client.index')->name('admin.client.index');
                Volt::route('/{user?}', 'admin.client.profil.index')->name('admin.client.profil.index');
            });*/

            /*Route::prefix('sale')->group(function () {
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
            });*/
        })->middleware('role:admin,staff');
    });

    Route::get('/job', function () {
        //SendReportPdfJob::dispatch();
        //dump(\Namu\WireChat\Models\Message::all());
        //MessageCreated::dispatch(\Namu\WireChat\Models\Message::first());
        dispatch(new \Namu\WireChat\Jobs\BroadcastMessage(\Namu\WireChat\Models\Message::create([
            'conversation_id' => 1,
            'sendable_id ' => 1,
            'sendable_type' => \App\Models\User::class,
            'body' => 'asdasd',
            'type' => 'text',
        ])));

        //broadcast(new MessageCreated(\Namu\WireChat\Models\Message::first()))->toOthers();

    });

    Route::get('/cache', function () {});

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

    Route::get('/set', function () {
        Settings::create([
            'data' => [
                'store_name' => 'MARGE GÜZELLİK',
            ],
        ]);
    });

    Route::get('/setb', function () {
        foreach (\App\Tenant::all() as $tenant) {
            $tenant->run(function () {
                Settings::create([
                    'data' => [
                        SettingsType::site_name->name => 'MARGE GÜZELLİK',
                        SettingsType::shop_active->name => true,

                    ],
                ]);

                foreach (Branch::all() as $branch) {
                    Settings::create([
                        'branch_id' => $branch->id,
                        'data' => [
                            SettingsType::client_page_seans->name => true,
                            SettingsType::client_page_seans_show_zero->name => true,
                            SettingsType::client_page_seans_show_category->name => true,
                            SettingsType::client_page_seans_add_seans->name => true,

                            SettingsType::client_page_appointment->name => true,
                            SettingsType::client_page_appointment_show_services->name => true,
                            SettingsType::client_page_appointment_create->name => ['manuel', 'range', 'multi'],
                            SettingsType::client_page_appointment_show->name => AppointmentStatus::cases(),
                            SettingsType::client_page_appointment_create_once_category->name => true,
                            SettingsType::client_page_appointment_create_branches->name => [1, 2],
                            SettingsType::client_page_appointment_create_appointment_approve->name => true,
                            SettingsType::client_page_appointment_create_appointment_late_payment->name => true,

                            SettingsType::client_page_taksit->name => true,
                            SettingsType::client_page_taksit_pay->name => true,
                            SettingsType::client_page_taksit_show_locked->name => true,
                            SettingsType::client_page_taksit_show_zero->name => true,

                            SettingsType::client_page_offer->name => true,
                            SettingsType::client_page_offer_request->name => true,

                            SettingsType::client_page_coupon->name => true,
                            SettingsType::client_page_referans->name => true,
                            SettingsType::client_page_package->name => true,
                            SettingsType::client_page_earn->name => true,
                            SettingsType::client_page_fatura->name => true,
                            SettingsType::client_page_support->name => true,

                            SettingsType::client_page_shop_include_kdv->name => true,

                            SettingsType::client_payment_types->name => ['havale', 'kk'],
                            SettingsType::payment_taksit_include_kdv->name => 0,
                            SettingsType::payment_taksit_include_komisyon->name => true,

                        ],
                    ]);
                }
            });
        }
    });

    Route::get('/bin', function () {
        $bin = collect((new \App\Managers\PayTRPaymentManager)->bin('54377122', 1));

        $taksit_oran = \App\Models\TaksitOran::where('branch_id', 1)->first();

        dump($taksit_oran['data'][$bin['brand']]);
    });

    Route::get('/oran', function () {
        (new \App\Managers\PayTRPaymentManager)->getTaksitOran(1);
    });

    Route::get('/apikey', function () {

        $apikey = \App\Models\ApiKey::create([
            'branch_id' => 1,
            'merchant_id' => '473481',
            'merchant_key' => encrypt('1GXhbbBFT5Cw492p'),
            'merchant_salt' => encrypt('NHfc2iinP1nekZwz'),
        ]);

        $apikey = \App\Models\ApiKey::create([
            'branch_id' => 2,
            'merchant_id' => '473481',
            'merchant_key' => encrypt('1GXhbbBFT5Cw492p'),
            'merchant_salt' => encrypt('NHfc2iinP1nekZwz'),
        ]);

    });

    Route::get('/ss', function () {});

    Route::get('/tan', function () {
        dump(tenant());
        /*$tenant = App\Tenant::where('id', 'marge')->first();

        $tenant->sms_title = 'MARGE';
        $tenant->sms_count = 100;
        $tenant->save();*/
    });

    Route::get('/ta', function () {
        $branch = \App\Models\Branch::first();
        dump($branch->isOpen(\Carbon\Carbon::createFromFormat('Y-m-d', '2024-11-03')->toDateTime()));
        //dump($branch->startTimeByDay(\Carbon\Carbon::createFromFormat('Y-m-d', '2024-11-03')->toDateTime())->start()->format('H:i'));
        //dump($branch->startTimeByDay(\Carbon\Carbon::createFromFormat('Y-m-d', '2024-11-03')->toDateTime())->end()->format('H:i'));
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
