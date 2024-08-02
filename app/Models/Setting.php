<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'app_settings';
    protected $fillable = [
        'setting_id',
        'key',
        'value',
        'created_at',
        'updated_at',      
    
    ];

    
}
