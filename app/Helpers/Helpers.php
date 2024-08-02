<?php

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use MaxMind\Db\Reader;
use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;
use Carbon\Carbon;


if (!function_exists('getShortcode')) {
    function getShortcode($registration_number)
        {  
            $registration_data = (array) DB::table('event_program_registration')
            ->leftJoin('event_program','event_program_registration.program_id' , "=", 'event_program.program_id' )
            ->leftJoin('event_program_location','event_program_location.program_location_id' , "=", 'event_program_location.program_location_id' )
            ->leftJoin('event_program_location_venue', 'event_program_location_venue.classroom_venue_id', "=", 'event_program_registration.classroom_venue_id')
            ->leftJoin('mobile_country_list','mobile_country_list.mobile_country_code_id' , "=", 'event_program_registration.mobile_country_code_id' )
            ->leftJoin('country_to_state_code','country_to_state_code.country_state_id' , "=", 'event_program_registration.country_state_id' )
            ->leftJoin('country','country.mobile_country_code_id' , "=", 'event_program_registration.mobile_country_code_id' )
            ->leftJoin('students_certificate_status','students_certificate_status.student_certificate_status_id' , "=", 'event_program_registration.student_certificate_status_id' )
            ->leftJoin('event_program_certificate','event_program_certificate.program_certificate_id' , "=", 'event_program.program_certificate_id' )
            ->leftJoin('students_selection_status','students_selection_status.student_selection_status_id' , "=", 'event_program_registration.student_selection_status_id' )
            ->leftJoin('payment_status','payment_status.payment_status_id' , "=", 'event_program_registration.payment_status_id' )
            ->leftJoin('payment_gateway_config', 'payment_gateway_config.payment_gateway_id', '=', 'event_program.payment_gateway_id' )
            ->leftJoin('currency_settings', 'currency_settings.currency_id', '=', 'event_program.currency_id' )
            ->leftJoin('payments', 'payments.payment_log_id', '=', 'event_program_registration.payment_log_id' )
            ->leftJoin('event', 'event.event_id', '=', 'event_program.event_id' )
            ->leftJoin('event_program_type', 'event_program_type.event_program_type_id', '=', 'event_program.event_program_type_id' )
            ->leftJoin('event_program_registration_seo_url', 'event_program_registration_seo_url.registration_seo_url_id', '=', 'event_program.registration_seo_url_id' )
            ->where('event_program_registration.registration_number',$registration_number)    
            ->select(
             'event_program.*',  // Select all columns from event_program
             'event_program_location.*',  // Select all columns from event_program_location
             'event_program_location_venue.*',  // Select all columns from event_program_location_venue
             'mobile_country_list.*',  // Select all columns from mobile_country_list
             'country_to_state_code.*',  // Select all columns from country_to_state_code
             'country.*',  // Select all columns from country
             'students_certificate_status.*',  // Select all columns from students_certificate_status
             'event_program_certificate.*',  // Select all columns from event_program_certificate
             'students_selection_status.*',  // Select all columns from students_selection_status
             'payment_status.*',  // Select all columns from payment_status
             'payment_gateway_config.*',  // Select all columns from payment_gateway_config
             'currency_settings.*',  // Select all columns from currency_settings
             'payments.*',  // Select all columns from payments
             'event.*',  // Select all columns from event
             'event_program_type.*',  // Select all columns from event_program_type
             'event_program_registration_seo_url.*',  // Select all columns from event_program_registration_seo_url
             'event_program_registration.*',  // Select all columns from event_program_registration
             DB::raw(dbPrefix().'currency_settings.code') , 
         )       
            ->first();
            
           
 
            $registered_program_registration_page_url = url("/event/registered/" . $registration_data['seo_url']);
 
            if( !empty(DB::table('country')
            ->where('mobile_country_code_id',$registration_data['country_id'])
            ->first()) ){
             $country_name =   DB::table('country')
             ->where('mobile_country_code_id',$registration_data['country_id'])
             ->first()->country_name; 
             }else{
                 $country_name ='';
             } 
 
             if( !empty(DB::table('payment_status')
             ->where('payment_status_id',$registration_data['payment_status_id'])
             ->first()) ){
              $payment_status =   DB::table('payment_status')
              ->where('payment_status_id',$registration_data['payment_status_id'])
              ->first()->payment_status; 
              }else{
                  $payment_status ='';
              } 
  
 
             if( !empty(DB::table('program_certificate')
            ->where('registration_number',$registration_data['registration_number'])
            ->first()) ){
             $certificate_code =  DB::table('program_certificate')
             ->where('registration_number',$registration_data['registration_number'])
             ->first()->certificate_code; 
             }else{
                 $certificate_code ='';
             } 
 
 
             if( !empty( DB::table('country_to_state_code')
             ->where('country_state_id',$registration_data['country_state_id'])
             ->first()) ){
                 $state_name =   DB::table('country_to_state_code')
                 ->where('country_state_id',$registration_data['country_state_id'])
                 ->first()->state_name; 
             }else{
                 $state_name ='';
             } 
 
 
             if( !empty(DB::table('mobile_country_list')
             ->where('mobile_country_code_id',$registration_data['mobile_country_code_id'])
             ->first() ) ){
                 $country_code =   DB::table('mobile_country_list')
             ->where('mobile_country_code_id',$registration_data['mobile_country_code_id'])
             ->first()->country_code; 
             }else{
                 $country_code = '';
             } 
 
             if( !empty(DB::table('payments')
             ->where('payment_log_id',$registration_data['payment_log_id'])
             ->first()) ){
                 $payments  =   DB::table('payments')
                 ->where('payment_log_id',$registration_data['payment_log_id'])
                 ->first(); 
             }else{
                 $payments =[];
             }
              
            
             if( !empty(DB::table('country')
             ->where('mobile_country_code_id',$registration_data['shipping_address_country_id'])
             ->first()  ) ){
                 $registered_shipping_address_country_name =   DB::table('country')
             ->where('mobile_country_code_id',$registration_data['shipping_address_country_id'])
             ->first()->country_name;
             }else{
                 $registered_shipping_address_country_name ='';
             } 
 
           
             if( !empty($shipping_address_mobile_country_code) ){
                 $shipping_address_mobile_country_code =   DB::table('country')
                 ->where('mobile_country_code_id',$registration_data['shipping_address_mobile_country_code_id'])
                 ->first()->mobile_country_code_id;  
          
             }else{
                 $shipping_address_mobile_country_code ='';
             } 
            
 
            
             if( !empty(DB::table('country_to_state_code')
             ->where('country_state_id',$registration_data['shipping_address_state_id'])
             ->first()   ) ){
                 $shipping_address_state_name =   DB::table('country_to_state_code')
                 ->where('country_state_id',$registration_data['shipping_address_state_id'])
                 ->first()->state_name;  
             }else{
                 $shipping_address_state_name ='';
             }           
          
 
             if( !empty(DB::table('country')
             ->where('mobile_country_code_id',$registration_data['location_country_id'])
             ->first()) ){
                 $location_country_name =   DB::table('country')
                 ->where('mobile_country_code_id',$registration_data['location_country_id'])
                 ->first()->country_name ; 
             }else{
                 $location_country_name ='';
             } 
             
             if( !empty(DB::table('country_to_state_code')
             ->where('country_state_id',$registration_data['location_state_id'])
             ->first()) ){
                 $location_state_name =   DB::table('country_to_state_code')
             ->where('country_state_id',$registration_data['location_state_id'])
             ->first()->state_name; 
             }else{
                 $location_state_name ='';
             } 
 
 
             if( !empty(DB::table('students_certificate_status')
             ->where('student_certificate_status_id',$registration_data['student_certificate_status_id'])
             ->first()) ){
                 $student_certificate_status_name =   DB::table('students_certificate_status')
             ->where('student_certificate_status_id',$registration_data['student_certificate_status_id'])
             ->first()->certificate_status; 
             }else{
                 $student_certificate_status_name ='';
             } 
 
 
             if( !empty(DB::table('students_selection_status')
             ->where('student_selection_status_id',$registration_data['student_selection_status_id'])
             ->first()) ){
                 $student_selection_status_name =   DB::table('students_selection_status')
                 ->where('student_selection_status_id',$registration_data['student_selection_status_id'])
                 ->first()->selection_status; 
             }else{
                 $student_selection_status_name ='';
             } 
            
 
             if( !empty(DB::table('students_invitation_status')
             ->where('student_invitation_status_id',$registration_data['student_invitation_status_id'])
             ->first()) ){
                 $student_invitation_status_name =   DB::table('students_invitation_status')
             ->where('student_invitation_status_id',$registration_data['student_invitation_status_id'])
             ->first()->invitation_status; 
 
             }else{
                 $student_invitation_status_name ='';
             } 
            
             if( !empty(DB::table('students_registration_status')
             ->where('student_registration_status_id',$registration_data['student_registration_status_id'])
             ->first()   ) ){
                 $students_registration_status =   DB::table('students_registration_status')
             ->where('student_registration_status_id',$registration_data['student_registration_status_id'])
             ->first()->registration_status; 
             }else{
                 $students_registration_status ='';
             } 
 
             if (!empty(DB::table('time_zone')
             ->where('time_zone_id', $registration_data['time_zone_id'])
             ->first())) {
             $registered_program_timezone =   DB::table('time_zone')
                 ->where('time_zone_id', $registration_data['time_zone_id'])
                 ->first()->timezone;
             } else {
                 $registered_program_timezone = '';
             }
             
     
     
             
     
             if (!empty(DB::table('time_zone')
                 ->where('time_zone_id', $registration_data['time_zone_id'])
                 ->first())) {
                 $registered_program_timezone_lable =   DB::table('time_zone')
                     ->where('time_zone_id', $registration_data['time_zone_id'])
                     ->first()->timezone_lable;
             } else {
                 $registered_program_timezone_lable = '';
             }
     
     
             if (!empty(DB::table('time_zone')
             ->where('time_zone_id', $registration_data['user_time_zone_id'])
             ->first())) {
             $registered_user_timezone_lable =   DB::table('time_zone')
                 ->where('time_zone_id', $registration_data['user_time_zone_id'])
                 ->first()->timezone_lable;
             } else {
                 $registered_user_timezone_lable = '';
             }
     
     
             if (!empty(DB::table('time_zone')
             ->where('time_zone_id', $registration_data['user_time_zone_id'])
             ->first())) {
                 $registered_user_timezone =   DB::table('time_zone')
                 ->where('time_zone_id', $registration_data['user_time_zone_id'])
                 ->first()->timezone;
             } else {
                 $registered_user_timezone = '';
             }

             if(isset($registration_data['direct_payment_short_url']) &&  $registration_data['direct_payment_short_url'] ){
                $registered_direct_payment_short_url_parameter = str_replace("https://t2k.in/", "" ,$registration_data['direct_payment_short_url']);
            }else{
                $registered_direct_payment_short_url_parameter = '';
            }
    
            if(isset($registration_data['direct_login_short_url']) &&  $registration_data['direct_login_short_url'] ){
                $registered_direct_login_short_url_parameter = str_replace("https://t2k.in/", "" ,$registration_data['direct_login_short_url']);
            }else{
                $registered_direct_login_short_url_parameter = '';
            }
           
    
            if(isset($registration_data['program_details_page_short_url']) &&  $registration_data['program_details_page_short_url'] ){
                $registered_program_details_page_short_url_parameter = str_replace("https://t2k.in/", "" ,$registration_data['program_details_page_short_url']);
            }else{
                $registered_program_details_page_short_url_parameter = '';
            }
    
        
            if(isset($registration_data['start_dates']) &&  $registration_data['start_dates'] ){        
                $registered_program_start_dates_month_txt = getTimeDateDisplay($registration_data['user_time_zone_id'],$registration_data['start_dates'], 'd-M-Y' );
            }
    
            if(isset($registration_data['end_dates']) &&  $registration_data['end_dates'] ){        
                $registered_program_end_dates_month_txt = getTimeDateDisplay($registration_data['user_time_zone_id'],$registration_data['end_dates'], 'd-M-Y' );
            }
   
            if(isset($registration_data['payment_last_date']) &&  $registration_data['payment_last_date'] ){        
                $registered_program_payment_last_date_month_txt = getTimeDateDisplay($registration_data['user_time_zone_id'],$registration_data['payment_last_date'],'d-M-Y');
            }
    
    
         
    
    
    
            if (!empty(DB::table('payments')
            ->where('payment_log_id', $registration_data['payment_log_id'])
            ->first())) {
                $payment =   (array) DB::table('payments')
                ->where('payment_log_id', $registration_data['payment_log_id'])
                ->first();
            } else {
                $payment = [];
            }
            
            if(isset($payment['added_datetime']) &&  $payment['added_datetime'] ){        
                $registered_payment_datetime_month_txt = getTimeDateDisplay($registration_data['user_time_zone_id'],$payment['added_datetime'],'d-M-Y h:i:s' , 'Y-m-d H:i:s' );
            }
    
           
            if(isset($registration_data['registration_time']) &&  $registration_data['registration_time'] ){        
                $registered_registration_time_month_txt = getTimeDateDisplay($registration_data['user_time_zone_id'],$registration_data['registration_time'],'d-M-Y h:i:s' , 'Y-m-d H:i:s' );
            }
          
    
            if(isset($country_code) &&  isset( $registration_data['mobile_no']) ){        
                $registered_mobile_no_with_country_code =  $country_code. $registration_data['mobile_no'];
            }
         
    
     
 
             $email_templates_data = (array) DB::table('email_templates')
             ->where('email_template_id',$registration_data['registration_email_template_id'])    
             ->first();       
         
          $replace = array(
                 '{{registered_auto_login_string}}' => $registration_data['auto_login_string'],
                 '{{registered_registration_id}}' => $registration_data['registration_id'],
                 '{{registered_certificate_code}}' => $certificate_code,
                 '{{registered_certificate_print_status}}' => $registration_data['certificate_print_status'],
                 '{{registered_certificate_title}}' => $registration_data['certificate_title'],
                 '{{registered_city}}' => $registration_data['city'],
                 '{{registered_college}}' => $registration_data['college'],
                 '{{registered_country_name}}' => $country_name,
                 '{{registered_country_state_name}}' => $state_name,
                 '{{registered_direct_login_qr_code_url}}' => $registration_data['direct_login_qr_code_url'],
                 '{{registered_direct_login_short_url}}' => $registration_data['direct_login_short_url'],
                 '{{registered_direct_login_url}}' => $registration_data['direct_login_url'],
                 '{{registered_direct_payment_short_url}}' => $registration_data['direct_payment_short_url'],
                 '{{registered_direct_payment_url}}' => $registration_data['direct_payment_url'],
                 '{{registered_email_status}}' => $registration_data['email_status'],
                 '{{registered_email}}' => $registration_data['registered_email'],
                 '{{registered_event_end_date}}' => $registration_data['event_end_date'],
                 '{{registered_event_from_date}}' => $registration_data['event_from_date'],
                 '{{registered_event_name_prefix}}' => $registration_data['event_name_prefix'],
                 '{{registered_event_name_suffix}}' => $registration_data['event_name_suffix'],
                 '{{registered_event_name}}' => $registration_data['event_name'],
                 '{{registered_event_partner_1_association_slug}}' => $registration_data['event_partner_1_association_slug'],
                 '{{registered_event_partner_1_suffix}}' => $registration_data['event_partner_1_suffix'],
                 '{{registered_event_partner_1}}' => $registration_data['event_partner_1'],
                 '{{registered_event_partner_2_association_slug}}' => $registration_data['event_partner_2_association_slug'],
                 '{{registered_event_partner_2_suffix}}' => $registration_data['event_partner_2_suffix'],
                 '{{registered_event_partner_2}}' => $registration_data['event_partner_2'],
                 '{{registered_event_partner_3_association_slug}}' => $registration_data['event_partner_3_association_slug'],
                 '{{registered_event_partner_3_suffix}}' => $registration_data['event_partner_3_suffix'],
                 '{{registered_event_partner_3}}' => $registration_data['event_partner_3'],
                 '{{registered_event_presenting_partner_1_association_slug}}' => $registration_data['event_presenting_partner_1_association_slug'],
                 '{{registered_event_presenting_partner_1}}' => $registration_data['event_presenting_partner_1'],
                 '{{registered_event_presenting_partner_2_association_slug}}' => $registration_data['event_presenting_partner_2_association_slug'],
                 '{{registered_event_presenting_partner_2}}' => $registration_data['event_presenting_partner_2'],
                 '{{registered_first_name}}' => $registration_data['first_name'],
                 '{{registered_ip}}' => $registration_data['ip'],
                 '{{registered_last_date}}' => $registration_data['last_date'],
                 '{{registered_last_name}}' => $registration_data['last_name'],
                 '{{registered_mobile_country_code}}' =>$country_code ,
                 '{{registered_mobile_no}}' => $registration_data['mobile_no'],
                 '{{registered_payment_amount_subunit}}' =>  isset($payments->amount_subunit)  ? $payments->amount_subunit : '',
                 '{{registered_payment_amount}}' => isset($payments->amount)  ? $payments->amount : '' ,
                 '{{registered_payment_currency}}' =>  isset($payments->currency)  ? $payments->currency : ''   ,
                 '{{registered_payment_datetime}}' => isset($payments->added_datetime)  ? $payments->added_datetime : '',
                '{{registered_payment_gateway_name}}' =>  isset($payments->payment_gateway_name)  ? $payments->payment_gateway_name : ''   ,
                 '{{registered_payment_id}}' => isset($payments->payment_id)  ? $payments->payment_id : ''  ,
                 '{{registered_payment_log_id}}' => isset($payments->payment_log_id)  ? $payments->payment_log_id : ''  ,
                 '{{registered_payment_order_id}}' =>  isset($payments->order_id)  ? $payments->order_id : ''  ,
                 '{{registered_payment_status}}' => $payment_status,
                 '{{registered_program_code}}' => $registration_data['program_code'],
                 '{{registered_program_contact_us_email}}' => $registration_data['contact_us_email'],
                 '{{registered_program_contact_us_mobile}}' => $registration_data['contact_us_mobile'],
                 '{{registered_program_duration_time_unit}}' => $registration_data['program_duration_time_unit'],
                 '{{registered_program_duration}}' => $registration_data['program_duration'],
                 '{{registered_program_page_header_banner_image_alt}}' => $registration_data['page_header_banner_image_alt'],
                 '{{registered_program_page_header_banner_image_url}}' => asset($registration_data['page_header_banner_image_url']) ,
                 '{{registered_program_end_dates}}' => $registration_data['end_dates'],
                 '{{registered_program_end_time}}' => $registration_data['end_time'],
                 '{{registered_program_event_backend_name}}' => $registration_data['event_backend_name'],
                 '{{registered_program_event_code}}' => $registration_data['event_code'],
                 '{{registered_program_event_id}}' => $registration_data['event_id'],
                 '{{registered_program_event_program_title}}' => $registration_data['event_program_title'],
                 '{{registered_program_id}}' => $registration_data['program_id'],
                 '{{registered_program_level}}' => $registration_data['level'],
                 '{{registered_program_location_address_city}}' => $registration_data['location_address_city'],
                 '{{registered_program_location_address_geo_latitude}}' => $registration_data['location_address_geo_latitude'],
                 '{{registered_program_location_address_geo_longitude}}' => $registration_data['location_address_geo_longitude'],
                 '{{registered_program_location_address_line1}}' => $registration_data['location_address_line1'],
                 '{{registered_program_location_address_line2}}' => $registration_data['location_address_line2'],
                 '{{registered_program_location_address_zip}}' => $registration_data['location_address_zip'],
                 '{{registered_program_location_country_name}}' => $location_country_name,
                 '{{registered_program_location_location_google_maps_url}}' => $registration_data['location_location_google_maps_url'],
                 '{{registered_program_location_name}}' => $registration_data['location_name'],
                 '{{registered_program_location_state_name}}' => $location_state_name,
                 '{{registered_program_location_sub_location_name}}' => $registration_data['location_sub_location_name'],
                 '{{registered_program_name_for_certificate}}' => $registration_data['program_name_for_certificate'],
                 '{{registered_program_name_sms_dlt}}' => $registration_data['program_name_sms_dlt'],
                 '{{registered_program_name}}' => $registration_data['program_name'],
                 '{{registered_program_payment_last_date}}' => $registration_data['payment_last_date'],
                 '{{registered_program_program_details_page_url}}' => $registration_data['program_details_page_short_url'],
                 '{{registered_program_registration_page_short_url}}' => $registration_data['registration_short_url'],
                 '{{registered_program_registration_page_url}}' => $registered_program_registration_page_url . "/view?id=" . $registration_data['auto_login_string']  . '&express_payment=true&' . $email_templates_data['email_template_link_utm'],
                 '{{registered_program_start_dates}}' => $registration_data['start_dates'],
                 '{{registered_program_start_time}}' => $registration_data['start_time'],
                 
                 
                 '{{registered_program_timezone}}' => $registered_program_timezone,
                 '{{registered_program_timezone_lable}}' => $registered_program_timezone_lable,
                 '{{registered_user_timezone}}' => $registered_user_timezone,
                 '{{registered_user_timezone_lable}}' => $registered_user_timezone_lable,
                 
     
                
                 '{{registered_registration_number}}' => $registration_data['registration_number'],
                 '{{registered_registration_time}}' => $registration_data['registration_time'],
                 '{{registered_seats_count}}' => $registration_data['seats'],
                 '{{registered_shipping_address_city}}' => $registration_data['shipping_address_city'],
                 '{{registered_shipping_address_country_name}}' => $registered_shipping_address_country_name,
                 '{{registered_shipping_address_firstname}}' => $registration_data['shipping_address_firstname'],
                 '{{registered_shipping_address_lastname}}' => $registration_data['shipping_address_lastname'],
                 '{{registered_shipping_address_line_1}}' => $registration_data['shipping_address_line_1'],
                 '{{registered_shipping_address_line_2}}' => $registration_data['shipping_address_line_2'],
                 '{{registered_shipping_address_mobile_country_code}}' =>  $shipping_address_mobile_country_code ,
                 '{{registered_shipping_address_mobile}}' => $registration_data['shipping_address_mobile'],
                 '{{registered_shipping_address_post_code}}' => $registration_data['shipping_address_post_code'],
                 '{{registered_shipping_address_state_name}}' =>  $shipping_address_state_name,
                 '{{registered_student_certificate_status}}' => $student_certificate_status_name,
                 '{{registered_student_invitation_status}}' => $student_invitation_status_name,
                 '{{registered_student_registration_status}}' => $students_registration_status,
                 '{{registered_student_selection_status}}' =>  $student_selection_status_name ,
                 '{{registered_total_fee_all_inclusive}}' => $registration_data['total_fee_all_inclusive'] , 
                 '{{registered_event_program_type_title}}' => $registration_data['event_program_title'] ,
                 '{{registered_amount}}' => $registration_data['amount'] ,
                 '{{registered_tax_amount}}' => $registration_data['tax_amount'] ,
                 '{{registered_payment_gateway_fee}}' => $registration_data['payment_gateway_fee'] ,
                 '{{registered_currency_title}}' => $registration_data['title'] ,
                 '{{registered_currency_code}}' => $registration_data['code'] ,
                 '{{registered_program_details_page_short_url}}' => $registration_data['program_details_page_short_url'] ,
                 '{{registered_program_whatsapp_notification_banner_image}}' => $registration_data['whatsapp_notification_banner_image'] ,
                 '{{registered_program_whatsapp_notification_banner_image_url}}' => asset('assets/images/whatsapp-banners/'.$registration_data['whatsapp_notification_banner_image'] ) ,
              
                 '{{registered_referrer}}' => $registration_data['referrer'],
                 '{{registered_utm_source}}' => $registration_data['utm_source'],
                 '{{registered_utm_medium}}' => $registration_data['utm_medium'],
                 '{{registered_utm_campaign}}' => $registration_data['utm_campaign'],
                 '{{registered_utm_term}}' => $registration_data['utm_term'],
                 '{{registered_utm_content}}' => $registration_data['utm_content'],
                 '{{registered_url_used_for_registration}}' => $registration_data['url_used_for_registration'],
                 '{{registered_classroom_venue_name}}' => $registration_data['venue_name'],



                '{{registered_direct_payment_short_url_parameter}}' => $registered_direct_payment_short_url_parameter ?? '',
                '{{registered_direct_login_short_url_parameter}}' => $registered_direct_login_short_url_parameter ?? '',
                '{{registered_program_details_page_short_url_parameter}}' => $registered_program_details_page_short_url_parameter ?? '',

                '{{registered_program_start_dates_month_txt}}' => $registered_program_start_dates_month_txt ?? '',
                '{{registered_program_end_dates_month_txt}}' => $registered_program_end_dates_month_txt ?? '',
                '{{registered_program_payment_last_date_month_txt}}' => $registered_program_payment_last_date_month_txt ?? '',

                '{{registered_payment_datetime_month_txt}}' => $registered_payment_datetime_month_txt ?? '',
                '{{registered_registration_time_month_txt}}' => $registered_registration_time_month_txt ?? '',
                '{{registered_mobile_no_with_country_code}}' => $registered_mobile_no_with_country_code ?? '',


             
             );
    
           

             return $replace;
             


        }

}
if (!function_exists('shortcode')) {
    function shortcode($registration_number,$message)
        {  
             $replace = getShortcode($registration_number);
 
             $message = replaceTagWithValue($message, $replace);
       
             $message = preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '', $message);
             
             $message = str_replace(array("\r\n", "\r", "\n"), '', $message);

             return $message;
             
        }

}

