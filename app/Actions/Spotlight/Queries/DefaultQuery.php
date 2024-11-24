<?php

namespace App\Actions\Spotlight\Queries;

use App\Actions\Spotlight\SpotlightCheckPermission;
use App\Enum\PermissionType;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;

class DefaultQuery
{
    use AsAction;

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::asDefault(function ($query) {
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
                        ->setImage(asset('kahri.png'))
                        ->setAction('jump_to', ['path' => route('admin.client.profil.index', $user->id)])
                        ->setTokens(['client' => $user])
                        ->setIcon('check');
                });
            if (SpotlightCheckPermission::run(PermissionType::action_client_create)) {
                $pages->push(SpotlightResult::make()
                    ->setTitle('Danışan Oluştur')
                    ->setGroup('actions')
                    ->setIcon('plus-circle')
                    ->setAction('dispatch_event',
                        ['name' => 'slide-over.open',
                            'data' => ['component' => 'actions.create-client'],
                        ]));
            }

            return collect()->merge($pages)->merge($users);
        });
    }
}
