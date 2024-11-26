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
                ->where('client_id', '=', $clientToken->getParameter('id'))
                ->latest()
                ->get();
            $results = collect();

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
