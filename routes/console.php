<?php

use Illuminate\Support\Facades\Schedule;

// Schedule command to cache top discounted products every fifteen minutes
Schedule::command('app:cache-top-discounted-products')->everyFifteenMinutes();

// Schedule command to create daily price history entries for all active products
Schedule::command('app:create-today-price')->dailyAt('00:01');

// Schedule command to sync product data by store every three hours
Schedule::command('app:sync-product-by-store')->everyThreeHours();

// Schedule command to sync top discounted products to Department 1 every hour
Schedule::command('app:sync-top-discounted-products-to-department')->hourly();

/** 
 * Schedule commands for backup and cleanup of old backups
 **/
Schedule::command('backup:run')->dailyAt('01:00');
Schedule::command('backup:clean')->dailyAt('02:00');