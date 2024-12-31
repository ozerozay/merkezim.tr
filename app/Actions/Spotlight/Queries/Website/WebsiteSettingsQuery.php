<?php

namespace App\Actions\Spotlight\Queries\Website;

use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class WebsiteSettingsQuery
{
    use AsAction;

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::forToken('websitesettings', function (SpotlightScopeToken $websitesettingsToken, $query) {

            $results = collect();
            $results->push(
                SpotlightResult::make()
                    ->setTitle('Geri Dön')
                    ->setGroup('backk')
                    ->setIcon('arrow-left')
                    ->setAction('return_action'),
            );

            /*$results->push(SpotlightResult::make()
                ->setTitle('Dil Desteği')
                ->setGroup('definations')
                ->setIcon('language'));*/

            $results->push(SpotlightResult::make()
                ->setTitle('Sayfalar')
                ->setGroup('definations')
                ->setIcon('photo'));

            $results->push(SpotlightResult::make()
                ->setTitle('Anasayfa')
                ->setGroup('definations')
                ->setIcon('photo'));

            $results->push(SpotlightResult::make()
                ->setTitle('Online İşlem Merkezi')
                ->setGroup('definations')
                ->setIcon('hand-thumb-up')
                ->setAction('dispatch_event',
                    ['name' => 'slide-over.open',
                        'data' => ['component' => 'settings.web.online-settings'],
                    ]));

            $results->push(SpotlightResult::make()
                ->setTitle('Blog')
                ->setGroup('definations')
                ->setIcon('document-text'));

            $results->push(SpotlightResult::make()
                ->setTitle('Hizmetler')
                ->setGroup('definations')
                ->setIcon('queue-list'));

            $results->push(SpotlightResult::make()
                ->setTitle('Resim Galerisi')
                ->setGroup('definations')
                ->setIcon('photo'));

            $results->push(SpotlightResult::make()
                ->setTitle('Telefon ve Sosyal Medya')
                ->setGroup('definations')
                ->setIcon('phone'));

            $results->push(SpotlightResult::make()
                ->setTitle('Bize Ulaşın')
                ->setGroup('definations')
                ->setIcon('map'));

            $results = $results->sortBy(function (SpotlightResult $result, int $key) {
                return $result->title();
            });

            $results = $results->when(! blank($query), function ($collection) use ($query) {
                return $collection->where(fn (SpotlightResult $result) => str($result->title())->lower()->contains(str($query)->lower()));
            });

            return $results;
        });
    }
}
