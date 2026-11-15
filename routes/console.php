<?php

use App\Jobs\BackupMill;
use App\Jobs\CompileDailyMis;
use App\Jobs\EscalateOpenIssues;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new CompileDailyMis)->dailyAt('07:00');
Schedule::job(new EscalateOpenIssues)->hourly();
Schedule::job(new BackupMill)->dailyAt('02:00');
