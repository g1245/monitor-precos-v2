<?php

use Illuminate\Support\Facades\Schedule;

// Schedule command to cache top discounted products every fifteen minutes
Schedule::command('app:cache-top-discounted-products')->everyFifteenMinutes();
