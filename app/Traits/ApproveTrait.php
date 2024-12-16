<?php

namespace App\Traits;

use App\Actions\Spotlight\Actions\Client\CreateAdisyonAction;
use App\Actions\Spotlight\Actions\Client\CreateAppointmentManuelAction;
use App\Actions\Spotlight\Actions\Client\CreateCouponAction;
use App\Actions\Spotlight\Actions\Client\CreateLabelAction;
use App\Actions\Spotlight\Actions\Client\CreateNoteAction;
use App\Actions\Spotlight\Actions\Client\CreateOfferAction;
use App\Actions\Spotlight\Actions\Client\CreateSaleAction;
use App\Actions\Spotlight\Actions\Client\CreateSaleProductAction;
use App\Actions\Spotlight\Actions\Client\CreateServiceAction;
use App\Actions\Spotlight\Actions\Client\CreateTahsilatAction;
use App\Actions\Spotlight\Actions\Client\CreateTaksitAction;
use App\Actions\Spotlight\Actions\Client\EditClientAction;
use App\Actions\Spotlight\Actions\Create\CreateClientAction;
use App\Actions\Spotlight\Actions\Kasa\CreateMahsupAction;
use App\Actions\Spotlight\Actions\Kasa\CreatePaymentAction;
use App\ApproveStatus;
use App\Enum\PermissionType;
use App\Models\Approve;
use Mary\Traits\Toast;

trait ApproveTrait
{
    use Toast;

    public int|Approve $approve;

    public ?string $message = null;

    public function mount(Approve $approve)
    {
        $this->approve = $approve;
    }

    public function submitApprove()
    {
        if ($this->approve->status == ApproveStatus::approved) {
            $this->error('Daha önceden onaylanmış.');

            return;
        }

        $create_id = null;

        $create_id = match ($this->approve->type) {
            PermissionType::action_client_add_label => CreateLabelAction::run($this->approve->data, true),
            PermissionType::action_adisyon_create => CreateAdisyonAction::run($this->approve->data, true),
            PermissionType::action_client_create => CreateClientAction::run($this->approve->data, true),
            PermissionType::action_client_add_note => CreateNoteAction::run($this->approve->data, true),
            //PermissionType::action_client_use_service => UseServiceAction
            PermissionType::action_client_create_offer => CreateOfferAction::run($this->approve->data, true),
            PermissionType::action_client_create_appointment => CreateAppointmentManuelAction::run($this->approve->data, true),
            PermissionType::action_create_coupon => CreateCouponAction::run($this->approve->data, true),
            PermissionType::action_client_create_taksit => CreateTaksitAction::run($this->approve->data, true),
            PermissionType::action_client_product_sale => CreateSaleProductAction::run($this->approve->data, true),
            PermissionType::action_client_sale => CreateSaleAction::run($this->approve->data, true),
            PermissionType::action_edit_user => EditClientAction::run($this->approve->data, true),
            //PermissionType::action_send_sms => throw new AppException('yapılmadı.'),
            PermissionType::action_client_tahsilat => CreateTahsilatAction::run($this->approve->data, true),
            PermissionType::kasa_mahsup => CreateMahsupAction::run($this->approve->data, true),
            PermissionType::kasa_make_payment => CreatePaymentAction::run($this->approve->data, true),
            PermissionType::action_client_create_service => CreateServiceAction::run($this->approve->data, true),
        };

        if ($create_id != null) {

            $this->approve->update([
                'approved_id' => $create_id,
                'approved_by' => auth()->user()->id,
                'approve_message' => $this->message,
                'status' => ApproveStatus::approved->name,
            ]);

            $this->success('Onaylandı.');
            $this->dispatch('refresh-approves');
        } else {
            $this->error('İşleminiz gerçekleştirilemedi.');
        }
    }

    public function submitReject()
    {
        $this->approve->approved_by = auth()->user()->id;
        $this->approve->approve_message = $this->message;
        $this->approve->status = ApproveStatus::rejected->name;
        $this->approve->save();

        $this->success('İptal edildi.');
        $this->dispatch('refresh-approves');
    }
}