function replaceTagWithValue($template, $data) { 
    foreach ($data as $key => $value) {        
        $template = str_replace($key , $value, $template);      
    }
    return $template;
}


if (!function_exists('getGeo')) {
    function getGeo($ip)
        {  
            try {			
                $reader = new Reader(base_path('vendor/databases/GeoLite2-City.mmdb'));   
                $record = $reader->get($ip);
                
                $continent = isset($record['continent']['names']['en']) ? trim($record['continent']['names']['en']): 'Others';
                $country = isset($record['country']['names']['en']) ? trim($record['country']['names']['en']): 'Others';
                $state = isset($record['subdivisions'][0]['names']['en']) ? trim($record['subdivisions'][0]['names']['en']): 'Others';
                $country = isset($record['country']['names']['en']) ? trim($record['country']['names']['en']): 'Others';
                $city = isset($record['city']['names']['en']) ? trim($record['city']['names']['en']): 'Others';
                $location = (isset($record['location']) && !empty($record['location'])) ? $record['location']: [];    
    
                $geo = array(
                    'ip_address' => $ip,
                    'continent' => $continent,
                    'country' => $country,
                    'state' =>$state,
                    'city' => $city,
                    'location' => $location
                
                );
    
              
            } catch (Exception $ex) {        
           
                            $geo = array(
                                    'ip_address' =>$ip,
                                    'continent' => 'Others',
                                    'country' => 'Others',
                                    'state' => 'Others',
                                    'city' => 'Others',
                                    'location' => 'Others',
                                    'timezone' => 'Others'
                                );
            }

            return $geo;  
        }

}


