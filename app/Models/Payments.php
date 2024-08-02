<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class PaymentsModel extends Model
{
    use HasFactory;
   
    protected $table = 'payments';
    protected $primaryKey = 'payment_log_id';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'payment_log_id',
        'payment_gateway_id',
        'registration_number',
        'payment_id',
        'order_id',
        'contact',
        'amount_subunit',
        'amount',
        'currency',
        'status',
        'description',
        'payment_response_json',
        'added_datetime',
    
    ];

    public $sortable = [
        'payment_log_id',
        
    ];
}
