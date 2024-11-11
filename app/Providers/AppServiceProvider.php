<?php

namespace App\Providers;

use App\Models\Note;
use App\Policies\NotePolicy;
use Gate;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {

        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Gate::policy(Note::class, NotePolicy::class);

        Relation::enforceMorphMap([
            'service' => 'App\Models\Service',
            'user' => 'App\Models\User',
            'coupon' => 'App\Models\Coupon',
            'category' => 'App\Models\ServiceCategory',
            'branch' => 'App\Models\Branch',
            'kasa' => 'App\Models\Kasa',
            'note' => 'App\Models\Note',
            'offer' => 'App\Models\Offer',
            'offerItem' => 'App\Models\OfferItem',
            'packageItem' => 'App\Models\PackageItem',
            'permission' => 'App\Models\Permission',
            'package' => 'App\Models\Package',
            'product' => 'App\Models\Product',
            'room' => 'App\Models\ServiceRoom',
            'sale_type' => 'App\Models\SaleType',
            'sale' => 'App\Models\Sale',
            'client_service' => 'App\Models\ClientService',
            'client_taksit' => 'App\Models\ClientTaksit',
            'transaction' => 'App\Models\Transaction',
            'masraf' => 'App\Models\Masraf',
            'prim' => 'App\Models\Prim',
            'staff_muhasebe' => 'App\Models\StaffMuhasebe',
            'client_service_use' => 'App\Models\ClientServiceUse',
            'label' => 'App\Models\Label',
            'approve' => 'App\Models\Approve',
            'adisyon' => 'App\Models\Adisyon',
            'adisyon_service' => 'App\Models\AdisyonService',
            'sale_product' => 'App\Models\SaleProduct',
            'sale_product_item' => 'App\Models\SaleProductItem',
            'mahsup' => 'App\Models\Mahsup',
            'client_taksit_lock' => 'App\Models\ClientTaksitsLock',
            'appointment' => 'App\Models\Appointment',
            'appointment_statuses' => 'App\Models\AppointmentStatuses',
            'talep_status' => 'App\Models\TalepStatus',
            'agenda' => 'App\Models\Agenda',
            'agenda_occurrence' => 'App\Models\AgendaOccurrence',
        ]);

        Blade::directive('price', function ($price) {
            return "<?php echo \Illuminate\Support\Number::currency((float) $price, in: 'TRY', locale: 'tr'); ?>";
        });
    }
}