if (!function_exists('getDevice')) {
    function getDevice($request)
        { 
            $userAgent = $request->header('User-Agent');

            // OPTIONAL: Set version truncation to none, so full versions will be returned
            // By default only minor versions will be returned (e.g. X.Y)
            // for other options see VERSION_TRUNCATION_* constants in DeviceParserAbstract class
            AbstractDeviceParser::setVersionTruncation(AbstractDeviceParser::VERSION_TRUNCATION_NONE);

            $userAgent = $_SERVER['HTTP_USER_AGENT']; // change this to the useragent you want to parse
            $clientHints = ClientHints::factory($_SERVER); // client hints are optional

            $dd = new DeviceDetector($userAgent, $clientHints);

            $dd->parse();

            // print_r($dd);die;

            if ($dd->isBot()) {
                // handle bots,spiders,crawlers,...
                $botInfo = $dd->getBot();
              } else {
              
                $device = [
                  "browser" => $dd->getClient()['name'].", ".$dd->getClient()['version'],
                  "platform" => $dd->getOs()['name'].", " . $dd->getOs()['platform'],
                  "device_type" => $dd->getDevice(),
                  "device_name" => $dd->getDeviceName(),
                  "device_brand" => $dd->getBrandName(),	
                  "is_mobile" => $dd->isMobile(),
                  "is_tablet" => $dd->isTablet(),
                  "model" => $dd->getModel(),
                  
                ];
              
               
              }

              return $device;
         
        }

}


