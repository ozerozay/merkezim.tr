<?php

namespace App\Providers;

use App\Actions\Spotlight\Actions\Settings\GetAllSettingsAction;
use App\Actions\Spotlight\Actions\Settings\GetSettingsAction;
use App\Actions\Spotlight\RegisterSpotlights;
use App\Livewire\Actions\Note\GetClientNotesAction;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use WireElements\Pro\Components\Spotlight\Spotlight;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        RegisterSpotlights::run();
        /*if ($this->app->environment('local')) {

        }*/
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        app()->singleton('settings', function () {
            return GetAllSettingsAction::run();
        });

        \Gate::define('viewPulse', function (?\App\Models\User $user) {
            return true;
        });

        \Laravel\Pulse\Facades\Pulse::user(fn ($user) => [
            'name' => '$user->name',
            'extra' => '$user->email',
            'avatar' => '$user->avatar_url',
        ]);
        //Spotlight::registerAction('show_notes', GetClientNotesAction::class);
        //Spotlight::registerAction('get_client_notes_action', GetClientNotesAction::class);

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
            'agenda_type' => 'App\AgendaType',
            'agenda_status' => 'App\AgendaStatus',
            'client_timeline' => 'App\Models\ClientTimeline',
            'talep' => 'App\Models\Talep',
            'talep_process' => 'App\Models\TalepProcess',
            'il' => 'App\Models\Il',
            'ilce' => 'App\Models\Ilce',
            'role' => 'Spatie\Permission\Models\Role',
            'sms_template' => 'App\Models\SMSTemplate',
            'cart_item' => 'App\Models\CartItem',
            'cart' => 'App\Models\Cart',
        ]);

        Blade::directive('price', function ($price) {
            return "<?php echo \Illuminate\Support\Number::currency((float) $price, in: 'TRY', locale: 'tr'); ?>";
        });

        Blade::directive('ayar', function ($expression) {
            dump($expression);

            $setting_name = str_replace('"', '', trim($expression));

            $get_setting = (bool) GetSettingsAction::run(eval(" return $setting_name;"), auth()->user()->branch_id);

            return "<?php if ($get_setting): ?>";
        });

        Blade::directive('endayar', function () {
            // Bitiş için @endif döndür
            return '<?php endif; ?>';
        });
    }
}
