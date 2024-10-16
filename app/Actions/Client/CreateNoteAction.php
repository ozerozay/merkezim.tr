<?php

namespace App\Actions\Client;

use App\Models\Note;
use App\Traits\StrHelper;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateNoteAction
{
    use AsAction, StrHelper;

    public function handle(array $info)
    {
        $info['message'] = $this->strUpper($info['message']);
        Note::create($info);
    }
}