if (!function_exists('getRegistrationData')) {
    function getRegistrationData($registration_id)
        {  
            $registration_data = (array) DB::table('event_program_registration')
            ->leftJoin('event_program', 'event_program_registration.program_id', "=", 'event_program.program_id')
            ->leftJoin('event_program_location', 'event_program_location.program_location_id', "=", 'event_program_location.program_location_id')
            ->leftJoin('event_program_location_venue', 'event_program_location_venue.program_location_id', "=", 'event_program_location_venue.program_location_id')
            ->leftJoin('mobile_country_list', 'mobile_country_list.mobile_country_code_id', "=", 'event_program_registration.mobile_country_code_id')
            ->leftJoin('country_to_state_code', 'country_to_state_code.country_state_id', "=", 'event_program_registration.country_state_id')
            ->leftJoin('country', 'country.mobile_country_code_id', "=", 'event_program_registration.mobile_country_code_id')
            ->leftJoin('students_certificate_status', 'students_certificate_status.student_certificate_status_id', "=", 'event_program_registration.student_certificate_status_id')
            ->leftJoin('event_program_certificate', 'event_program_certificate.program_certificate_id', "=", 'event_program.program_certificate_id')
            ->leftJoin('students_selection_status', 'students_selection_status.student_selection_status_id', "=", 'event_program_registration.student_selection_status_id')
            ->leftJoin('payment_status', 'payment_status.payment_status_id', "=", 'event_program_registration.payment_status_id')
            ->leftJoin('payment_gateway_config', 'payment_gateway_config.payment_gateway_id', '=', 'event_program.payment_gateway_id')
            ->leftJoin('currency_settings', 'currency_settings.currency_id', '=', 'event_program.currency_id')
            ->leftJoin('payments', 'payments.payment_log_id', '=', 'event_program_registration.payment_log_id')
            ->leftJoin('event', 'event.event_id', '=', 'event_program.event_id')
            ->leftJoin('event_program_type', 'event_program_type.event_program_type_id', '=', 'event_program.event_program_type_id')
            ->leftJoin('event_program_registration_seo_url', 'event_program_registration_seo_url.registration_seo_url_id', '=', 'event_program.registration_seo_url_id')
            ->where('event_program_registration.registration_id', $registration_id)
            ->select(
              'event_program.*',  // Select all columns from event_program
              'event_program_location.*',  // Select all columns from event_program_location
              'event_program_location_venue.*',  // Select all columns from event_program_location_venue
              'mobile_country_list.*',  // Select all columns from mobile_country_list
              'country_to_state_code.*',  // Select all columns from country_to_state_code
              'country.*',  // Select all columns from country
              'students_certificate_status.*',  // Select all columns from students_certificate_status
              'event_program_certificate.*',  // Select all columns from event_program_certificate
              'students_selection_status.*',  // Select all columns from students_selection_status
              'payment_status.*',  // Select all columns from payment_status
              'payment_gateway_config.*',  // Select all columns from payment_gateway_config
              'currency_settings.*',  // Select all columns from currency_settings
              'payments.*',  // Select all columns from payments
              'event.*',  // Select all columns from event
              'event_program_type.*',  // Select all columns from event_program_type
              'event_program_registration_seo_url.*',  // Select all columns from event_program_registration_seo_url
              'event_program_registration.*',  // Select all columns from event_program_registration
              DB::raw(dbPrefix() . 'currency_settings.code'),
            )
            ->first();



            return $registration_data ;
        }

}

