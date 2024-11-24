<?php

namespace App\Actions\Spotlight\Tokens;

use App\Models\Note;
use Lorisleiva\Actions\Concerns\AsAction;
use WireElements\Pro\Components\Spotlight\SpotlightScopeToken;

class NoteToken
{
    use AsAction;

    public function handle()
    {
        return SpotlightScopeToken::make('note', function (SpotlightScopeToken $token, Note $note) {
            $token->setParameters(['id' => $note->id]);
            $token->setText('Notlar');
        });
    }
}
