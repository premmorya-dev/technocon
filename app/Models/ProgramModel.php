<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ProgramModel extends Model
{
    use HasFactory;

    use Sortable;

    protected $table = 'event_program';
    protected $primaryKey = 'program_id';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */ 
    protected $fillable = [
        'program_id',
        'time_zone_id',
        'event_id',
        'registration_seo_url_id',
        'program_location_id',
        'event_program_type_id',
        'program_certificate_id',
        'program_code',
        'program_name',
        'program_name_for_certificate',
        'program_name_sms_dlt',
        'status',
        'rstatus',
        'program_duration',
        'program_duration_time_unit',
        'registration_no_prefix',
        'payment_last_date',
        'start_dates',
        'end_dates',
        'start_time',
        'end_time',
        'level',
        'fees',
        'currency_id',
        'payment_gateway_id',
        'fees_inclusive_tax',
        'tax_rate',
        'payment_gateway_fee_rate',
        'max_member',
        'any_special_terms',
        'online_content',
        'content_links',
        'registration_page_url',
        'registration_page_short_url',
        'registration_page_root_domain',
        'event_website',
        'program_details_page_url',
        'program_details_page_short_url',
        'enable_payment_link',
        'sms_gateway_id',
        'smtp_id',
        'registration_email_template_id',
        'registration_sms_template_id',
        'payment_email_template_id',
        'payment_sms_template_id',
        'currency_exchange_gateway_id',
        'contact_us_email',
        'contact_us_mobile',
        'zoik_app_id',
        'zoik_app_common_list_uid',
        'zoik_app_program_list_uid',
        'zoik_app_common_list_sync',
        'zoik_app_program_list_sync',
        'zoik_app_common_list_field_mapping',
        'zoik_app_program_list_field_mapping',
        'selection_status_after_registartion',
        'selection_status_after_payment',
        'invitation_status_after_registartion',
        'invitation_status_after_payment',
        'payment_status_after_registration',
        'payment_status_after_payment',
        'email_header_banner_url',
        'email_header_banner_alt',
        'short_url_api_id',
        'short_url_domain',
        'short_url_channel_id',
        'whatsapp_api_id',
        'whatsapp_sender_id',
        'whatsapp_notification_on_registration',
        'sms_notification_on_registration',
        'email_notification_on_registration',
        'whatsapp_notification_on_payment',
        'sms_notification_on_payment',
        'email_notification_on_payment',
        'whatsapp_template_id_on_registration_success',
        'whatsapp_template_id_on_payment_success',
        'enable_gate_pass',
        'enable_gate_pass_on_selection_status_id',
        'enable_address_field',
        'enable_address_field_on_selection_status_id',
        'enable_digital_certificate',
        'enable_digital_certificate_on_selection_status_id',
        'registration_outgoing_webhooks',
        'addional_email_notification',
     
    
    ];

    public $sortable = [
        'program_id',
        'time_zone_id',
        'event_id',
        'registration_seo_url_id',
        'program_location_id',
        'program_code',
        'program_name',
        'program_name_for_certificate',
        'program_name_sms_dlt',
        'status',
        'rstatus',
        'event_program_type_id',
        'program_duration',
        'program_duration_time_unit',
        'registration_no_prefix',
        'start_dates',
        'end_dates',
        'start_time',
        'end_time',
        'level',
        'fees',
        'currency_id',
        'fees_inclusive_tax',
        'tax_rate',
        'payment_gateway_fee_rate',
        'max_member',
        'any_special_terms',
        'online_content',
        'content_links',
        'registration_page_url',
        'registration_page_short_url',
        'registration_page_root_domain',
        'event_website',
        'program_details_page_url',
        'program_details_page_short_url',
        'enable_payment_link',
        'payment_gateway_id',
        'sms_gateway_id',
        'smtp_id',
        'registration_email_template_id',
        'registration_sms_template_id',
        'payment_email_template_id',
        'payment_sms_template_id',
        'currency_exchange_gateway_id',
        'short_url_domain',
        'contact_us_email',
        'contact_us_mobile',
        'zoik_app_program_list_uid',
        'zoik_app_common_list_uid',
        'zoik_app_id',
        'zoik_app_program_list_field_mapping',
        'zoik_app_common_list_field_mapping',
        'selection_status_after_registartion',
        'selection_status_after_payment',
        'invitation_status_after_registartion',
        'invitation_status_after_payment',
        'payment_status_after_registration',
        'payment_status_after_payment',
        'email_header_banner_url',
        'email_header_banner_alt',
        'short_url_api_id',
        'short_url_channel_id',
        'whatsapp_api_id',
        'whatsapp_sender_id',
        'whatsapp_notification_on_registration',
        'sms_notification_on_registration',
        'email_notification_on_registration',
        'whatsapp_notification_on_payment',
        'sms_notification_on_payment',
        'email_notification_on_payment',
        'whatsapp_template_id_on_registration_success',
        'whatsapp_template_id_on_payment_success',
        'enable_gate_pass',
        'enable_gate_pass_on_selection_status_id',
        'enable_address_field',
        'enable_address_field_on_selection_status_id',
        'enable_digital_certificate',
        'enable_digital_certificate_on_selection_status_id',
        'registration_outgoing_webhooks',
    ];
}