if (!function_exists('getCountryCode')) {
    function getCountryCode()
        {  
            $countries =   DB::table('mobile_country_list')->get()->toArray();

            foreach ($countries as $key=> $country) {
              $country->country_code = str_replace('+', '', $country->country_code);
            
           }
          
           return $countries;
        }

}

if (!function_exists('getSeoHandleSetting')) {
    function getSeoHandleSetting($seo_url)
        {  
            $event_seo_handle = (array) DB::table('event_program_registration_seo_url')
            ->where('seo_url',$seo_url)->first();  

            return $event_seo_handle ;
        }

}


if (!function_exists('appendUTM')) {
    function appendUTM($html,$utm_parameters)
        {  
            // Load HTML into DOMDocument
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // To suppress warnings for invalid HTML
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        
        // Get all anchor tags
        $anchors = $dom->getElementsByTagName('a');        
     
        // Iterate through each anchor tag and append UTM parameters
        foreach ($anchors as $anchor) {
            $href = $anchor->getAttribute('href');
        
            // Append UTM parameters to the URL
            $parsed_url = parse_url($href);
            $separator = isset($parsed_url['query']) ? '&' : '?';
            $new_href = $href . $separator . $utm_parameters;
        
            // Update the href attribute
            $anchor->setAttribute('href', $new_href);
        }
        
        // Output the modified HTML
        return html_entity_decode($dom->saveHTML());
        }

}




  
    if (!function_exists('convertUtcToTimeZone')) {
        function convertUtcToTimeZone($utcDateTime, $targetTimeZone)
        {
          $date = new DateTime($utcDateTime, new DateTimeZone('UTC'));
          $date->setTimezone(new DateTimeZone($targetTimeZone));
          return $date->format('Y-m-d H:i:s');
        }
    
    }

    


