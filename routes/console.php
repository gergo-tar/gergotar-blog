<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\GenerateSitemaps;

Schedule::command(GenerateSitemaps::class)->daily();
