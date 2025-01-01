<?php

namespace App\Console\Commands;

use App\Actions\Spotlight\Actions\Create\CreateUniqueID;
use App\Models\BEST\Hizmet;
use App\Models\BEST\HizmetOda;
use App\Models\BEST\Kasa;
use App\Models\BEST\SatisTipi;
use App\Models\BEST\Urun;
use App\Models\BEST\User;
use App\Models\Branch;
use App\Models\Product;
use App\Models\SaleType;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceRoom;
use App\Tenant;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class DataTransferCommand extends Command
{
    /**
     * Komutun imzası.
     */
    protected $signature = 'tenant:data-transfer';

    /**
     * Komut açıklaması.
     */
    protected $description = 'Belirli bir tenant için büyük miktarda veri aktarımı yapar';

    /**
     * İşleme başlama.
     */
    public function handle(): int
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

            $tenant = Tenant::find($tenantId);
            $tenant->run(function () {
                \DB::beginTransaction();

                $this->createCategories();
                $this->createServices();
                $this->createRooms();
                $this->getKasas();
                $this->getStaff();
                $this->getProducts();
                $this->getSaleTypes();

                \DB::commit();
            });

            //$this->transferData($tenant);

            $this->info("✅ Veri aktarımı tamamlandı: $tenantId");

            return CommandAlias::SUCCESS;

        } catch (\Exception $e) {
            \DB::rollBack();
            $this->error('❌ Bir hata oluştu: '.$e->getMessage());

            return CommandAlias::FAILURE;
        }
    }

    /**
     * Tenant seçimi için liste.
     */
    private function selectTenant()
    {
        $tenants = Tenant::all(['id'])->pluck('id')->toArray();

        if (empty($tenants)) {
            throw new \Exception('Kayıtlı bir tenant bulunamadı.');
        }

        return $this->choice('Hangi tenant üzerinde işlem yapmak istiyorsunuz?', $tenants);
    }

    private function createCategories(): void
    {
        $best_service_categories = Hizmet::where('aktif', true)->groupBy('cesit')->pluck('cesit')->toArray();

        foreach ($best_service_categories as $category) {
            if (! ServiceCategory::where('name', $category)->exists()) {
                $category = ServiceCategory::firstOrCreate([
                    'branch_ids' => Branch::pluck('id')->toArray(),
                    'price' => 0,
                    'name' => $category,
                ]);
            }
        }
    }

    private function createServices(): void
    {
        $best_services = Hizmet::where('aktif', true)->get();

        foreach ($best_services as $service) {
            if (! Service::where('name', $service->name)->exists()) {
                Service::create([
                    'category_id' => ServiceCategory::where('name', $service->cesit)->first()->id,
                    'name' => $service->ad,
                    'gender' => 0,
                    'seans' => 1,
                    'duration' => $service->sure,
                    'price' => $service->fiyat,
                    'min_day' => 0,
                ]);
            }
        }
    }

    private function createRooms(): void
    {
        $best_rooms = HizmetOda::where('aktif', true)->whereIn('sube', [1, 2])->get();

        foreach ($best_rooms as $room) {
            if (! ServiceRoom::where('name', $room->name)->where('branch_id', $room->sube)->exists()) {
                ServiceRoom::create([
                    'branch_id' => $room->sube,
                    'name' => $room->ad,
                    'category_ids' => ServiceCategory::pluck('id')->toArray(),
                ]);
            }
        }
    }

    private function getKasas(): void
    {

        $best_kasas = Kasa::whereIn('sube', [1, 2])->get();

        foreach ($best_kasas as $kasa) {
            if (! \App\Models\Kasa::where('name', $kasa->ad)->where('branch_id', $kasa->sube)->exists()) {
                \App\Models\Kasa::create([
                    'branch_id' => $kasa->sube,
                    'name' => $kasa->ad,
                    'active' => $kasa->aktif,
                ]);
            }
        }
    }

    private function getStaff(): void
    {

        $best_users = User::all();
        foreach ($best_users as $user) {
            if (! empty(array_intersect($user->sube, [1, 2]))) {
                if (! \App\Models\User::where('phone', $user->telefon)->exists()) {
                    $user = \App\Models\User::create([
                        'branch_id' => Branch::first()->id,
                        'phone' => $user->telefon,
                        'country' => '90',
                        'phone_code' => '1111',
                        'name' => $user->name,
                        //'tckimlik' => '34222447480',
                        //'adres' => 'asdasda',
                        'unique_id' => CreateUniqueID::run('user'),
                        'gender' => true,
                        'first_login' => false,
                        'birth_date' => date('Y-m-d'),
                        'staff_branches' => $user->sube,
                        'instant_approve' => false,
                        'active' => $user->aktif,
                        'can_login' => $user->aktif,
                    ]);

                    $user->assignRole('staff');
                }
            }
        }

    }

    private function getProducts(): void
    {

        $best_products = Urun::whereIn('sube', [1, 2])->get();

        foreach ($best_products as $product) {
            if (! \App\Models\Product::where('name', $product->ad)->where('branch_id', $product->sube)->exists()) {
                Product::create([
                    'branch_id' => $product->sube,
                    'name' => $product->ad,
                    'price' => $product->fiyat,
                    'stok' => $product->stok,
                    'active' => $product->aktif,
                ]);
            }
        }
    }

    private function getSaleTypes(): void
    {

        $sale_types = SatisTipi::all();

        foreach ($sale_types as $type) {
            if (! SaleType::where('name', $type->ad)->exists()) {
                SaleType::create([
                    'name' => $type->ad,
                    'active' => $type->aktif,
                ]);
            }
        }

    }

    private function oldServiceNames(): array
    {
        return [
            'CİLT BAKIMI' => 26, //'CİLT BAKIMI',
            'CİLT BAKIMI-249' => 26, // 'CİLT BAKIMI',
            'EPİLASYON GENİTAL' => 1, //'GENİTAL',
            'EPİLASYON GENİTAL RÜTUŞ' => 1, // 'GENİTAL',
            'EPİLASYON YARIM KOL' => 4, //'ALT KOL',
            'EPİLASYON TÜM BACAK' => 15, // 'ÜST BACAK',
            'EPİLASYON KOLTUK ALTI' => 5, // 'KOLTUK ALTI',
            'EPİLASYON BIYIK' => 6, // 'TÜM YÜZ',
            'EPİLASYON YARIM BACAK' => 15, // 'ÜST BACAK',
            'EPİLASYON GÖĞÜS UCU' => 3, // 'GÖĞÜS UCU',
            'EPİLASYON TÜM KOL' => 2, // 'ÜST KOL',
            'EPİLASYON TÜM YÜZ RÜTUŞ' => 6, // 'TÜM YÜZ',
            'EPİLASYON TÜM YÜZ' => 6, // 'TÜM YÜZ',
            'EPİLASYON GÖBEK ÇİZGİSİ' => 7, // 'GÖBEK ÇİZGİSİ',
            'EPİLASYON EL ÜSTÜ' => 8, // 'EL ÜSTÜ',
            'EPİLASYON BEL' => 10, // 'BEL',
            'EPİLASYON ÇENE' => 6, // 'TÜM YÜZ',
            'EPİLASYON POPO' => 11, // 'POPO',
            'EPİLASYON GÖĞÜS' => 12, // 'GÖĞÜS',
            'EPİLASYON ENSE' => 17, // 'ENSE',
            'EPİLASYON BOYUN' => 18, // 'BOYUN',
            'EPİLASYON OMUZ' => 19, // 'OMUZ',
            'EPİLASYON FAVORİ' => 6, // 'TÜM YÜZ',
            'EPİLASYON SIRT' => 20, // 'SIRT',
            'EPİLASYON BİKİNİ' => 13, // 'BİKİNİ',
            'ELMACIK KEMİĞİ' => 6, // 'TÜM YÜZ',
            'EPİLASYON BIYIK RÖTUŞ' => 6, // 'TÜM YÜZ',
            'EPİLASYON BOYUN RÖTUŞ' => 6, // 'TÜM YÜZ',
            'EPİLASYON ÇENE RÖTUŞ' => 6, // 'TÜM YÜZ',
            'EPİLASYON GÖĞÜS ARASI' => 21, // 'GÖĞÜS ARASI',
            'EPİLASYON ÜST BACAK' => 15, // 'ÜST BACAK',
            'EPİLASYON ALT BACAK' => 14, // 'ALT BACAK',
            'EPİLASYON PARMAK ÜSTÜ' => 8, // 'EL ÜSTÜ',
            'EPİLASYON AYAK ÜSTÜ' => 16, // 'AYAK ÜSTÜ',
            'EPİLASYON KAŞ ARASI' => 6, // 'TÜM YÜZ',
            'ERKEK SIRT' => 23, // 'SIRT',
            'ERKEK GÖĞÜS' => 24, // 'GÖĞÜS',
            'ERKEK GÖBEK' => 25, // 'GÖBEK',
            'ERKEK KOLTUK ALTI' => 5, // 'KOLTUK ALTI',
            'ERKEK OMUZ' => 19, // 'OMUZ',
            'ERKEK BEL' => 10, // 'BEL',
            'ERKEK KOL' => 2, // 'ÜST KOL',
            'ERKEK YARIM KOL' => 2, // 'ÜST KOL',
            'ERKEK YANAK' => 6, // 'TÜM YÜZ',
            'ERKEK BOYUN' => 18, // 'BOYUN',
            'ERKEK ENSE' => 17, // 'ENSE',
            'ERKEK TÜM YÜZ' => 6, // 'TÜM YÜZ',
        ];
    }
}
