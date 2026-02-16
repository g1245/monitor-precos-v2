<?php

use Illuminate\Support\Facades\Schedule;

// Schedule command to cache top discounted products every fifteen minutes
Schedule::command('app:cache-top-discounted-products')->everyFifteenMinutes();

// Schedule command to create daily price history entries for all active products
Schedule::command('app:create-today-price')->dailyAt('00:01');