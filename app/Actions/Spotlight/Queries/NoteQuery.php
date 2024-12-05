<?php

namespace App\Actions\Spotlight\Queries;

use App\Models\Note;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightQuery;
use WireElements\Pro\Components\Spotlight\SpotlightResult;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class NoteQuery
{
    use AsAction;

    public function handle(): SpotlightQuery
    {
        return SpotlightQuery::forToken('note', function (SpotlightScopeToken $clientToken, SpotlightScopeToken $noteToken, $query) {
            $noteList = Note::query()
                ->select('id', 'client_id', 'message')
                ->where('client_id', $clientToken->getParameter('id'))
                ->latest()
                ->take(5)
                ->get();
            $results = collect();

            $results->push(SpotlightResult::make()
                ->setTitle('Tümünü görüntüle')
                ->setGroup('note')
                ->setIcon('pencil')
                ->setAction('dispatch_event',
                    ['name' => 'slide-over.open',
                        'data' => ['component' => 'modals.client.client-notes-modal',
                            'arguments' => [
                                'client' => $clientToken->getParameter('id')]],
                    ]));

            foreach ($noteList as $note) {
                $results->push(SpotlightResult::make()
                    ->setTitle($note->message)
                    ->setGroup('note')
                    ->setIcon('check-circle')
                );
            }

            return $results;
        });
    }
}
