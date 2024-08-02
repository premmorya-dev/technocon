<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class StudentRegistrationModel extends Model
{
    use HasFactory;

    use Sortable;

    protected $table = 'event_program_registration';
    protected $primaryKey = 'registration_id';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */ 
    protected $fillable = [
      
        'registration_id',
        'user_time_zone_id',
        'program_id',
        'first_name',
        'last_name',
        'registered_email',
        'mobile_country_code_id',
        'mobile_no',
        'college',
        'city',
        'country_id',
        'country_state_id',
        'registration_number',
        'auto_login_string',
        'shortcode',
        'direct_login_url',
        'direct_login_short_url',
        'direct_login_qr_code_url',
        'direct_payment_url',
        'direct_payment_short_url',
        'seats',
        'amount',
        'tax_amount',
        'payment_gateway_fee',
        'total_fee_all_inclusive',
        'payment_status_id',
        'student_selection_status_id',
        'student_invitation_status_id',
        'last_date',
        'classroom_venue_id',
        'student_certificate_status_id',
        'certificate_print_status',
        'certificate_title',
        'certificate_code',
        'referrer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'url_used_for_registration',
        'whatsapp_optin',
        'student_registration_status_id',
        'email_status',
        'email_bounce_log',
        'email_bounce_datetime',
        'registration_time',
        'shipping_address_firstname',
        'shipping_address_lastname',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_address_city',
        'shipping_address_state_id',
        'shipping_address_country_id',
        'shipping_address_post_code',
        'shipping_address_mobile',
        'shipping_address_mobile_country_code_id',
        'last_update_datetime',
        'ip',
        'user_agent',
        'geo_continent',
        'geo_country',
        'geo_state',
        'geo_city',
        'geo_language',
        'geo_location',
        'geo_timezone',
        'browser',
        'platform',
        'device_type',
        'device_brand',
        'device_name',
        'is_mobile',
        'is_tablet',
    
    ];

    public $sortable = [
                 'registration_id',
                'time_zone_id',
                'program_id',
                'first_name',
                'last_name',
                'registered_email',
                'mobile_country_code_id',
                'mobile_no',
                'college',
                'city',
                'country_id',
                'country_state_id',
                'registration_number',
                'auto_login_string',
                'shortcode',
                'direct_login_url',
                'direct_login_short_url',
                'direct_login_qr_code_url',
                'direct_payment_url',
                'direct_payment_short_url',
                'seats',
                'amount',
                'tax_amount',
                'payment_gateway_fee',
                'total_fee_all_inclusive',
                'payment_status_id',
                'student_selection_status_id',
                'student_invitation_status_id',
                'last_date',
                'classroom_venue_id',
                'student_certificate_status_id',
                'certificate_print_status',
                'certificate_title',
                'certificate_code',
                'referrer',
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_term',
                'utm_content',
                'url_used_for_registration',
                'whatsapp_optin',
                'student_registration_status_id',
                'email_status',
                'email_bounce_log',
                'email_bounce_datetime',
                'registration_time',
                'shipping_address_firstname',
                'shipping_address_lastname',
                'shipping_address_line_1',
                'shipping_address_line_2',
                'shipping_address_city',
                'shipping_address_state_id',
                'shipping_address_country_id',
                'shipping_address_post_code',
                'shipping_address_mobile',
                'shipping_address_mobile_country_code_id',
                'last_update_datetime',
                'ip',
                'user_agent',
                'geo_continent',
                'geo_country',
                'geo_state',
                'geo_city',
                'geo_language',
                'geo_location',
                'geo_timezone',
                'browser',
                'platform',
                'device_type',
                'device_brand',
                'device_name',
                'is_mobile',
                'is_tablet',
   
        
    ];
}