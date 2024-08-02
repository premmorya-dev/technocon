<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\StudentRegistrationModel;
use App\Models\ProgramModel;
use App\Models\NotificationJobQueue;
use App\Models\WebhookJobQueue;
use Carbon\Carbon;


class AjaxController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function  getSeat(Request $request)
    {
        $program =  (array) DB::table('event_program')
            ->where('program_id', $request['program_id'])
            ->select('max_member')->first();



        if (isset($program['max_member']) && $program['max_member']) {
            $count = $program['max_member'];
        } else {
            $count = 1;
        }
        // $count = 5;
        $html = '';
        $html .= "<option value=''>[Select Seats]</option>";
        if ($count == 1) {
            $html .= "<option value='1' selected >1</option>";
        } else if ($count > 1) {
            for ($i = 1; $i <= $count; $i++) {
                $html .= "<option value='" . $i . "'>" . $i . "</option>";
            }
        }

        return response()->json(['success' => true, 'html' => $html, 'count' => $count]);
    }

    public function  addStudentRegistration(Request $request)
    {
       
       
        $request->seats = $request->seats ?? 1;
        $errors = [];


        $location = getGeo($_SERVER['REMOTE_ADDR']);
        $device = getDevice($request);
        $program = ProgramModel::findOrFail($request->program_id);

        try {
            $request->validate([
                'email_id' => ['required', 'email',   function ($attribute, $value, $fail) use ($request) {

                    $program_user =   DB::table('event_program_registration')
                        ->where('program_id', $request->program_id)
                        ->where('registered_email', $request->email_id)
                        ->first();
                    if (!empty($program_user)) {
                        $fail('The user already registered to this program.');
                    }
                }],
            ]);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return response()->json(
                [
                    'error' => 1,
                    'errors' => $errors
                ]
                , 200);
        }



        $currency_settings = DB::table('currency_settings')
            ->where('currency_id', $program->currency_id)
            ->first();

        if ($program) {
            if (isset($program['fees_inclusive_tax']) && $program['fees_inclusive_tax'] == 'N') {
                $total_tax = round((($program['fees'] * $request->seats) * ($program['tax_rate'] / 100)), $currency_settings->decimal_place);
                $workshopfee = round(($program['fees'] * $request->seats), $currency_settings->decimal_place);
                $fees_inclusive_tax = round(($workshopfee + $total_tax), $currency_settings->decimal_place);
                $payment_gateway_fee = round(($fees_inclusive_tax * $program['payment_gateway_fee_rate'] / 100), $currency_settings->decimal_place);
                $total_fee_all_inclusive_tax = round(($workshopfee + $total_tax + $payment_gateway_fee), $currency_settings->decimal_place);
            } else {
                $total_tax = round(($program['fees'] * $request->seats) - (($program['fees'] * $request->seats) / (1 + ($program['tax_rate'] / 100))), $currency_settings->decimal_place);
                $workshopfee = round((($program['fees'] * $request->seats) - $total_tax), $currency_settings->decimal_place);
                $payment_gateway_fee = round((($program['fees'] * $request->seats) * $program['payment_gateway_fee_rate'] / 100), $currency_settings->decimal_place);
                $total_fee_all_inclusive_tax = round((($program['fees'] * $request->seats) + $payment_gateway_fee), $currency_settings->decimal_place);
            }
        }

        if (!isset($program['registration_no_prefix']) || empty(trim($program['registration_no_prefix']))  || !trim($program['registration_no_prefix'])) {
            $registration_number = generate_registration_code();
        } else {
            $registration_number = $program['registration_no_prefix']  . generate_registration_code();
        }

        $dttime = strtotime('now');
        $auto_login_string = md5('workshop_registration_' . $dttime . $registration_number);

        $generate_short_code = generate_short_code();
        $generate_certificate_code = generate_certificate_code();


        $direct_login_url = url('/event/registered/'.$request->seo_handle . "/view?id=".$auto_login_string);

        $direct_payment_url =  url('/event/registered/'.$request->seo_handle . "/view?id=".$auto_login_string) . "&express_payment=true";


        $direct_login_short_url = shortUrl($direct_login_url, $request->program_id);
        $direct_payment_short_url = shortUrl($direct_payment_url, $request->program_id);

        $direct_login_qr_code_url = $direct_login_short_url . '/qr';

        $email_alias = getEmailAlias(trim($request->email_id), $program['workshop_code']);

        $registration_update_url_on_zoik_app = $program['registration_page_url'] . "w.php?id=" . $auto_login_string . "&update_zoik_app_data=true";
        $registration_time = Carbon::now('UTC')->format('Y-m-d h:i:s');


        $event_program_certificate =  (array) DB::table('event_program_certificate')
            ->where('program_certificate_id', $program->program_certificate_id)
            ->first();

        if (!isset($location['location']['latitude'])  ||  !isset($location['location']['longitude'])) {
            $location_cordinate = "";
        } else {
            $location_cordinate = "Lat: " . $location['location']['latitude'] . ", Long: " .  $location['location']['longitude'];
        }


        if (!isset($request->state_id) || $request->state_id == 0  ||  !$request->state_id) {
            $state_id =  -1;
        } else {
            $state_id =  $request->state_id;
        }

        if (!isset($request->country_id) || $request->country_id == 0  ||  !$request->country_id) {
            $country_id =  -1;
        } else {
            $country_id =  $request->country_id;
        }

        if (!isset($request->mobile_country_code_id) || $request->mobile_country_code_id == 0  ||  !$request->mobile_country_code_id) {
            $mobile_country_code_id =  -1;
        } else {
            $mobile_country_code_id =  $request->mobile_country_code_id;
        }

        // Convert the array to JSON
        $registration_outgoing_webhooks  = json_encode($program['registration_outgoing_webhooks']);

        // Remove newlines and extra whitespace
        $registration_outgoing_webhooks = str_replace(["\r", "\n", "\t", " "], '', $registration_outgoing_webhooks);

        // Decode the JSON string back to a PHP array
        $registration_outgoing_webhooks = json_decode(json_decode($registration_outgoing_webhooks, true), true);


        $form_setting =   getSeoHandleSetting($request->seo_handle );
        $registration_success_url = url('/event/registered/'.$request->seo_handle . "/view?id=".$auto_login_string . "&{$form_setting['registration_success_event_url_parameter']}={$form_setting['registration_success_event_url_parameter_value']}");
        if (empty($error)) {


            try {


                DB::beginTransaction();
                
               $timezone =  isset($location['location']['time_zone']) ?  $location['location']['time_zone'] : '';


               $timezone_id =   DB::table('time_zone')
                                ->where('timezone', $timezone )                              
                                ->first();

                               

                $student_registration = StudentRegistrationModel::create([
                    'user_time_zone_id' => isset($timezone_id->time_zone_id) ? $timezone_id->time_zone_id : 28,
                    'program_id' => $request->program_id,
                    'first_name' => trim(ucwords($request->first_name)),
                    'last_name' => ucwords($request->last_name) ?? '',
                    'registered_email' => strtolower(trim($request->email_id)),
                    'mobile_country_code_id' => $mobile_country_code_id,
                    'mobile_no' => trim($request->mobile),
                    'college' => trim($request->college_name) ?? '',
                    'city' => trim($request->city) ?? '',
                    'country_id' => $country_id,
                    'country_state_id' =>  $state_id,
                    'registration_number' => trim($registration_number),
                    'auto_login_string' => $auto_login_string,
                    'direct_login_url' => $direct_login_url,
                    'direct_login_short_url' => $direct_login_short_url,
                    'direct_login_qr_code_url' => $direct_login_qr_code_url,
                    'direct_payment_url' => $direct_payment_url,
                    'direct_payment_short_url' => $direct_payment_short_url,
                    'seats' => $request->seats ?? 1,
                    'amount' => $workshopfee,
                    'tax_amount' => $total_tax,
                    'payment_gateway_fee' => $payment_gateway_fee,
                    'total_fee_all_inclusive' => $total_fee_all_inclusive_tax,
                    'payment_status_id' =>  $program['payment_status_after_registration'],
                    'student_selection_status_id' => $program['selection_status_after_registration'],
                    'student_invitation_status_id' => $program['invitation_status_after_registration'],
                    'last_date' =>  $program['payment_last_date'],
                    'classroom_venue_id' => NULL,
                    'student_certificate_status_id' => $program['certificate_status_after_registration'],
                    'certificate_print_status' => 'pending',
                
                    'referrer' => $request->referrer ?? '',
                    'utm_source' => $request->utm_source ?? '',
                    'utm_medium' => $request->utm_medium ?? '',
                    'utm_campaign' => $request->utm_campaign ?? '',
                    'utm_term' => $request->utm_term ?? '',
                    'utm_content' => $request->utm_content ?? '',
                    'url_used_for_registration' => $_SERVER['HTTP_REFERER'],
                    'registration_time' => $registration_time,
                    'last_update_datetime' => $registration_time,
                    'student_registration_status_id' => 1,
                    'email_status' => 'active',
                    'email_bounce_log' => '',
                    'email_bounce_datetime' => Null,


                    'shipping_address_firstname' => '',
                    'shipping_address_lastname' => '',
                    'shipping_address_line_1' => '',
                    'shipping_address_line_2' => '',
                    'shipping_address_city' => '',
                    'shipping_address_state_id' => -1,
                    'shipping_address_country_id' => -1,
                    'shipping_address_post_code' => '',
                    'shipping_address_mobile' => '',
                    'shipping_address_mobile_country_code_id' => -1,




                    'ip' => $location['ip_address']  ?? '',
                    'user_agent' =>   $_SERVER['HTTP_USER_AGENT'] ?? '',
                    'geo_continent' => $location['continent'] ?? '',
                    'geo_country' => $location['country'] ?? '',
                    'geo_state' => $location['state'] ?? '',
                    'geo_city' => $location['city'] ?? '',
                    'geo_language' => '',
                    'geo_location' => $location_cordinate,
                    'geo_timezone' => $timezone,
                    'browser' => $device['browser'] ?? '',
                    'platform' => $device['platform'] ?? '',
                    'device_type' => $device['device_type']  ?? '',
                    'device_brand' => $device['device_brand']  ?? '',
                    'device_name' => $device['device_name']   ?? '',
                    'is_mobile' => $device['is_mobile']  ??  '',
                    'is_tablet' => $device['is_tablet']  ?? '',



                ]);
                   
            
                NotificationJobQueue::create([
                    'notification_job_queue_type' => 'registration-email',
                    'notification_enabled' =>  $program['email_notification_on_registration'],
                    'registration_id' => $student_registration->registration_id,
                    'notification_status' => 'pending',
                    'added_datetime' =>  Carbon::now('UTC')->format('Y-m-d h:i:s'),
                    'queue_process_start_datetime' => NULL,
                    'queue_process_end_datetime' => NULL,
                    'notification_log' => '',
                ]);

                NotificationJobQueue::create([
                    'notification_job_queue_type' => 'registration-sms',
                    'notification_enabled' =>  $program['sms_notification_on_registration'],
                    'registration_id' => $student_registration->registration_id,
                    'notification_status' => 'pending',
                    'added_datetime' =>  Carbon::now('UTC')->format('Y-m-d h:i:s'),
                    'queue_process_start_datetime' => NULL,
                    'queue_process_end_datetime' => NULL,
                    'notification_log' => '',
                ]);


                NotificationJobQueue::create([
                    'notification_job_queue_type' => 'registration-whatsapp',
                    'notification_enabled' =>  $program['whatsapp_notification_on_registration'],
                    'registration_id' => $student_registration->registration_id,
                    'notification_status' => 'pending',
                    'added_datetime' =>  Carbon::now('UTC')->format('Y-m-d h:i:s'),
                    'queue_process_start_datetime' => NULL,
                    'queue_process_end_datetime' => NULL,
                    'notification_log' => '',
                ]);

                NotificationJobQueue::create([
                    'notification_job_queue_type' => 'zoikmail-common-list-sync',
                    'notification_enabled' =>  $program['zoik_app_common_list_sync'],
                    'registration_id' => $student_registration->registration_id,
                    'notification_status' => 'pending',
                    'added_datetime' =>  Carbon::now('UTC')->format('Y-m-d h:i:s'),
                    'queue_process_start_datetime' => NULL,
                    'queue_process_end_datetime' => NULL,
                    'notification_log' => '',
                ]);

                
                NotificationJobQueue::create([
                    'notification_job_queue_type' => 'zoikmail-program-list-sync',
                    'notification_enabled' =>  $program['zoik_app_program_list_sync'],
                    'registration_id' => $student_registration->registration_id,
                    'notification_status' => 'pending',
                    'added_datetime' =>  Carbon::now('UTC')->format('Y-m-d h:i:s'),
                    'queue_process_start_datetime' => NULL,
                    'queue_process_end_datetime' => NULL,
                    'notification_log' => '',
                ]);



                if (!empty($registration_outgoing_webhooks)) {

                    foreach ($registration_outgoing_webhooks as $key => $webhook) {
                       if(!empty($webhook)){
                                WebhookJobQueue::create([
                                    'outgoing_webhook_job_queue_content_type' => $webhook['content-type'],
                                    'webhook_header_json' => json_encode($webhook['header']),
                                    'registration_id' => $student_registration->registration_id,
                                    'webhook_url' => $webhook['url'],
                                    'webhook_enabled' => $webhook['status'],
                                    'webhook_status' => 'pending',
                                    'added_datetime' => Carbon::now('UTC')->format('Y-m-d h:i:s'),
                                    'queue_process_start_datetime' => NULL,
                                    'queue_process_end_datetime' => NULL,
                                    'webhook_log' => '',
                                ]);
                       }
                      
                    }
                }

                DB::commit();

                session(['registration_success' => 'true']);
                
                return response()->json([
                    'error' => 0,
                    'message' => 'Student registration successfully',
                    'redirect' => $registration_success_url,

                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'error' => 1,
                    'message' => 'Student registration failed due to ' . $e->getMessage(),
                ], 400);
            }
        }
    }




    public function updateShippingAddress(Request $request)
    {

        try {
            DB::beginTransaction();

            $registration =  (array) DB::table('event_program_registration')
                ->where('auto_login_string', $request->uid)
                ->first();

            $student_registration = StudentRegistrationModel::findOrFail($registration['registration_id']);
            $student_registration->shipping_address_firstname =  $request->shipping_address_firstname ?? '';
            $student_registration->shipping_address_lastname = $request->shipping_address_lastname ?? '';
            $student_registration->shipping_address_line_1 = $request->shipping_address_line_1  ?? '';
            $student_registration->shipping_address_line_2 = $request->shipping_address_line_2 ?? ' ';
            $student_registration->shipping_address_city = $request->shipping_address_city  ?? '';
            $student_registration->shipping_address_state_id = $request->shipping_address_state_id  ?? '';
            $student_registration->shipping_address_country_id = $request->shipping_address_country_id  ?? '';
            $student_registration->shipping_address_post_code = $request->shipping_address_post_code  ?? '';
            $student_registration->shipping_address_mobile = $request->shipping_address_mobile  ?? '';
            $student_registration->shipping_address_mobile_country_code_id = $request->shipping_address_mobile_country_code_id  ??  '';

            $student_registration->save();

            DB::commit();
            session()->flash('success', 'Shipping Address Updated Successfully.');
            return response()->json([
                'error' => 0,
                'message' => 'Shipping Address Updated Successfully'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 1,
                'message' => 'Shipping Address Updated failed due to ' . $e->getMessage(),
            ], 400);
        }
    }


    public function updateRegistration(Request $request)
    {

        try {
            DB::beginTransaction();

           

            $registration =  (array) DB::table('event_program_registration')
            ->leftJoin('event_program', 'event_program_registration.program_id', "=", 'event_program.program_id')                          
            ->leftJoin('event_program_registration_seo_url', 'event_program_registration_seo_url.registration_seo_url_id', '=', 'event_program.registration_seo_url_id')
            ->where('event_program_registration.auto_login_string', $request->uid)
            ->first();

                

            $student_registration = StudentRegistrationModel::findOrFail($registration['registration_id']);
            $student_registration->first_name =  $request->first_name ?? '';
            $student_registration->last_name = $request->last_name ?? '';
            $student_registration->mobile_country_code_id = $request->mobile_country_code_id  ?? '';
            $student_registration->mobile_no = $request->mobile ?? '';
            $student_registration->college = $request->college  ?? '';
            $student_registration->city = $request->city  ?? '';
            $student_registration->country_state_id = $request->state_id  ?? -1;
            $student_registration->country_id = $request->country_id  ?? -1;
            $student_registration->save();


            NotificationJobQueue::create([
                'notification_job_queue_type' => 'zoikmail-program-list-sync',
                'notification_enabled' =>  $registration['zoik_app_program_list_sync'],
                'registration_id' => $registration['registration_id'],
                'notification_status' => 'pending',
                'added_datetime' =>  Carbon::now('UTC')->format('Y-m-d h:i:s'),
                'queue_process_start_datetime' => NULL,
                'queue_process_end_datetime' => NULL,
                'notification_log' => '',
            ]);


            NotificationJobQueue::create([
                'notification_job_queue_type' => 'zoikmail-common-list-sync',
                'notification_enabled' =>  $registration['zoik_app_program_list_sync'],
                'registration_id' => $registration['registration_id'],
                'notification_status' => 'pending',
                'added_datetime' =>  Carbon::now('UTC')->format('Y-m-d h:i:s'),
                'queue_process_start_datetime' => NULL,
                'queue_process_end_datetime' => NULL,
                'notification_log' => '',
            ]);


            DB::commit();
            session()->flash('success', 'Registration Updated Successfully.');
           
            return response()->json([
                'error' => 0,
                'message' => 'Registration Updated Successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => 1,
                'message' => 'Registration Updated failed due to ' . $e->getMessage(),
            ], 400);

        }
    }
}
