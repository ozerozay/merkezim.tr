<?php

namespace App\Actions\Spotlight\Actions\Client\Commands;

use App\Models\Note;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;

class DeleteClientNoteAction
{
    use AsAction;

    public function handle($info)
    {
        Peren::runDatabaseTransactionApprove($info, function () use ($info) {
            Note::where('id', $info['id'])->delete();
        });
    }
}
