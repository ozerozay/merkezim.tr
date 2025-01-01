<?php

namespace App\Console\Commands;

use App\Models\BEST\Musteri;
use App\Peren;
use App\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ManageTenant extends Command
{
    protected $signature = 'tenant:manage';

    protected $description = 'Tenant işlemlerini yönetir';

    public function handle()
    {
        $action = $this->choice(
            'Hangi işlemi yapmak istiyorsunuz?',
            ['Tenant Oluştur', 'Tenant için Şube Ekle', 'Tenant Veritabanını Temizle', 'Tenant Veritabanını Kaldır', 'Tenant Migrate Et', 'Tenant Seedle', 'Aktarım Resetle'],
            0
        );

        try {
            DB::beginTransaction();

            if ($action === 'Tenant Oluştur') {
                $this->createTenant();
            } elseif ($action === 'Tenant için Şube Ekle') {
                $this->addBranchToTenant();
            } elseif ($action === 'Tenant Veritabanını Temizle') {
                $this->clearTenantDatabase();
            } elseif ($action === 'Tenant Veritabanını Kaldır') {
                $this->dropTenantAndDatabase();
            } elseif ($action === 'Tenant Migrate Et') {
                $this->migrateTenant();
            } elseif ($action === 'Tenant Seedle') {
                $this->seedTenant();
            } elseif ($action === 'Aktarım Resetle') {
                $this->resetOldUsers();
            }

            DB::commit();
            $this->info('✅ İşlem başarıyla tamamlandı.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ İşlem sırasında bir hata oluştu: '.$e->getMessage());
        }

        return CommandAlias::SUCCESS;
    }

    private function resetOldUsers()
    {

        Musteri::query()->update([
            'aktar' => null,
        ]);

    }

    private function createTenant()
    {
        $tenantId = $this->ask('Tenant ID giriniz');
        if (Tenant::find($tenantId)) {
            throw new \Exception("Tenant ID '$tenantId' zaten mevcut.");
        }

        $domain = $this->ask('Domain giriniz (örnek: tenant1.example.com)');
        if (Tenant::whereHas('domains', fn ($query) => $query->where('domain', $domain))->exists()) {
            throw new \Exception("Domain '$domain' zaten başka bir tenant tarafından kullanılıyor.");
        }

        $summary = <<<SUMMARY
İşlem Özeti:
- Tenant ID: $tenantId
- Domain: $domain
SUMMARY;

        $this->info($summary);

        if (! $this->confirm('Bu işlemi onaylıyor musunuz?')) {
            throw new \Exception('İşlem iptal edildi.');
        }

        $this->output->write('<info>Tenant oluşturuluyor</info>');
        $this->fakeLoading(3);

        $tenant = Tenant::create(['id' => $tenantId]);
        $tenant->domains()->create(['domain' => $domain]);

        $this->info('✅ Tenant oluşturuldu.');

        return CommandAlias::SUCCESS;
    }

    private function addBranchToTenant(): void
    {
        $tenantId = $this->selectTenant();
        $tenant = Tenant::find($tenantId);

        $branchName = $this->ask('Eklenecek şubenin adı nedir?');
        $exists = $tenant->run(fn () => DB::table('branches')->where('name', $branchName)->exists());

        if ($exists) {
            throw new \Exception("Şube adı '$branchName' zaten bu tenant için mevcut.");
        }

        $summary = <<<SUMMARY
İşlem Özeti:
- Tenant ID: $tenantId
- Şube Adı: $branchName
SUMMARY;

        $this->info($summary);

        if (! $this->confirm('Bu işlemi onaylıyor musunuz?')) {
            throw new \Exception('İşlem iptal edildi.');
        }

        $this->output->write('<info>Şube ekleniyor</info>');
        $this->fakeLoading(3);

        $tenant->run(function () use ($branchName) {
            $this->addBranch($branchName);
        });
    }

    private function clearTenantDatabase()
    {
        $tenantId = $this->selectTenant();
        $tenant = Tenant::find($tenantId);

        $summary = <<<SUMMARY
İşlem Özeti:
- Tenant ID: $tenantId
- Eylem: Tüm tablolar silinecek.
SUMMARY;

        $this->info($summary);

        if (! $this->confirm('Bu işlemi onaylıyor musunuz?')) {
            throw new \Exception('İşlem iptal edildi.');
        }

        $this->output->write('<info>Tablolar siliniyor</info>');
        $this->fakeLoading(5);

        $tenant->run(function () {
            $tables = DB::select('SHOW TABLES');
            $dbName = DB::getDatabaseName();
            $key = "Tables_in_$dbName";

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            foreach ($tables as $table) {
                $tableName = $table->$key;
                DB::statement("DROP TABLE `$tableName`");
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }

    private function dropTenantAndDatabase()
    {
        $tenantId = $this->selectTenant();
        $tenant = Tenant::find($tenantId);

        $summary = <<<SUMMARY
İşlem Özeti:
- Tenant ID: $tenantId
- Eylem: Veritabanı ve tenant kaydı tamamen kaldırılacak.
SUMMARY;

        $this->info($summary);

        if (! $this->confirm('Bu işlemi onaylıyor musunuz?')) {
            throw new \Exception('İşlem iptal edildi.');
        }

        $this->output->write('<info>Veritabanı ve tenant kaldırılıyor</info>');
        $this->fakeLoading(5);

        $tenant->run(function () {
            $dbName = DB::getDatabaseName();

            DB::statement("DROP DATABASE IF EXISTS `$dbName`");
        });

        $tenant->domains()->delete();
        $tenant->delete();

        $this->info("✅ Tenant '$tenantId', bağlı domainler ve veritabanı başarıyla kaldırıldı.");
    }

    private function migrateTenant()
    {
        $tenantId = $this->selectTenant();
        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            throw new \Exception("Belirtilen tenant bulunamadı: $tenantId");
        }

        $summary = <<<SUMMARY
İşlem Özeti:
- Tenant ID: $tenantId
- Eylem: Veritabanı migrate edilecek.
SUMMARY;

        $this->info($summary);

        if (! $this->confirm('Bu işlemi onaylıyor musunuz?')) {
            throw new \Exception('İşlem iptal edildi.');
        }

        $this->output->write('<info>Veritabanı migrate ediliyor</info>');
        $this->fakeLoading(3);

        $tenant->run(function () {
            Artisan::call('migrate:fresh', [
                '--path' => 'database/migrations/tenant',
                '--force' => true,
            ]);
        });

        $this->info("✅ Tenant '$tenantId' için migrate işlemi başarıyla tamamlandı.");
    }

    private function selectTenant()
    {
        $tenants = Tenant::all(['id'])->pluck('id')->toArray();

        if (empty($tenants)) {
            throw new \Exception('Kayıtlı bir tenant bulunamadı.');
        }

        return $this->choice('Hangi tenant üzerinde işlem yapmak istiyorsunuz?', $tenants);
    }

    private function fakeLoading($seconds)
    {
        $this->output->writeln(' ✅');
    }

    private function addBranch(string $branchName)
    {
        DB::table('branches')->insert([
            'name' => $branchName,
            'opening_hours' => json_encode(Peren::opening_hours()),
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info("✅ Şube '$branchName' başarıyla eklendi.");

    }

    private function seedTenant()
    {
        $tenantId = $this->selectTenant();
        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            throw new \Exception("Belirtilen tenant bulunamadı: $tenantId");
        }

        $summary = <<<SUMMARY
İşlem Özeti:
- Tenant ID: $tenantId
- Eylem: Seed işlemi çalıştırılacak.
SUMMARY;

        $this->info($summary);

        if (! $this->confirm('Bu işlemi onaylıyor musunuz?')) {
            throw new \Exception('İşlem iptal edildi.');
        }

        $this->output->write('<info>Seed işlemi başlatılıyor</info>');
        $this->fakeLoading(3);

        $tenant->run(function () {
            Artisan::call('db:seed', [
                '--class' => 'Database\Seeders\RoleSeeder',
                '--force' => true,
            ]);
            Artisan::call('db:seed', [
                '--class' => 'Database\Seeders\IlSeed',
                '--force' => true,
            ]);
        });

        $this->info("✅ Tenant '$tenantId' için seed işlemi başarıyla tamamlandı.");
    }
}
