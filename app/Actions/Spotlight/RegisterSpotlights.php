<?php

namespace App\Actions\Spotlight;

use App\Actions\Spotlight\Queries\AppointmentQuery;
use App\Actions\Spotlight\Queries\ClientQuery;
use App\Actions\Spotlight\Queries\CreateAppointmentQuery;
use App\Actions\Spotlight\Queries\DefaultQuery;
use App\Actions\Spotlight\Tokens\AppointmentToken;
use App\Actions\Spotlight\Tokens\ClientToken;
use App\Actions\Spotlight\Tokens\CreateAppointmentToken;
use App\Actions\Spotlight\Tokens\NoteToken;
use App\Models\Note;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\Spotlight;
use WireElements\Pro\Components\Spotlight\SpotlightMode;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScope;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class RegisterSpotlights
{
    use AsAction;

    public function handle(): void
    {
        Spotlight::setup(function () {
            $this->registerSpotlightGroups();
            $this->registerSpotlightModes();
            $this->registerSpotlightRandomTips();
            $this->registerSpotlightTokens();
            $this->registerSpotlightScopes();
            $this->registerSpotlightQueries();
        });
    }

    private function registerSpotlightGroups(): void
    {
        Spotlight::registerGroup('pages', 'Sayfalar');
        Spotlight::registerGroup('clients', 'Danışanlar');
        Spotlight::registerGroup('client_notes', 'Notlar');
        Spotlight::registerGroup('note', 'Notlar');

        /* Randevu İşlemleri */
        Spotlight::registerGroup('appointment_actions', 'İşlemler');
        Spotlight::registerGroup('appointments_active', 'Aktif');
        Spotlight::registerGroup('appointments_finish', 'Bitti');
        Spotlight::registerGroup('appointments_cancel', 'İptal');

        /* Randevu Oluştur */
        Spotlight::registerGroup('appointment_create', 'Kategori');

        /* İşlemler */
        Spotlight::registerGroup('actions', 'İşlemler');

        /* Client Actions */
        Spotlight::registerGroup('client_actions_pages', 'Sayfalar');
        Spotlight::registerGroup('client_actions_edit', 'İşlemler');
        Spotlight::registerGroup('client_actions_service', 'Hizmet');
        Spotlight::registerGroup('client_actions_sale', 'Satış');
        Spotlight::registerGroup('client_actions_new', 'İşlemler');
        Spotlight::registerGroup('client_actions_contact', 'Oluştur');

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
            ClientToken::run(),
            NoteToken::run(),
            AppointmentToken::run(),
            CreateAppointmentToken::run()
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
            DefaultQuery::run(),
            ClientQuery::run(),
            AppointmentQuery::run(),
            CreateAppointmentQuery::run(),
            SpotlightQuery::forToken('note', function ($query, SpotlightScopeToken $noteToken) {
                $notes = Note::query()
                    ->get()
                    ->map(function (Note $note) {
                        return SpotlightResult::make()
                            ->setTitle($note->message)
                            ->setGroup('client_notes')
                            ->setAction('get_client_notes_action', ['client' => $note->client_id])
                            ->setIcon('check');
                    });

                return collect()->merge($notes);
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
}
