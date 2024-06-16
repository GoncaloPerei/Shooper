<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('scraper:run')->dailyAt('1:00');
