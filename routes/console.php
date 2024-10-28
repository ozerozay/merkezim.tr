<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:expire-offers')->dailyAt('00:10');
Schedule::command('app:activate-freezed-sales')->dailyAt('00:15');
Schedule::command('app:expire-sales')->dailyAt('00:20');
