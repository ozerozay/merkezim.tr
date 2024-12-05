<?php

namespace App\Actions\Spotlight\Actions\Client\Get;

use App\Exceptions\AppException;
use App\Models\Note;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;

class GetClientNotesAction
{
    use AsAction;

    public function handle($client, $paginate)
    {
        try {

            $query = Note::where('client_id', $client)->latest()->with('user:id,name');

            return $paginate ? $query->paginate(10) : $query->get();

        } catch (AppException $e) {
            throw ToastException::error($e);
        } catch (\Throwable $e) {
            throw ToastException::error('İşlem tamamlanamadı.'.$e->getMessage());
        }
    }
}
