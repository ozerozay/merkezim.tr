<?php

namespace App\Console\Commands;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\AppointmentStatus;
use App\AppointmentType;
use App\Models\Appointment;
use App\Models\BEST\Hizmet;
use App\Models\BEST\HizmetOda;
use App\Models\BEST\Musteri;
use App\Models\BEST\SatisTipi;
use App\Models\BEST\User;
use App\Models\Note;
use App\Models\Sale;
use App\Models\SaleType;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceRoom;
use App\SaleStatus;
use App\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as CommandAlias;

class DataTransferClientCommand extends Command
{
    protected $signature = 'tenant:client-transfer';

    protected $description = 'Eski veritabanından müşterileri ve ilişkili verileri yeni veritabanına aktarır.';

    public function handle()
    {
        try {
            $tenantId = $this->selectTenant();
            $tenant = Tenant::find($tenantId);

            if (! $tenant) {
                $this->error("Tenant bulunamadı: $tenantId");

                return CommandAlias::FAILURE;
            }

            $this->info("Tenant seçildi: $tenantId");
            if (! $this->confirm('Veri aktarımı başlatılacak. Devam etmek istiyor musunuz?')) {
                $this->info('İşlem iptal edildi.');

                return CommandAlias::SUCCESS;
            }

            $this->info('✅ Veri aktarımı başlatılıyor...');
            $this->transferData($tenant);

            $this->info("✅ Veri aktarımı tamamlandı: $tenantId");

            return CommandAlias::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Bir hata oluştu: '.$e->getMessage());

            return CommandAlias::FAILURE;
        }
    }

    private function selectTenant()
    {
        $tenants = Tenant::all(['id'])->pluck('id')->toArray();

        if (empty($tenants)) {
            throw new \Exception('Kayıtlı bir tenant bulunamadı.');
        }

        return $this->choice('Hangi tenant üzerinde işlem yapmak istiyorsunuz?', $tenants);
    }

    private function transferData($tenant)
    {
        $tenant->run(function () {
            Musteri::query()
                ->whereIn('sube', [1, 2])
                ->whereNull('aktar')
                ->orderBy('id')
                ->with(['Satislari.Hizmetleri', 'Satislari.Senetleri', 'Randevulari', 'Notlari'])
                ->chunk(250, function ($customers) {
                    foreach ($customers as $customer) {
                        DB::beginTransaction();
                        $old_new_customer_service_ids = [];
                        try {
                            $client = \App\Models\User::firstOrCreate(
                                ['phone' => $customer->tel],
                                [
                                    'branch_id' => $customer->sube,
                                    'country' => '90',
                                    'phone_code' => '1111',
                                    'name' => $customer->ad,
                                    'tckimlik' => $customer->tckimlik,
                                    'adres' => $customer->adres,
                                    'unique_id' => CreateUniqueID::run('user'),
                                    'gender' => true,
                                    'first_login' => false,
                                    'birth_date' => $customer->dtarih,
                                    'instant_approve' => false,
                                    'active' => true,
                                    'can_login' => true,
                                ]
                            );

                            Note::withoutTimestamps(function () use ($customer, $client) {
                                foreach ($customer->Notlari as $customer_note) {
                                    Note::create([
                                        'user_id' => $this->getNewStaffId($customer_note->kullanici),
                                        'client_id' => $client->id,
                                        'message' => $customer_note->aciklama,
                                        'created_at' => $customer_note->created_at,
                                        'updated_at' => $customer_note->updated_at,
                                    ]);
                                }
                            });

                            foreach ($customer->Satislari as $mSatis) {
                                $customer_sale = Sale::create([
                                    'branch_id' => $client->branch_id,
                                    'unique_id' => CreateUniqueID::run('sale'),
                                    'client_id' => $client->id,
                                    'user_id' => $this->getNewStaffId($mSatis->kullanici),
                                    'sale_type_id' => $this->getNewSaleTypeId($mSatis->tip),
                                    'date' => $mSatis->tarih,
                                    'status' => $mSatis->durum == 'Aktif' ? SaleStatus::success : SaleStatus::cancel,
                                    'price' => $mSatis->tutar,
                                    'price_real' => $mSatis->indirimsiz_tutar,
                                    'staffs' => $this->getNewStaffIds($mSatis->personel),
                                    'sale_no' => $mSatis->numara,
                                    'message' => 'AKTARIM',
                                    'visible' => true,
                                ]);

                                foreach ($mSatis->Senetleri as $senet) {
                                    $customer_sale->clientTaksits()->create([
                                        'client_id' => $client->id,
                                        'branch_id' => $client->branch_id,
                                        'sale_id' => $customer_sale->id,
                                        'total' => $senet->tutar,
                                        'remaining' => $senet->kalan,
                                        'date' => $senet->tarih,
                                        'status' => $mSatis->durum == 'Aktif' ? SaleStatus::success : SaleStatus::cancel,
                                    ]);
                                }

                                foreach ($mSatis->Hizmetleri as $hizmet) {
                                    $customer_service = $customer_sale->clientServices()->create([
                                        'client_id' => $client->id,
                                        'branch_id' => $client->branch_id,
                                        'service_id' => $this->getNewServiceId($hizmet->id),
                                        'total' => $hizmet->toplam_seans,
                                        'remaining' => $hizmet->kalan_seans,
                                        'gift' => $hizmet->cesit == 'HEDİYE',
                                        'message' => 'AKTARIM',
                                        'user_id' => $customer_sale->user_id,
                                        'status' => $mSatis->durum == 'Aktif' ? SaleStatus::success : SaleStatus::cancel,
                                    ]);
                                    $old_new_customer_service_ids[$hizmet->id] = $customer_service->id;
                                }
                            }

                            Appointment::withoutTimestamps(function () use ($customer, $client, $old_new_customer_service_ids) {
                                foreach ($customer->Randevulari as $customer_appointment) {
                                    $client->clientAppointments()->create([
                                        'branch_id' => $client->branch_id,
                                        'service_room_id' => $this->getNewRoomId($customer_appointment->oda),
                                        'service_category_id' => ServiceCategory::first()->id,
                                        'service_ids' => $this->getNewClientServiceIds($old_new_customer_service_ids, $customer_appointment->hizmetler),
                                        'date' => $customer_appointment->tarih,
                                        'duration' => $customer_appointment->sure,
                                        'date_start' => $customer_appointment->baslangic,
                                        'date_end' => $customer_appointment->bitis,
                                        'finish_service_ids' => $customer_appointment->durum == 'Onaylandı'
                                            ? $this->getNewClientServiceIds($old_new_customer_service_ids, $customer_appointment->hizmetler)
                                            : null,
                                        'finish_user_id' => isset($customer_appointment->detay['onay_personel']) && ! is_null($customer_appointment->detay['onay_personel'])
                                            ? $this->getNewStaffId($customer_appointment->detay['onay_personel'])
                                            : 1,
                                        'status' => $this->getNewAppointmentStatus($customer_appointment->durum),
                                        'message' => 'AKTARIM',
                                        'type' => AppointmentType::appointment->name,
                                    ]);
                                }
                            });

                            $customer->update(['aktar' => 1]);

                            DB::commit();

                            $this->info("Müşteri aktarıldı: {$client->name}");
                        } catch (\Exception $e) {
                            DB::rollBack();
                            $this->error("❌ Hata: {$e->getMessage()} {$e->getLine()} (Müşteri ID: {$customer->ad})");
                        }
                    }
                });
        });
    }

