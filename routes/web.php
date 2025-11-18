<?php

use App\Http\Controllers\Dht22Controller;
use Illuminate\Support\Facades\Route;

use App\Models\Setting;
use App\Models\SmartHome;
use Illuminate\Http\Request;

Route::get('/', function () {
    $setting = Setting::first();
    $lampuStatuses = SmartHome::all();
    return view('welcome', compact('setting', 'lampuStatuses'));
});

// Route untuk DHT22
Route::get('/update-data/{tmp}/{hmd}', [Dht22Controller::class, 'updateData']);
Route::get('/get-data', [Dht22Controller::class, 'getData']);

// Route untuk setting DHT22
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

Route::post('/toggle-lampu/{id}', [Dht22Controller::class, 'toggleLampu']);
Route::post('/lampu/toggle/{id}', [Dht22Controller::class, 'toggle'])->name('lampu.toggle');
Route::post('/devices/update-name', [Dht22Controller::class, 'updateName'])->name('devices.updateName');

// Mengambil status semua lampu
Route::get('/get-lampu', [Dht22Controller::class, 'getLampu']);