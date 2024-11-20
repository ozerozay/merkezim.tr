<?php

namespace App\Providers;

use App\Models\Note;
use App\Models\User;
use App\Policies\NotePolicy;
use Gate;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use WireElements\Pro\Components\Spotlight\Spotlight;
use WireElements\Pro\Components\Spotlight\SpotlightMode;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScope;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Spotlight::setup(function () {
            $this->registerSpotlightGroups();
            $this->registerSpotlightModes();
            $this->registerSpotlightRandomTips();
            $this->registerSpotlightTokens();
            $this->registerSpotlightScopes();
            $this->registerSpotlightQueries();
        });
        if ($this->app->environment('local')) {

        }
    }

    private function registerSpotlightGroups(): void
    {
        Spotlight::registerGroup('pages', 'Sayfalar');
        Spotlight::registerGroup('clients', 'Danışanlar');
    }

    private function registerSpotlightModes(): void
    {
        Spotlight::registerModes(
            SpotlightMode::make('help', 'Tüm komutlar ve yardım')
                ->setCharacter('?'),
            SpotlightMode::make('search_issues', 'Ara')
                ->setCharacter('#'),
            SpotlightMode::make('global_commands', 'Komutlar')
                ->setCharacter('>'),
        );
    }

    private function registerSpotlightRandomTips(): void
    {
        Spotlight::registerTips(
            'Merkezim',
            'Merkezim yazı',
            'Merkezim flan filan açıklama',
        );
    }

    private function registerSpotlightTokens(): void
    {
        Spotlight::registerTokens(
            SpotlightScopeToken::make('client', function (SpotlightScopeToken $token, User $client): void {
                //$client->refresh();
                $token->setParameters(['id' => $client->id]);
                $token->setText($client->name.' - '.$client->client_branch->name);
            }),
        );
    }

    private function registerSpotlightScopes(): void
    {
        Spotlight::registerScopes(
            SpotlightScope::forRoute('admin.client.profil.index', function (SpotlightScope $scope, Request $request) {
                $scope->applyToken('client', $request->user);
            })
        );
    }

    private function registerSpotlightQueries(): void
    {
        Spotlight::registerQueries(
            SpotlightQuery::asDefault(function ($query) {
                $pages = collect([
                    SpotlightResult::make()
                        ->setTitle('Anasayfa')
                        ->setGroup('pages')
                        ->setAction('jump_to', ['path' => route('admin.index')])
                        ->setIcon('home'),
                ])->when(! blank($query), function ($collection) use ($query) {
                    return $collection->where(fn (SpotlightResult $result) => str($result->title())->lower()->contains(str($query)->lower()));
                });

                $users = User::query()
                    ->where('name', 'like', "%{$query}%")
                    ->take(5)
                    ->get()
                    ->map(function (User $user) {
                        return SpotlightResult::make()
                            ->setTitle($user->name.' - '.$user->client_branch->name)
                            ->setGroup('clients')
                            ->setAction('jump_to', ['path' => route('admin.client.profil.index', $user->id)])
                            ->setTokens(['client' => $user])
                            ->setIcon('check');
                    });

                return collect()->merge($pages)->merge($users);
            }),
            SpotlightQuery::forToken('client', function ($query, SpotlightScopeToken $clientToken) {
                $pages = collect([
                    SpotlightResult::make()
                        ->setTitle('Falan filan yap')
                        ->setGroup('pages')
                        ->setIcon('arrow-path-rounded-square'),
                ])->when(! blank($query), function ($collection) use ($query) {
                    return $collection->where(fn (SpotlightResult $result
                    ) => str($result->title())->lower()->contains(str($query)->lower()));
                });

                return collect()->merge($pages);
            }),
            SpotlightQuery::forMode('help', function ($query) {
                return collect([
                    SpotlightResult::make()
                        ->setTitle('Search for issues')
                        ->setTypeahead('Search mode')
                        ->setAction('replace_query', ['query' => '#', 'description' => 'Start command'])
                        ->setIcon('tag'),
                    SpotlightResult::make()
                        ->setTitle('Activate command mode')
                        ->setTypeahead('Command mode')
                        ->setAction('replace_query', ['query' => '>', 'description' => 'Start command'])
                        ->setIcon('command-line'),
                ])->when(! blank($query), function ($collection) use ($query) {
                    return $collection->where(fn (SpotlightResult $result
                    ) => str($result->title())->lower()->contains(str($query)->lower()));
                });
            }),
            SpotlightQuery::forMode('global_commands', function ($query) {
                return collect([
                    SpotlightResult::make()
                        ->setTitle('Visit Wire Elements')
                        ->setAction('jump_to', ['path' => 'https://wire-elements.dev'])
                        ->setIcon('link'),
                    SpotlightResult::make()
                        ->setTitle('Visit Livewire')
                        ->setAction('jump_to', ['path' => 'https://laravel-livewire.com'])
                        ->setIcon('link'),
                    SpotlightResult::make()
                        ->setTitle('Visit Laravel')
                        ->setAction('jump_to', ['path' => 'https://laravel.com'])
                        ->setIcon('link'),
                ])->when(! blank($query), function ($collection) use ($query) {
                    return $collection->where(fn (SpotlightResult $result
                    ) => str($result->title())->lower()->contains(str($query)->lower()));
                });
            }),
        );
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
            'agenda_type' => 'App\AgendaType',
            'agenda_status' => 'App\AgendaStatus',
            'client_timeline' => 'App\Models\ClientTimeline',
            'talep' => 'App\Models\Talep',
            'talep_process' => 'App\Models\TalepProcess',
        ]);

        Blade::directive('price', function ($price) {
            return "<?php echo \Illuminate\Support\Number::currency((float) $price, in: 'TRY', locale: 'tr'); ?>";
        });
    }
}
