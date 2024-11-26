<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\Note;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class NoteToken
{
    use AsAction;

    public function handle(): SpotlightScopeToken
    {
        return SpotlightScopeToken::make('note', function (SpotlightScopeToken $token, Note $note) {
            $note->refresh();
            $token->setParameters(['client' => $note->client_id]);
            $token->setParameters(['id' => $note->id]);
            $token->setText('Notlar');
        });
    }
}
