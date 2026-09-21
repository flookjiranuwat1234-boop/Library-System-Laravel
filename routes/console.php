<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('library:notify-overdue')
    ->dailyAt('08:00')
    ->timezone('Asia/Bangkok')
    ->withoutOverlapping(30)
    ->onOneServer();

Schedule::command('library:backup')
    ->dailyAt('02:00')
    ->timezone('Asia/Bangkok')
    ->withoutOverlapping(60)
    ->onOneServer();

Schedule::command('library:recalculate-journey')
    ->dailyAt('03:00')
    ->timezone('Asia/Bangkok')
    ->withoutOverlapping(30)
    ->onOneServer();
