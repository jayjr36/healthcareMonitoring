<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    use HasFactory;
    protected $fillable = ['heartrate', 'temperature', 'ecg_samples', 'dose_taken'];

    protected $casts = [
        'ecg_samples' => 'array',
        'dose_taken' => 'boolean',
    ];
}
