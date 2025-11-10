<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dht22;
use App\Models\Setting;

class Dht22Controller extends Controller
{
    public function __construct()
    {
       $dh1 = Dht22::count();
       if ($dh1 == 0) {
        $dht22 = Dht22::create([
            'temperature' => 0,
            'humidity' => 0,
        ]);
       }
    }
    
    public function updateData($tmp, $hmd)
    {
        $dh1 = Dht22::first();
        $dh1->temperature = $tmp;
        $dh1->humidity = $hmd;
        $dh1->save();

        return response()->json([
            'success' => true,
            'message' => 'Data updated successfully',
        ]);
    }

    public function getData()
    {
        $dh1 = Dht22::first();
        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => $dh1,
        ]);
    }

    public function getSetting()
    {
        $setting = Setting::first();
        return response()->json([
            'threshold_temp' => $setting ? $setting->threshold_temp : 30
        ]);
    }

    // Mengubah setting dari web
    public function updateSetting(Request $request)
    {
        $setting = Setting::first() ?? new Setting();
        $setting->threshold_temp = $request->threshold_temp;
        $setting->save();

        return redirect()->back()->with('success', 'Setting diperbarui!');
    }
}
