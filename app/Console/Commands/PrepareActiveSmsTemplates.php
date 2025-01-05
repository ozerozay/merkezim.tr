<?php

namespace App\Console\Commands;

use App\BranchSMSTemplateType;
use App\Models\Branch;
use App\Models\User;
use App\Tenant;
use Illuminate\Console\Command;

class PrepareActiveSmsTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:prepare';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aktif şubeler için SMS şablonlarını hazırlar.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            $tenants = Tenant::all();

            foreach ($tenants as $tenant) {
                tenancy()->initialize($tenant);

                $branchesWithActiveTemplates = Branch::with(['smsTemplates' => function ($query) {
                    $query->where('active', true);
                }])->where('active', true)->get();

                foreach ($branchesWithActiveTemplates as $branch) {
                    foreach ($branch->smsTemplates as $template) {

                        $templateType = BranchSMSTemplateType::from($template->type);

                        switch ($templateType) {
                            case BranchSMSTemplateType::appointment_one_day_before:

                                $clients = User::query()
                                    ->where('branch_id', $branch->id)
                                    ->whereHas('clientAppointments', function ($query) {
                                        $query->whereDate('date', now()->addDay()->toDateString());
                                    })->get();

                                foreach ($clients as $customer) {
                                    $this->processCustomer($customer, $template);
                                }
                                break;
                            case BranchSMSTemplateType::appointment_on_day:
                                break;
                            case BranchSMSTemplateType::appointment_two_days_before:
                                throw new \Exception('To be implemented');
                                break;
                            case BranchSMSTemplateType::appointment_five_days_before:
                                throw new \Exception('To be implemented1');
                                break;
                            case BranchSMSTemplateType::appointment_completed:
                                throw new \Exception('To be implemen2ted');
                                break;
                            case BranchSMSTemplateType::offer_five_days_before:
                                throw new \Exception('To be implemen3ted');
                                break;
                            case BranchSMSTemplateType::offer_two_days_before:
                                throw new \Exception('To be implem4ented');
                                break;
                            case BranchSMSTemplateType::offer_due_today:
                                throw new \Exception('To be imple5mented');
                                break;
                            case BranchSMSTemplateType::payment_five_days_before:
                                throw new \Exception('To be implem6ented');
                                break;
                            case BranchSMSTemplateType::payment_two_days_before:
                                throw new \Exception('To be implemen7ted');
                                break;
                            case BranchSMSTemplateType::payment_due_today:
                                throw new \Exception('To be impleme12nted');
                                break;
                            case BranchSMSTemplateType::payment_two_days_after:
                                throw new \Exception('To be imple12mented');
                                break;
                            case BranchSMSTemplateType::payment_five_days_after:
                                throw new \Exception('To be imp33lemented');
                                break;
                            case BranchSMSTemplateType::payment_seven_days_after:
                                throw new \Exception('To be 2');
                                break;
                            case BranchSMSTemplateType::payment_fifteen_days_after:
                                throw new \Exception('To be 44');
                                break;
                            case BranchSMSTemplateType::payment_twenty_days_after:
                                throw new \Exception('To be 1');
                                break;
                            case BranchSMSTemplateType::welcome_message:
                                throw new \Exception('To be 2');
                        }

                    }
                }

                tenancy()->end();
            }

        } catch (\Throwable $e) {

        }
    }

    protected function processCustomer($customer, $templateBranch)
    {
        // BranchSMSTemplateType Enum'undan ilgili türü al
        $templateType = BranchSMSTemplateType::from($templateBranch->type);

        // Mesajı hazırla
        $message = $templateType->prepareMessage($templateBranch, [
            'müşteri adı' => $customer->name,
            'randevu saati' => $customer->appointments->first()->time ?? 'Bilinmiyor',
        ]);

        // SMS Kuyruğa ekleme işlemi
        $this->queueSms($customer->phone, $message);

        $this->info("Müşteri: {$customer->name}, Mesaj: {$message}");
    }
}
