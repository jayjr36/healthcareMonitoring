<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Dose;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function storeData(Request $request)
    {
        // Validate incoming JSON data
        $validated = $request->validate([
            'heartrate' => 'required|numeric',
            'oxygen_saturation' => 'required|numeric',
            'temperature' => 'required|numeric',
            'ecg_samples' => 'required|array',
        ]);

        // Store data in the database
        $data = new Data();
        $data->heartrate = $validated['heartrate'];
        $data->oxygen_saturation = $validated['oxygen_saturation'];
        $data->r_value = 0;
        $data->temperature = $validated['temperature'];
        $data->ecg_samples = json_encode($validated['ecg_samples']);
        $data->save();

        return response()->json(['status' => 'success']);
    }

    public function storeDose(Request $request)
    {
        $validated = $request->validate([
            'dose_taken' => 'required|boolean',
        ]);

        $dose = new Dose();
        $dose->dose_taken = $validated['dose_taken'];
        $dose->save();

        return response()->json(['status' => 'success']);
    }

    public function retrieveData()
    {
$data = Data::orderBy('created_at', 'desc')->take(30)->get();

$doses = Dose::orderBy('created_at', 'desc')->take(30)->get();

        $response = [
            'data' => $data,
            'doses' => $doses,
        ];

        return response()->json($response);
    }
}

