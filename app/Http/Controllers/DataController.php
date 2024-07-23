<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;

class DataController extends Controller
{
    public function storeData(Request $request)
    {
        // Validate incoming JSON data
        $validated = $request->validate([
            'heartrate' => 'required|numeric',
            'temperature' => 'required|numeric',
            'ecg_samples' => 'required|array',
        ]);

        // Store data in the database
        $data = new Data();
        $data->heartrate = $validated['heartrate'];
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

        // Store dose_taken value in the database (assuming there's a 'dose_taken' column in 'data' table)
        $data = Data::latest()->first();
        $data->dose_taken = $validated['dose_taken'];
        $data->save();

        return response()->json(['status' => 'success']);
    }

    public function retrieveData()
    {
        // Retrieve latest data from the database
        $data = Data::all();

        return response()->json($data);
    }
}

