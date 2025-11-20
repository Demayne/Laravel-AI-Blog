<?php
/**
 * console.php
 *
 * Purpose:
 * Registers custom Artisan commands for the Laravel application.
 * Here, a sample command displays an inspiring quote in the console.
 *
 * URL References:
 * - Artisan::command(): https://laravel.com/docs/artisan#defining-commands
 * - $this->comment(): https://laravel.com/docs/artisan#writing-output
 */

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