    private function getNewStaffId($old_id)
    {
        $old_user = User::where('id', $old_id)->first();
        if ($old_user) {
            $new_user = \App\Models\User::where('phone', $old_user->telefon)->first();
            if ($new_user) {
                return $new_user->id;
            }
        }
        //dump('kullanıcı 1 döndü', $old_id);

        return 1;
    }

    private function getNewStaffIds(array $old_ids)
    {
        return array_map(function ($old_id) {
            $old_user = User::where('id', $old_id)->first();
            if ($old_user) {
                $new_user = \App\Models\User::where('phone', $old_user->telefon)->first();

                return $new_user ? $new_user->id : 1;
            }

            return 1;
        }, $old_ids);
    }

    private function getNewSaleTypeId($old_id)
    {
        $old_sale_type = SatisTipi::where('id', $old_id)->first();
        if ($old_sale_type) {
            $new_sale_type = SaleType::where('name', $old_sale_type->name)->first();
            if ($new_sale_type) {
                return $new_sale_type->id;
            }
        }
        //dump('sale_type 1 döndü', $old_id);

        return 1;
    }

    private function getNewServiceId($old_id)
    {
        $old_service = Hizmet::where('id', $old_id)->first();
        if ($old_service) {
            $new_service = Service::where('name', $old_service->name)->first();
            if ($new_service) {
                return $new_service->id;
            }
        }
        //dump('hizmet 1 döndü', $old_id);

        return 1;
    }

    private function getNewRoomId($old_id)
    {
        $old_room = HizmetOda::where('id', $old_id)->first();
        if ($old_room) {
            $new_room = ServiceRoom::where('name', $old_room->ad)->where('branch_id', $old_room->sube)->first();
            if ($new_room) {
                return $new_room->id;
            }
        }
        //dump('room 1 döndü', $old_id);

        return 1;
    }

    private function getNewAppointmentStatus($old_status): string
    {
        return match ($old_status) {
            'Gelmedi' => AppointmentStatus::late->name,
            'İptal' => AppointmentStatus::cancel->name,
            'Onaylandı' => AppointmentStatus::finish->name,
            'Teyitli' => AppointmentStatus::teyitli->name,
            'Geldi' => AppointmentStatus::merkez->name,
            default => AppointmentStatus::waiting->name,

        };
    }

    private function getNewClientServiceIds($list, $old_ids)
    {
        return collect($list)->only($old_ids)->values()->toArray();
    }
}