if (!function_exists('getEmailAlias')) {
    function getEmailAlias($email,$program_id)
        {  
            $alias = explode('@',$email);
            $alias_email = '';
            if(!empty($alias)){
            $alias_email = $alias[0]  . "+" .$program_id. "@" .$alias[1];  
            }
            return $alias_email;
        }

}


if (!function_exists('shortUrl')) {
    function shortUrl($url, $program_id)
        {    

            $program =(array) DB::table('event_program')
            ->where('program_id',$program_id)
            ->first();    

            $short_url_api = (array) DB::table('short_url_api_service')
            ->where('short_url_api_id',$program['short_url_api_id'])
            ->first();  
            
            if(!empty($short_url_api)){
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => $short_url_api['api_url'] . "url/add",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_MAXREDIRS => 2,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_HTTPHEADER => [
                        "Authorization: Bearer ". $short_url_api['api_key'],
                        "Content-Type: application/json",
                    ],
                    CURLOPT_POSTFIELDS => 
                        '{"url": "'.$url.'","channel":"'.$program['short_url_channel_id'].'"}',
                ));
      
                $response = curl_exec($curl);
      
                curl_close($curl);
                $decode_response = json_decode($response,true);
                if(isset($decode_response['error']) && $decode_response['error'] == 0 ){
                    if(isset($decode_response['shorturl'])){
                      return $decode_response['shorturl']; 
                    }
                }else{
                  return 'error occured in short url api';
                }         
      
            }


           
        }

}

