<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class TechnoconEvent extends Model
{
    use HasFactory;
    use Sortable;
    protected $table = 'event';
    protected $primaryKey = 'event_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'event_code',
        'event_backend_name',
        'event_presenting_partner_1',
        'event_presenting_partner_2_association_slug',
        'event_presenting_partner_2',
        'event_presenting_partner_1_association_slug',
        'event_name_prefix',
        'event_name',
        'event_name_suffix',
        'event_partner_1_association_slug',
        'event_partner_1',
        'event_partner_1_suffix',
        'event_partner_2_association_slug',
        'event_partner_2',
        'event_partner_2_suffix',
        'event_partner_3_association_slug',
        'event_partner_3',
        'event_partner_3_suffix',
        'event_from_date',
        'event_end_date',
        'event_url',
        'registration_page_banner_url',
        'registration_page_header_main_text',
        'registration_page_header_important_notes',
        'whatsapp_notification_banner_image_dir_url',
        'whatsapp_notification_banner_image',
    
    ];

    public $sortable = [
        'event_id',
        'event_code',
        'event_backend_name',
        'event_presenting_partner_1',
        'event_presenting_partner_2_association_slug',
        'event_presenting_partner_2',
        'event_presenting_partner_1_association_slug',
        'event_name_prefix',
        'event_name',
        'event_name_suffix',
        'event_partner_1_association_slug',
        'event_partner_1',
        'event_partner_1_suffix',
        'event_partner_2_association_slug',
        'event_partner_2',
        'event_partner_2_suffix',
        'event_partner_3_association_slug',
        'event_partner_3',
        'event_partner_3_suffix',
        'event_from_date',
        'event_end_date',
        'event_url',
        'registration_page_banner_url',
        'registration_page_header_main_text',
        'registration_page_header_important_notes',
        'whatsapp_notification_banner_image_dir_url',
        'whatsapp_notification_banner_image',
        
        
    ];
}
