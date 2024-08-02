<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class IncomingWebhookJobQueue extends Model
{
    use HasFactory;
   
    protected $table = 'incoming_webhook_job_queue';
    protected $primaryKey = 'incoming_webhook_job_queue_id';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'incoming_webhook_job_queue_id',
        'incoming_webhook_id',
        'queue_processing',
        'incoming_webhook_header_json',
        'incoming_webhook_body_json',
        'recieved_from_url',
        'queue_process_status',
        'added_datetime',
        'queue_process_start_datetime',
        'queue_process_end_datetime',
        'webhook_log',
        'process_log',
    
    ];

    public $sortable = [    
     
        
    ];
}
