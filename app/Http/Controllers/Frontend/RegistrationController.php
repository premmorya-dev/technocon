<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentRegistrationModel;
use App\Models\PaymentsModel;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Models\NotificationJobQueue;
use Illuminate\Support\Facades\Log;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function  seo_handle_index($seo_handle)
    {
        $data['event_program_max_count'] = 'hide_seat';
        $data['form_setting'] = getSeoHandleSetting($seo_handle);
       
        $data['program_tiles'] = DB::table('event_program')
        ->leftJoin('event_program_registration_seo_url',"event_program_registration_seo_url.registration_seo_url_id","=","event_program.registration_seo_url_id")
       
        ->leftJoin('event_program_type', 'event_program_type.event_program_type_id', '=', 'event_program.event_program_type_id')

        ->leftJoin('event_program_certificate', 'event_program_certificate.program_certificate_id', '=', 'event_program.program_certificate_id')


        ->leftJoin('event_program_location', 'event_program_location.program_location_id', "=", 'event_program_location.program_location_id')
        ->where("event_program_registration_seo_url.seo_url",$seo_handle)
        ->get()
        ->toArray();

        // dd( $data['form_setting']);




           
        if ($data['program_tiles']) {
            foreach ($data['program_tiles'] as $key => $value) {               
                $data['program_tiles'][$key]->start_dates  = getTimeDateDisplay($value->time_zone_id,$value->start_dates, 'd-M-Y' );
                $data['program_tiles'][$key]->end_dates  = getTimeDateDisplay($value->time_zone_id,$value->end_dates, 'd-M-Y' );               
            }
        }

       
        if (!isset($data['form_setting']) || empty($data['form_setting'])) {
            abort(404);
        }

        $data['event_program'] = DB::table('event_program')
            ->where('registration_seo_url_id', $data['form_setting']['registration_seo_url_id'])
            ->where('status', "1")->get();
        if ($data['event_program']) {
            foreach ($data['event_program'] as $key => $value) {
                $data['event_program'][$key]->start_dates  = getTimeDateDisplay($value->time_zone_id,$value->start_dates, 'd-M-Y' );
                if ($value->max_member > 1) {
                    $data['event_program_max_count'] = 'show_seat';
                }
            }
        }

        if (isset($data['form_setting']['status']) && $data['form_setting']['status'] == 'inactive') {
            abort(404);
        } else if (isset($data['form_setting']['status'])  &&  $data['form_setting']['status'] == 'redirect'  && !empty($data['form_setting']['redirect_url'])) {
            return redirect()->away($data['form_setting']['redirect_url'])->setStatusCode(301);
        }

        $data['form_setting']['page_url'] = url("/event/registration/" . $data['form_setting']['seo_url']);
        // dd($data);


        $data['country'] = DB::table('mobile_country_list')->get();
        $data['mobile_country_list'] = getCountryCode();
        // $data['dselect_library_status'] =  str_contains($_SERVER['REQUEST_URI'],'/event/registration/') ;
        $data['country_to_state_code'] = DB::table('country_to_state_code')->get();

        $data['og_url'] = url('/') . "/event/registration/" . $data['form_setting']['seo_url'];


        if( isset($data['form_setting']['support_contacts_json']) ){
            $data['form_setting']['support_contacts_json']  = json_decode($data['form_setting']['support_contacts_json'],true);
        }

        return view('frontend/registration', compact('data'));
    }

    public function  workshop_code_handle_index($seo_handle, $workshop_code)
    {

        dd($seo_handle);

        $data['event_program'] = DB::table('event_program')->get();
        return view('frontend/registration');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function registered(Request $request, $seo_handle)
    {
                $data['registration_data'] =  (array) DB::table('event_program_registration')
                ->leftJoin('event_program', 'event_program_registration.program_id', '=', 'event_program.program_id')
                ->leftJoin('event_program_location', 'event_program_location.program_location_id', '=', 'event_program_location.program_location_id')
                ->leftJoin('event_program_location_venue', 'event_program_location_venue.classroom_venue_id', '=', 'event_program_registration.classroom_venue_id')
                ->leftJoin('mobile_country_list', 'mobile_country_list.mobile_country_code_id', '=', 'event_program_registration.mobile_country_code_id')
                ->leftJoin('country_to_state_code', 'country_to_state_code.country_state_id', '=', 'event_program_registration.country_state_id')
                ->leftJoin('country', 'country.mobile_country_code_id', '=', 'event_program_registration.mobile_country_code_id')
                ->leftJoin('students_certificate_status', 'students_certificate_status.student_certificate_status_id', '=', 'event_program_registration.student_certificate_status_id')
                ->leftJoin('event_program_certificate', 'event_program_certificate.program_certificate_id', '=', 'event_program.program_certificate_id')
                ->leftJoin('students_selection_status', 'students_selection_status.student_selection_status_id', '=', 'event_program_registration.student_selection_status_id')
                ->leftJoin('payment_status', 'payment_status.payment_status_id', '=', 'event_program_registration.payment_status_id')
                ->leftJoin('payment_gateway_config', 'payment_gateway_config.payment_gateway_id', '=', 'event_program.payment_gateway_id')
                ->leftJoin('currency_settings', 'currency_settings.currency_id', '=', 'event_program.currency_id')
                ->leftJoin('payments', 'payments.payment_log_id', '=', 'event_program_registration.payment_log_id')
                ->leftJoin('event', 'event.event_id', '=', 'event_program.event_id')
                ->leftJoin('event_program_type', 'event_program_type.event_program_type_id', '=', 'event_program.event_program_type_id')
                ->leftJoin('event_program_registration_seo_url', 'event_program_registration_seo_url.registration_seo_url_id', '=', 'event_program.registration_seo_url_id')
                ->where('auto_login_string', $request->id)
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
                    DB::raw(dbPrefix() . 'currency_settings.code')
                )
                ->first();

            $location_country = [];
            $location_country_state = [];

            // Validate and fetch location country
            if (isset($data['registration_data']['location_country_id'])) {
                $location_country = (array) DB::table('country')
                    ->where('mobile_country_code_id', $data['registration_data']['location_country_id'])
                    ->first();
            }

            // Validate and decode support contacts JSON
            $data['registration_data']['support_contacts_json'] = isset($data['registration_data']['support_contacts_json']) ? 
                json_decode($data['registration_data']['support_contacts_json'], true) ?? '' : '';

            // Validate and set location country
            $data['registration_data']['location_country'] = isset($location_country['country_name']) ? $location_country['country_name'] : '';

            // Validate and fetch location state
            if (isset($data['registration_data']['location_state_id'])) {
                $location_country_state = (array) DB::table('country_to_state_code')
                    ->where('country_state_id', $data['registration_data']['location_state_id'])
                    ->first();
            }

            // Validate and set location state
            $data['registration_data']['location_state'] = isset($location_country_state['state_name']) ? $location_country_state['state_name'] : '';

            // Validate and fetch user time zone
            if (isset($data['registration_data']['user_time_zone_id'])) {
                $timezone_id = DB::table('time_zone')
                    ->where('time_zone_id', $data['registration_data']['user_time_zone_id'])
                    ->first();

                if ($timezone_id) {
                    $utc_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $data['registration_data']['registration_time'], 'UTC');
                    $user_date_time = $utc_date_time->setTimezone($timezone_id->timezone);
                    $data['registration_data']['registration_time'] = $user_date_time->format('d-M-Y H:i:s');
                } else {
                    $data['registration_data']['registration_time'] = '';
                }
            } else {
                $data['registration_data']['registration_time'] = '';
            }

            // Validate and set payment last date
            $data['registration_data']['payment_last_date'] = isset($data['registration_data']['user_time_zone_id']) && isset($data['registration_data']['payment_last_date']) ? 
                getTimeDateDisplay($data['registration_data']['user_time_zone_id'], $data['registration_data']['payment_last_date'], 'd-M-Y') : '';

            // Validate and set start dates
            $data['registration_data']['start_dates'] = isset($data['registration_data']['time_zone_id']) && isset($data['registration_data']['start_dates']) ? 
                getTimeDateDisplay($data['registration_data']['user_time_zone_id'], $data['registration_data']['start_dates'], 'd-M-Y') : '';

            // Validate and set end dates
            $data['registration_data']['end_dates'] = isset($data['registration_data']['time_zone_id']) && isset($data['registration_data']['end_dates']) ? 
                getTimeDateDisplay($data['registration_data']['user_time_zone_id'], $data['registration_data']['end_dates'], 'd-M-Y') : '';

            // Validate and fetch program time zone
            if (isset($data['registration_data']['time_zone_id'])) {
                $time_zone = DB::table('time_zone')
                    ->where('time_zone_id', $data['registration_data']['time_zone_id'])
                    ->first();
                
                $data['registration_data']['program_time_zone'] = $time_zone && isset($time_zone->timezone_lable) ? $time_zone->timezone_lable : '';
            } else {
                $data['registration_data']['program_time_zone'] = '';
            }

            // Fetch students selection status
            $data['students_selection_status'] = DB::table('students_selection_status')->get();

        

            if (isset($data['registration_data']) && is_array($data['registration_data'])) {
                if (isset($data['registration_data']['configuration']) && !empty($data['registration_data']['configuration'])) {
                    $configuration = explode(PHP_EOL, $data['registration_data']['configuration']);
                    if (isset($configuration[0]) && isset($configuration[1])) {
                        $key1 = explode(':', $configuration[0]);
                        $secrte = explode(':', $configuration[1]);
                        if (isset($key1[1]) && isset($secrte[1])) {
                            $keyId = trim($key1[1]);
                            $keySecret = trim($secrte[1]);
                            $api = new Api($keyId, $keySecret);
                            $orderData = [
                                'receipt' => $data['registration_data']['registration_number'] ?? '' ,
                                'amount' => (int) $data['registration_data']['total_fee_all_inclusive'] * 100,
                                'currency' => $data['registration_data']['code'] ?? 'INR',
                                'payment_capture' => 1
                            ];
                
                            $razorpayOrder = $api->order->create($orderData);
                            $data['razorpay_order_id'] = $razorpayOrder['id'] ?? '' ;
                        } 
                    } 
                } 

            } 


            // $configuration = explode(PHP_EOL, $configuration);
            // $key1 = explode(':', $configuration[0]);
            // $secrte = explode(':', $configuration[1]);
            // $keyId = trim($key1[1]);
            // $keySecret = trim($secrte[1]);

            // $api = new Api($keyId, $keySecret);
            // $orderData = [
            //     'receipt' => $data['registration_data']['registration_number'] ?? '' ,
            //     'amount' => (int) $data['registration_data']['total_fee_all_inclusive'] ?? 1 * 100,
            //     'currency' => $data['registration_data']['code'] ?? 'INR',
            //     'payment_capture' => 1
            // ];

            // $razorpayOrder = $api->order->create($orderData);
            // $data['razorpay_order_id'] = $razorpayOrder['id'] ?? '' ;

            $data['event_program_max_count'] = 'hide_seat';
            $data['event_program'] = DB::table('event_program')->get();
            if ($data['event_program']) {
                foreach ($data['event_program'] as $key => $value) {
                    if ($value->max_member > 1) {
                        $data['event_program_max_count'] = 'show_seat';
                    }
                }
            }

            // Fetch form settings and country data
            $data['form_setting'] = getSeoHandleSetting($seo_handle);
            $data['country'] = DB::table('mobile_country_list')->get();
            $data['mobile_country_list'] = getCountryCode();

            // Process registration success event URL parameter and script
            $data['registration_success_event_url_parameter'] = request($data['form_setting']['registration_success_event_url_parameter'] ?? '', '');
            $data['form_setting']['registration_success_event_script'] = shortcode($data['registration_data']['registration_number'] ?? 0, $data['form_setting']['registration_success_event_script'] ?? '');
            $data['og_url'] = url('/') . "/event/registration/" . $data['form_setting']['seo_url'] ?? '';

            // Fetch country to state code
            $data['country_to_state_code'] = DB::table('country_to_state_code')->get();

            return view('frontend/registered', compact('data'));

    }


    public function shippingAddressView(Request $request)
    {

        $data['country'] = DB::table('mobile_country_list')->get();
        $data['mobile_country_list'] = getCountryCode();
        $data['country_to_state_code'] = DB::table('country_to_state_code')->get();
        $data['shipping_data'] = (array) DB::table('event_program_registration')
            ->where('registration_id', $request->registration_id)
            ->first();
        return view('frontend/shipping_address', compact('data'));
    }



    public function editRegistration(Request $request)
    {


        $data['registration_data'] = (array)DB::table('event_program_registration')
            ->where('registration_id', $request->registration_id)
            ->first();
        $data['event_program'] = DB::table('event_program')->get();

        $data['country'] = DB::table('mobile_country_list')->get();
        $data['mobile_country_list'] = getCountryCode();
        $data['country_to_state_code'] = DB::table('country_to_state_code')->get();
        $data['form_setting'] = getSeoHandleSetting($request->seo_handle);
        return view('frontend/edit_registration', compact('data'));
    }

    public function savePayment(Request $request)
    {

        
        $payment_gateway = (array)DB::table('event_program')
            ->leftJoin('payment_gateway_config', 'payment_gateway_config.payment_gateway_id', '=', 'event_program.payment_gateway_id')         
            ->where('program_id', $request->program_id)
            ->first();

        $configuration = explode(PHP_EOL, $payment_gateway['configuration']);

        $key1 = explode(':', $configuration[0]);
        $secrte = explode(':', $configuration[1]);

        $keyId = trim($key1[1]);
        $keySecret = trim($secrte[1]);
       
        $registration =  (array) DB::table('event_program_registration')
        ->leftJoin('event_program', 'event_program_registration.program_id', "=", 'event_program.program_id')                          
        ->leftJoin('event_program_registration_seo_url', 'event_program_registration_seo_url.registration_seo_url_id', '=', 'event_program.registration_seo_url_id')
        ->where('event_program_registration.registration_number', $request->registration_number)
        ->first();

       

       
        if (!empty($request->razorpay_payment_id)) {
            $api = new Api($keyId, $keySecret);
           
            try {
                
                $response = $api->payment->fetch($request->razorpay_payment_id);
               
            //    $attributes = [
            //         'razorpay_order_id' => $request->razorpay_order_id,
            //         'razorpay_payment_id' => $request->razorpay_payment_id,
            //         'razorpay_signature' => $request->razorpay_signature
            //     ];

            //     $api->utility->verifyPaymentSignature($attributes);
               

                if (!empty($response)) {
                    try {
                        DB::beginTransaction();

                       
                      

                        $payment =  PaymentsModel::create([
                            'payment_gateway_id' => $registration['payment_gateway_id'],
                            'registration_number' => $registration['registration_number'],
                            'registration_id' => $registration['registration_id'],
                            'created_by' => 'gateway',
                            'payment_id' => $response->id,
                            'order_id' => $response->order_id,
                            'contact' => str_replace("+","", $response->contact)  ,
                            'amount_subunit' => $response->amount,
                            'amount' => ($response->amount) / 100,
                            'currency' => $response->currency,
                            'status' => $response->status,
                            'description' => $response->description,
                            'payment_response_json' => json_encode((array)$response),
                            'added_datetime' => Carbon::now('UTC')->format('Y-m-d h:i:s'),
                        ]);

                        $student_registration = StudentRegistrationModel::findOrFail($registration['registration_id']);
                        $student_registration->student_selection_status_id = $registration['selection_status_after_payment'];
                        $student_registration->student_invitation_status_id = $registration['invitation_status_after_payment'];
                        $student_registration->payment_status_id = $registration['payment_status_after_payment'];
                        $student_registration->last_update_datetime =  Carbon::now('UTC')->format('Y-m-d h:i:s');
                        $student_registration->payment_log_id = $payment->payment_log_id;
                        $student_registration->save();




                        NotificationJobQueue::create([
                            'notification_job_queue_type' => 'payment-success-email',
                            'notification_enabled' =>  $registration['email_notification_on_payment'],
                            'registration_id' => $registration['registration_id'],
                            'notification_status' => 'pending',
                            'added_datetime' =>  Carbon::now('UTC')->format('Y-m-d h:i:s'),
                            'queue_process_start_datetime' => NULL,
                            'queue_process_end_datetime' => NULL,
                            'notification_log' => '',
                        ]);

                        NotificationJobQueue::create([
                            'notification_job_queue_type' => 'payment-success-sms',
                            'notification_enabled' =>  $registration['sms_notification_on_payment'],
                            'registration_id' => $registration['registration_id'],
                            'notification_status' => 'pending',
                            'added_datetime' =>  Carbon::now('UTC')->format('Y-m-d h:i:s'),
                            'queue_process_start_datetime' => NULL,
                            'queue_process_end_datetime' => NULL,
                            'notification_log' => '',
                        ]);

                        NotificationJobQueue::create([
                            'notification_job_queue_type' => 'payment-success-whatsapp',
                            'notification_enabled' =>  $registration['whatsapp_notification_on_payment'],
                            'registration_id' => $registration['registration_id'],
                            'notification_status' => 'pending',
                            'added_datetime' =>  Carbon::now('UTC')->format('Y-m-d h:i:s'),
                            'queue_process_start_datetime' => NULL,
                            'queue_process_end_datetime' => NULL,
                            'notification_log' => '',
                        ]);

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


                        DB::commit();
                        session()->flash('success', 'Payment Successfully.');
                        Log::info('Razorpay payment successfull: ', ['registration_id' => $registration['registration_id']]);
                     
                     
                    } catch (\Exception $e) {  
                        Log::error('Razorpay payment log: ', ["error:"=> $e->getMessage() ] );
                        DB::rollBack();
                       
                    }
                }
            } catch (\Exception $e) { 
                Log::error('Razorpay payment log: ', ["error:"=> $e->getMessage() ] );

               
            }
        }
     
      
        return redirect(url("/event/registered/{$registration['seo_url']}/view?id={$registration['auto_login_string']}&{$registration['registration_success_event_url_parameter']}={$registration['registration_success_event_url_parameter_value']}"));


       



    }

    public function shortcode(Request $request)
    {
        $data =  $replace = getShortcode($request->registration_number);

        return view('frontend/shortcode', compact('data'));
    }

    public function login($seo_handle ,Request $request)
    {
        // dd($seo_handle);
        $data['form_setting'] = getSeoHandleSetting($seo_handle);
   
        $data['og_url'] = url('/') . "/event/registration/" . $data['form_setting']['seo_url'];

        return view('frontend/login', compact('data'));
    
    }

    public function do_login(Request $request)
    {
        
        $json = [];
        $user =  DB::table('event_program_registration')
        ->where('registered_email',$request->registered_email)
        ->where('registration_number',$request->registration_number)
        ->get();


     
        if( $user->count() > 0 ){   
            
            $user =  DB::table('event_program_registration')
            ->where('registered_email',$request->registered_email)
            ->where('registration_number',$request->registration_number)
            ->first();

            $login_url = $user->direct_login_short_url;
            $json['error'] = 0;
            $json['redirect'] = $login_url;
        }else{
            $json['error'] = 1;
            $json['msg'] = "User not found";
        }
        return response()->json( $json,200);    
    
    }
    
    public function forgetEmail(Request $request)
    {
      
        $json = [];
        $users =  DB::table('event_program_registration')
        ->leftJoin('event_program',"event_program.program_id" , "=" , "event_program_registration.program_id" )  
        ->leftJoin('event_program_registration_seo_url',"event_program_registration_seo_url.registration_seo_url_id" , "=" , "event_program.registration_seo_url_id" )  
        ->where('event_program_registration_seo_url.seo_url',$request->seo_handle)   
        ->where('event_program_registration.registered_email',$request->registered_email)     
        ->get();

   
       
        $user_data = [];
        if( $users->count() > 0 ){              
            $users =  DB::table('event_program_registration')
            ->leftJoin('event_program',"event_program.program_id" , "=" , "event_program_registration.program_id" )  
            ->leftJoin('event_program_registration_seo_url',"event_program_registration_seo_url.registration_seo_url_id" , "=" , "event_program.registration_seo_url_id" )  
            ->where('event_program_registration_seo_url.seo_url',$request->seo_handle)   
            ->where('event_program_registration.registered_email',$request->registered_email)     
            ->get();

            $all_program = [];
            foreach($users as $user ){
                $program_start_date = getTimeDateDisplay($user->user_time_zone_id,$user->start_dates, 'd-M-Y' );
                
             $all_program[] = [
                "registration_number"=>$user->registration_number,
                "program_name"=>$user->program_name,
                "direct_login_short_url"=>$user->direct_login_short_url,
                "program_start_date"=> $program_start_date 
             ];
            }

            $user_data = [
                'name' => $users[0]->first_name . " " . $users[0]->last_name,
                'registered_email'=> $users[0]->registered_email,
                'programs'=> $all_program
            ];
            $this->sendEmail( $user_data );

            $json['error'] = 0;
          //  $json['user_data'] = $user_data;
        }else{
            $json['error'] = 1;
           // $json['msg'] = "User not found";
        }
        return response()->json( $json,200);    
    
    }


    public function sendEmail($user_data)
    {
        
        $smtp_id = setting('smtp_id_for_forget_registration_number');

        $forget_registration_template_id =setting('template_id_for_forget_registration_number');

        $smtp_data = (array) DB::table('smtp_settings')
        ->where('smtp_id',$smtp_id )    
        ->first();

        $email_templates_data = (array) DB::table('email_templates')
        ->where('email_template_id',$forget_registration_template_id)    
        ->first();

           $mail = new PHPMailer(true);

            try {
            // Server settings
            $mail->SMTPDebug =  $smtp_data['smtp_debug']; // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host       = $smtp_data['smtp_host']; // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true; // Enable SMTP authentication
            $mail->Username   = $smtp_data['smtp_username']; // SMTP username
            $mail->Password   = $smtp_data['smtp_password']; // SMTP password
            $mail->SMTPSecure = $smtp_data['smtp encryption']; // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = $smtp_data['smtp_port'];; // TCP port to connect to

            // Recipients
            $mail->setFrom($email_templates_data['email_template_from_email'], $email_templates_data['email_template_from_name']);
            $mail->addAddress($user_data['registered_email'], $user_data['name']    ); // Add a recipient
          
            $message = $email_templates_data['email_template_html'];
            $subject = $email_templates_data['email_template_subject'] ;

           
            $message = appendUTM($message,$email_templates_data['email_template_link_utm']);
            $html='';
            if($user_data['programs']){
                $html = '<!DOCTYPE html>
                <html>
                <head>
                    <style>
                        .striped-table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        .striped-table th, .striped-table td {
                            border: 1px solid #ddd;
                            padding: 8px;
                        }
                        .striped-table tr:nth-child(even) {
                            background-color: #f2f2f2;
                        }
                        .striped-table th {
                            padding-top: 12px;
                            padding-bottom: 12px;
                            text-align: left;
                            background-color: #e55252;
                            color: white;
                        }
                    </style>
                </head>
                <body>
                    <table class="striped-table">
                        <thead>
                            <tr>
                                <th>Sr.no.</th>
                                <th>Program Name</th>
                                <th>Start Date</th>
                                <th>Registration Number</th>
                                <th>Auto Login Url</th>

                            </tr>
                        </thead>
                        
                        <tbody>';
                  foreach($user_data['programs'] as $key => $program ){
                    $html.= "<tr><td>".$key+1 ."</td> <td>".$program['program_name'] ."</td>  <td>".$program['program_start_date'] ."</td> <td>".$program['registration_number']."</td>        <td><a href='{$program['direct_login_short_url']}' >".$program['direct_login_short_url']."</a></td></tr>";                 
                      
                  }
                  $html.= '</tbody>
                  </table>
              </body>
              </html>
              ';
            }
            

            $content = str_replace("{{content}}",$html,$message );
           
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject =  $subject;
            $mail->Body    =  $content ;

            $mail->send();
          

            } catch (Exception $e) {           
               Log::error('Forget registration email: ', ["error:"=> $e->getMessage() ] );
            }

  }

    
}