if (!function_exists('getTimeDateDisplay')) {
    function getTimeDateDisplay($time_zone_id,$date_time,$view_format,$recieve_format='Y-m-d')
        {    
            $timezone = DB::table('time_zone')
            ->where('time_zone_id', $time_zone_id )                              
            ->first();

            // dd($date_time);
      
            // Create a Carbon instance from the given UTC time
            $utc_date_time = Carbon::createFromFormat($recieve_format,$date_time, 'UTC');    
            // Convert to Asia/Kolkata timezone
            $kolkata_date_time = $utc_date_time->setTimezone($timezone->timezone);    
            // Format the datetime as needed
            $registered_program_end_dates_month_txt = $kolkata_date_time->format($view_format);
           
            return $registered_program_end_dates_month_txt ;
        }

}

if (!function_exists('generate_certificate_code')) {
    function generate_certificate_code()
        {    
            $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            $charactersLength = strlen($characters);
            $certificate_code = '';
            for ($i = 0; $i < 12; $i++) {
              $certificate_code .= $characters[rand(0, $charactersLength - 1)];
            }
            return $certificate_code;
        }

}

if (!function_exists('generate_registration_code')) {
    function generate_registration_code()
        {    
            $characters = 'ABCDEFGHJKLMNOPQRSTUVWXYZ234567890';
            $charactersLength = strlen($characters);
            $registration_code = '';
            for ($i = 0; $i < 8; $i++) {
              $registration_code .= $characters[rand(0, $charactersLength - 1)];
            }
            return $registration_code;
        }

}


if (!function_exists('generate_short_code')) {
    function generate_short_code()
        {    
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                $charactersLength = strlen($characters);
                $short_code = '';
                for ($i = 0; $i < 5; $i++) {
                $short_code .= $characters[rand(0, $charactersLength - 1)];
                }
                return $short_code;
        }

}

if (!function_exists('dbPrefix')) {
    function dbPrefix()
   {    
       return DB::getTablePrefix();
   }

}

if (!function_exists('generateUniqueCode')) {
     function generateUniqueCode($length = 4)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789123456789123456789';
        $charactersLength = strlen($characters);
        $uniqueCode = '';
        for ($i = 0; $i < $length; $i++) {
            $uniqueCode .= $characters[rand(0, $charactersLength - 1)];
        }
        return $uniqueCode;
    }

}


if (!function_exists('setting')) {
    function setting($key)
    {    
        $values = Setting::where('key', $key)->value('value');
        return  $values ?? '';
    }
}


if (!function_exists('theme')) {
    function theme()
    {
        return app(App\Core\Theme::class);
    }
}


if (!function_exists('getName')) {
    /**
     * Get product name
     *
     * @return void
     */
    function getName()
    {
        return config('settings.KT_THEME');
    }
}


if (!function_exists('addHtmlAttribute')) {
    /**
     * Add HTML attributes by scope
     *
     * @param $scope
     * @param $name
     * @param $value
     *
     * @return void
     */
    function addHtmlAttribute($scope, $name, $value)
    {
        theme()->addHtmlAttribute($scope, $name, $value);
    }
}


if (!function_exists('addHtmlAttributes')) {
    /**
     * Add multiple HTML attributes by scope
     *
     * @param $scope
     * @param $attributes
     *
     * @return void
     */
    function addHtmlAttributes($scope, $attributes)
    {
        theme()->addHtmlAttributes($scope, $attributes);
    }
}


if (!function_exists('addHtmlClass')) {
    /**
     * Add HTML class by scope
     *
     * @param $scope
     * @param $value
     *
     * @return void
     */
    function addHtmlClass($scope, $value)
    {
        theme()->addHtmlClass($scope, $value);
    }
}


if (!function_exists('printHtmlAttributes')) {
    /**
     * Print HTML attributes for the HTML template
     *
     * @param $scope
     *
     * @return string
     */
    function printHtmlAttributes($scope)
    {
        return theme()->printHtmlAttributes($scope);
    }
}


