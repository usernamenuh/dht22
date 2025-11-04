<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dht22;

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
}
