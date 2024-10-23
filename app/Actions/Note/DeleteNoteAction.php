<?php

namespace App\Actions\Note;

use App\Actions\User\CheckAuthUserPermissionAction;
use App\Models\Note;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class DeleteNoteAction
{
    use AsAction;

    public function handle($id)
    {
        try {

            CheckAuthUserPermissionAction::run('note_delete');
            Note::where('id', $id)->delete();

        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
