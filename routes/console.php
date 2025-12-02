<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic cleanup of old activity logs
Schedule::command('logs:cleanup --force --days=90')
    ->daily()
    ->at('02:00')
    ->timezone('Asia/Jakarta')
    ->description('Clean up activity logs older than 90 days');
