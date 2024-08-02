<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class NotificationJobQueue extends Model
{
    use HasFactory;
   
    protected $table = 'notification_job_queue';
    protected $primaryKey = 'notification_job_queue_id';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'notification_job_queue_id',
        'bulk_notification_job_id',
        'notification_enabled',
        'notification_job_queue_type',
        'registration_id',
        'scheduled_datetime',
        'notification_status',
        'added_datetime',
        'queue_process_start_datetime',
        'queue_process_end_datetime',
        'notification_log',
        'request_id',
        'delivery_status',
        'delivery_status_datettime',
        
    
    ];

    public $sortable = [
        'notification_job_queue_id',
        'notification_enabled',
        'notification_job_queue_type',
        'registration_id',
        'notification_status',
        'added_datetime',
        'queue_process_start_datetime',
        'queue_process_end_datetime',
        'notification_log',
        
        
    ];
}
