<?php

use App\Http\Controllers\Dht22Controller;
use Illuminate\Support\Facades\Route;

use App\Models\Setting;
use Illuminate\Http\Request;

Route::get('/', function () {
    $setting = Setting::first();
    return view('welcome', compact('setting'));
});


Route::get('/update-data/{tmp}/{hmd}', [Dht22Controller::class, 'updateData']);
Route::get('/get-data', [Dht22Controller::class, 'getData']);


Route::get('/get-setting', [Dht22Controller::class, 'getSetting']);
Route::post('/update-setting', [Dht22Controller::class, 'updateSetting']);


// Route untuk ditarik ESP8266
Route::get('/get-setting', function () {
    $setting = Setting::first();
    return response()->json([
        'threshold_temp' => $setting ? $setting->threshold_temp : 30
    ]);
});

// Route untuk ubah nilai dari browser (manual aja sementara)
Route::get('/set-setting/{value}', function ($value) {
    $setting = Setting::first() ?? new Setting();
    $setting->threshold_temp = $value;
    $setting->save();

    return "Nilai threshold diubah ke: $value";
});
