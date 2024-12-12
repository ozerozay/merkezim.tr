<?php

namespace App\Actions\Spotlight\Actions\User;

use App\ApproveStatus;
use App\Models\Approve;
use App\Models\User;
use App\Notifications\ApproveRequested;
use App\Peren;
use Lorisleiva\Actions\Concerns\AsAction;
use Mary\Exceptions\ToastException;
use Notification;

class RequestApproveAction
{
    use AsAction;

    /**
     * @throws ToastException
     */
    public function handle($info, $user, $type, $message): void
    {
        Peren::runDatabaseTransaction(function () use ($user, $type, $message, $info) {
            $approve = Approve::create([
                'user_id' => $user,
                'type' => $type,
                'message' => $message,
                'status' => ApproveStatus::waiting,
                'data' => $info,
            ]);

            $users = User::select('id', 'name')->permission('page_approve')->get();
            Notification::send($users, new ApproveRequested($approve));
        });
    }
}
