<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    
    $mysqlData = DB::connection('mysql')->table('post')->get();
    
    foreach ($mysqlData as $data) {
        
        $existingData = DB::connection('pgsql')->table('Post_backup')->where('id', $data->id)->first();
        
        if ($existingData) {
            
            DB::connection('pgsql')->table('Post_backup')->where('id', $data->id)->update((array) $data);
        } else {
            
            DB::connection('pgsql')->table('Post_backup')->insert((array) $data);
        }
    }
})->everyTenSeconds();