if (!function_exists('printHtmlClasses')) {
    /**
     * Print HTML classes for the HTML template
     *
     * @param $scope
     * @param $full
     *
     * @return string
     */
    function printHtmlClasses($scope, $full = true)
    {
        return theme()->printHtmlClasses($scope, $full);
    }
}


if (!function_exists('getSvgIcon')) {
    /**
     * Get SVG icon content
     *
     * @param $path
     * @param $classNames
     * @param $folder
     *
     * @return string
     */
    function getSvgIcon($path, $classNames = 'svg-icon', $folder = 'assets/media/icons/')
    {
        return theme()->getSvgIcon($path, $classNames, $folder);
    }
}


if (!function_exists('setModeSwitch')) {
    /**
     * Set dark mode enabled status
     *
     * @param $flag
     *
     * @return void
     */
    function setModeSwitch($flag)
    {
        theme()->setModeSwitch($flag);
    }
}


if (!function_exists('isModeSwitchEnabled')) {
    /**
     * Check dark mode status
     *
     * @return void
     */
    function isModeSwitchEnabled()
    {
        return theme()->isModeSwitchEnabled();
    }
}


if (!function_exists('setModeDefault')) {
    /**
     * Set the mode to dark or light
     *
     * @param $mode
     *
     * @return void
     */
    function setModeDefault($mode)
    {
        theme()->setModeDefault($mode);
    }
}


if (!function_exists('getModeDefault')) {
    /**
     * Get current mode
     *
     * @return void
     */
    function getModeDefault()
    {
        return theme()->getModeDefault();
    }
}


if (!function_exists('setDirection')) {
    /**
     * Set style direction
     *
     * @param $direction
     *
     * @return void
     */
    function setDirection($direction)
    {
        theme()->setDirection($direction);
    }
}


if (!function_exists('getDirection')) {
    /**
     * Get style direction
     *
     * @return void
     */
    function getDirection()
    {
        return theme()->getDirection();
    }
}


if (!function_exists('isRtlDirection')) {
    /**
     * Check if style direction is RTL
     *
     * @return void
     */
    function isRtlDirection()
    {
        return theme()->isRtlDirection();
    }
}


if (!function_exists('extendCssFilename')) {
    /**
     * Extend CSS file name with RTL or dark mode
     *
     * @param $path
     *
     * @return void
     */
    function extendCssFilename($path)
    {
        return theme()->extendCssFilename($path);
    }
}


if (!function_exists('includeFavicon')) {
    /**
     * Include favicon from settings
     *
     * @return string
     */
    function includeFavicon()
    {
        return theme()->includeFavicon();
    }
}


if (!function_exists('includeFonts')) {
    /**
     * Include the fonts from settings
     *
     * @return string
     */
    function includeFonts()
    {
        return theme()->includeFonts();
    }
}


if (!function_exists('getGlobalAssets')) {
    /**
     * Get the global assets
     *
     * @param $type
     *
     * @return array
     */
    function getGlobalAssets($type = 'js')
    {
        return theme()->getGlobalAssets($type);
    }
}


if (!function_exists('addVendors')) {
    /**
     * Add multiple vendors to the page by name. Refer to settings KT_THEME_VENDORS
     *
     * @param $vendors
     *
     * @return void
     */
    function addVendors($vendors)
    {
        theme()->addVendors($vendors);
    }
}


if (!function_exists('addVendor')) {
    /**
     * Add single vendor to the page by name. Refer to settings KT_THEME_VENDORS
     *
     * @param $vendor
     *
     * @return void
     */
    function addVendor($vendor)
    {
        theme()->addVendor($vendor);
    }
}


if (!function_exists('addJavascriptFile')) {
    /**
     * Add custom javascript file to the page
     *
     * @param $file
     *
     * @return void
     */
    function addJavascriptFile($file)
    {
        theme()->addJavascriptFile($file);
    }
}


if (!function_exists('addCssFile')) {
    /**
     * Add custom CSS file to the page
     *
     * @param $file
     *
     * @return void
     */
    function addCssFile($file)
    {
        theme()->addCssFile($file);
    }
}


if (!function_exists('getVendors')) {
    /**
     * Get vendor files from settings. Refer to settings KT_THEME_VENDORS
     *
     * @param $type
     *
     * @return array
     */
    function getVendors($type)
    {
        return theme()->getVendors($type);
    }
}


if (!function_exists('getCustomJs')) {
    /**
     * Get custom js files from the settings
     *
     * @return array
     */
    function getCustomJs()
    {
        return theme()->getCustomJs();
    }
}


if (!function_exists('getCustomCss')) {
    /**
     * Get custom css files from the settings
     *
     * @return array
     */
    function getCustomCss()
    {
        return theme()->getCustomCss();
    }
}


if (!function_exists('getHtmlAttribute')) {
    /**
     * Get HTML attribute based on the scope
     *
     * @param $scope
     * @param $attribute
     *
     * @return array
     */
    function getHtmlAttribute($scope, $attribute)
    {
        return theme()->getHtmlAttribute($scope, $attribute);
    }
}


if (!function_exists('isUrl')) {
    /**
     * Get HTML attribute based on the scope
     *
     * @param $url
     *
     * @return mixed
     */
    function isUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}


if (!function_exists('image')) {
    /**
     * Get image url by path
     *
     * @param $path
     *
     * @return string
     */
    function image($path)
    {
        return asset('assets/media/'.$path);
    }
}


if (!function_exists('getIcon')) {
    /**
     * Get icon
     *
     * @param $path
     *
     * @return string
     */
    function getIcon($name, $class = '', $type = '', $tag = 'span')
    {
        return theme()->getIcon($name, $class, $type, $tag);
    }
}
