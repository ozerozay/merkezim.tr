<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class IlSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SQL dosyasının yolu
        $path = database_path('sql/il_ilce.sql');

        // SQL dosyasını yükleyip içeriğini çalıştır
        $sql = File::get($path);
        DB::unprepared($sql);
    }
}
