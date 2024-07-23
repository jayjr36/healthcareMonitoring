<?php

// database/seeders/DataSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Data;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Data::create([
            'heartrate' => 72,
            'temperature' => 98.6,
            'ecg_samples' => [0.1, 0.2, 0.1, 0.4, 0.3],
            'dose_taken' => true,
        ]);

        Data::create([
            'heartrate' => 65,
            'temperature' => 97.9,
            'ecg_samples' => [0.2, 0.3, 0.2, 0.5, 0.4],
            'dose_taken' => false,
        ]);

        Data::create([
            'heartrate' => 78,
            'temperature' => 99.1,
            'ecg_samples' => [0.3, 0.1, 0.3, 0.6, 0.2],
            'dose_taken' => true,
        ]);

        Data::create([
            'heartrate' => 80,
            'temperature' => 100.2,
            'ecg_samples' => [0.2, 0.4, 0.1, 0.3, 0.5],
            'dose_taken' => false,
        ]);
    }
}
