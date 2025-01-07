<?php

declare(strict_types=1);

use App\AppointmentStatus;
use App\BranchSMSTemplateType;
use App\Enum\SettingsType;
use App\Http\Controllers\AuthController;
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
use App\Livewire\Web\Profil\CreateAppointmentPage;
use App\Livewire\Web\Profil\EarnPage;
use App\Livewire\Web\Profil\InvitePage;
use App\Livewire\Web\Profil\OfferPage;
use App\Livewire\Web\Profil\ReservationPage;
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

    Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider']);
    Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

    /* Route::any('broadcasting/auth', function () {
         dump('bur');

         return true;
     });*/

    Route::any('/paysuccess', function () {
        echo "<script type='text/javascript'>window.parent.postMessage({ type: 'triggerLivewireEvent', data: '" . request()->get('id') . "', status: 'success', message: 'Ödeme başarıyla alındı.' }, '*');
</script>";
    });
    Route::any('/payerror', function () {
        try {
            $payment = \App\Models\Payment::find(request()->get('id'));
            if ($payment) {
                $payment->status = \App\Enum\PaymentStatus::error;
                $payment->status_message = request()->get('fail_message');
                $payment->save();
            }

            echo "<script type='text/javascript'>window.parent.postMessage({ type: 'triggerLivewireEvent', data: '" . request()->get('id') . "', status: 'error', message: '" . request()->get('fail_message') . "' }, '*');
</script>";
        } catch (\Throwable $e) {
        }
    });
    Volt::route('/login', 'login')->name('login');

    Route::get('/logout', function () {
        \Illuminate\Support\Facades\Auth::logout();
        session()->flush();
        session()->regenerate();

        return redirect()->route('client.index');
    })->name('logout');

    // Route::get('/spotlight2', [Spotlight::class, 'search'])->name('mary.spotlight');

    Volt::route('/', 'client.index')->name('client.index');
    Volt::route('/service', 'client.service')->name('client.service');
    Volt::route('/contact', 'client.contact')->name('client.contact');
    Volt::route('/location', 'client.location')->name('client.location');


    Route::get('/shop/packages', PackagePage::class)->name('client.shop.packages');
    Route::get('/reservation-request', ReservationPage::class)->name('client.profil.reservation-request');

    Route::middleware('auth')->group(function () {

        Route::get('/seans', SeansPage::class)->name('client.profil.seans')->middleware('minify');
        Route::get('/randevu', AppointmentPage::class)->name('client.profil.appointment');
        Route::get('/randevu/create', CreateAppointmentPage::class)->name('client.profil.create-appointment-page');
        Route::get('/taksit', TaksitPage::class)->name('client.profil.taksit');
        Route::get('/teklif', OfferPage::class)->name('client.profil.offer');
        Route::get('/kupon', CouponPage::class)->name('client.profil.coupon');
        Route::get('/davet', InvitePage::class)->name('client.profil.invite');
        Route::get('/kazan', EarnPage::class)->name('client.profil.earn');

        Route::prefix('admin')->group(function () {
            Volt::route('/', 'admin-page')->name('admin.index');

            Route::prefix('kasa')->group(function () {
                Volt::route('/', 'admin.kasa.index')->name('admin.kasa')->middleware('can:page_kasa');
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
        })->middleware('role:admin,staff');
    });

    Route::get('/job', function () {
        // SendReportPdfJob::dispatch();
        // dump(\Namu\WireChat\Models\Message::all());
        // MessageCreated::dispatch(\Namu\WireChat\Models\Message::first());
        dispatch(new \Namu\WireChat\Jobs\BroadcastMessage(\Namu\WireChat\Models\Message::create([
            'conversation_id' => 1,
            'sendable_id ' => 1,
            'sendable_type' => \App\Models\User::class,
            'body' => 'asdasd',
            'type' => 'text',
        ])));

        // broadcast(new MessageCreated(\Namu\WireChat\Models\Message::first()))->toOthers();

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
        // dump(\App\Actions\Appointment\CheckAvailableAppointments::run($info_multiple));

    });

    Route::get('/set', function () {
        Settings::create([
            'data' => [
                'store_name' => 'MARGE GÜZELLİK',
            ],
        ]);
    });

    Route::get('/branch_sms', function () {
        foreach (Branch::all() as $branch) {
            foreach (BranchSMSTemplateType::cases() as $templateType) {
                \App\Models\SmsTemplateBranch::create([
                    'branch_id' => $branch->id,
                    'type' => $templateType->value,
                    'name' => $templateType->turkishName(),
                    'content' => $templateType->label(),
                    'active' => true,
                ]);
            }
        }
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
                            SettingsType::client_page_appointment_cancel_time->name => 0,

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

                            SettingsType::client_page_shop_include_kdv->name => 0,

                            SettingsType::client_payment_types->name => ['havale', 'kk'],

                            SettingsType::payment_taksit_include_kdv->name => 0,
                            SettingsType::payment_taksit_include_komisyon->name => 0,
                            SettingsType::payment_tip_include_kdv->name => 0,
                            SettingsType::payment_tip_include_komisyon->name => 0,
                            SettingsType::payment_offer_include_kdv->name => 0,
                            SettingsType::payment_offer_include_komisyon->name => 0,
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
        // dump($branch->startTimeByDay(\Carbon\Carbon::createFromFormat('Y-m-d', '2024-11-03')->toDateTime())->start()->format('H:i'));
        // dump($branch->startTimeByDay(\Carbon\Carbon::createFromFormat('Y-m-d', '2024-11-03')->toDateTime())->end()->format('H:i'));
    });

    Route::get('/per', function () {
        dd(Auth::user()->getAllPermissions());
    });

    // Route::get('/login', Login::class)->name('login');

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
