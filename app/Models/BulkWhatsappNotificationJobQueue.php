<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class BulkWhatsappNotificationJobQueue extends Model
{
    use HasFactory;
   
    protected $table = 'bulk_whatsapp_notification_job_queue';
    protected $primaryKey = 'bulk_whatsapp_notification_job_queue_id';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bulk_whatsapp_notification_job_queue_id',
        'bulk_whatsapp_notification_job_id',
        'registration_id',
        'notification_status',
        'notification_status',
        'added_datetime',
        'queue_process_start_datetime',
        'queue_process_end_datetime',
        'notification_log',
    
    ];

  
}
