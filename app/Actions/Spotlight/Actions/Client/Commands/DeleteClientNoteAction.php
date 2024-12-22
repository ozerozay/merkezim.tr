<?php

namespace App\Actions\Spotlight\Actions\Client\Commands;

use App\Models\Note;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteClientNoteAction
{
    use AsAction;

    public function handle($info)
    {
        Note::where('id', $info)->delete();
    }
}
