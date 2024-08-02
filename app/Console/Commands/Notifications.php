<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DOMDocument;
use Carbon\Carbon;

use EmsApi\Config;
use EmsApi\Base;
use EmsApi\Cache\File;
use EmsApi\Endpoint\Lists;
use EmsApi\Params;



require_once 'vendor/autoload.php';   

use App\Models\NotificationJobQueue;

class Notifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notifications {param1?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      // \Log::info('Your command is working!');
      $error = '';
      $log='';
      $limit = $this->argument('param1');

      $notifications = DB::table('notification_job_queue')
                    ->where('notification_status', 'pending')
                    ->orderBy('notification_job_queue_id', 'asc') // Change 'created_at' to the appropriate column
                    ->limit($limit) // Set the limit to any number you need
                    ->get();


      if(empty($notifications)){
        $log = "\nNotification queue is empty";   
        exit($log);     
      }

      foreach($notifications  as $notification){
      
        $notification_queue = NotificationJobQueue::findOrFail($notification->notification_job_queue_id);
       

      


        if($notification->notification_job_queue_type  == 'registration-email'){
            if(  $notification->notification_status  == 'running'   ){
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Email sent unsuccessfully | Msg: notification is already running";                  
                echo $log;    
                continue;
            }else  if( $notification->notification_status  == 'success'   ){        
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Email sent unsuccessfully | Msg: notification is already completed";                  
                echo $log;    
                continue;
            }else  if( $notification->notification_enabled  == 'N'   ){
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Email sent unsuccessfully | Msg: notification is disabled";                  
                echo $log;    
                continue;
            }
            $this->sendEmail($notification);
        }else  if($notification->notification_job_queue_type  == 'registration-sms'){
            if(  $notification->notification_status  == 'running'   ){
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: SMS sent unsuccessfully | Msg: notification is already running";                  
                echo $log;    
                continue;
            }else  if( $notification->notification_status  == 'success'   ){        
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: SMS sent unsuccessfully | Msg: notification is already completed";                  
                echo $log;    
                continue;
            }else  if( $notification->notification_enabled  == 'N'   ){
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: SMS sent unsuccessfully | Msg: notification is disabled";                  
                echo $log;    
                continue;
            }
            $this->sendSMS($notification);
           
                
        }else  if($notification->notification_job_queue_type  == 'registration-whatsapp'){
            if(  $notification->notification_status  == 'running'   ){
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Whatsapp sent unsuccessfully | Msg: notification is already running";                  
                echo $log;    
                continue;
            }else  if( $notification->notification_status  == 'success'   ){        
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Whatsapp sent unsuccessfully | Msg: notification is already completed";                  
                echo $log;    
                continue;
            }else  if( $notification->notification_enabled  == 'N'   ){
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Whatsapp sent unsuccessfully | Msg: notification is disabled";                  
                echo $log;    
                continue;
            }
            $this->sendWhatsapp($notification);
           
        }else  if($notification->notification_job_queue_type  == 'zoikmail-common-list-sync'){
            if(  $notification->notification_status  == 'running'   ){
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Sync unsuccessfully | Msg: notification is already running";                  
                echo $log;    
                continue;
            }else  if( $notification->notification_status  == 'success'   ){        
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Sync unsuccessfully | Msg: notification is already completed";                  
                echo $log;    
                continue;
            }else  if( $notification->notification_enabled  == 'N'   ){
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Sync unsuccessfully | Msg: notification is disabled";                  
                echo $log;    
                continue;
            }
            $this->zoikCommonListSync($notification);
           
        }else  if($notification->notification_job_queue_type  == 'zoikmail-program-list-sync'){
            if(  $notification->notification_status  == 'running'   ){
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Sync unsuccessfully | Msg: notification is already running";                  
                echo $log;    
                continue;
            }else  if( $notification->notification_status  == 'success'   ){        
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Sync unsuccessfully | Msg: notification is already completed";                  
                echo $log;    
                continue;
            }else  if( $notification->notification_enabled  == 'N'   ){
                $log =  "\nRegistration id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: Sync unsuccessfully | Msg: notification is disabled";                  
                echo $log;    
                continue;
            }
            $this->zoikProgramListSync($notification);
           
        }


        

      } 
  
    }

    public function sendEmail($notification)
    {
        $notification_queue = NotificationJobQueue::findOrFail($notification->notification_job_queue_id);
        $notification_queue->notification_status = 'running';
        $notification_queue->queue_process_start_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
        $notification_queue->save();

          $registration_data = (array) DB::table('event_program_registration')
          ->leftJoin('event_program','event_program_registration.program_id' , "=", 'event_program.program_id' )
          ->leftJoin('event_program_location','event_program_location.program_location_id' , "=", 'event_program_location.program_location_id' )
          ->leftJoin('event_program_location_venue','event_program_location_venue.program_location_id' , "=", 'event_program_location_venue.program_location_id' )
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
          ->where('event_program_registration.registration_id',$notification->registration_id)    
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

        //   dd($registration_data['registration_id']); 
          $smtp_data = (array) DB::table('smtp_settings')
                                    ->where('smtp_id',$registration_data['smtp_id'])    
                                    ->first();
        $email_templates_data = (array) DB::table('email_templates')
        ->where('email_template_id',$registration_data['registration_email_template_id'])    
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
                    $mail->addAddress($registration_data['registered_email'], $registration_data['first_name'] ." " . $registration_data['last_name']   ); // Add a recipient
                   

                    // Attachments (optional)
                    // $mail->addAttachment('/var/tmp/file.tar.gz'); // Add attachments
                    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg'); // Optional name

                    // Content

                    $message = shortcode($registration_data['registration_number'],$email_templates_data['email_template_html'] );
                    $subject = shortcode($registration_data['registration_number'],$email_templates_data['email_template_subject'] );


                    $message = $this->appendUTM($message,$email_templates_data['email_template_link_utm']);

                    $mail->isHTML(true); // Set email format to HTML
                    $mail->Subject =  $subject;
                    $mail->Body    =  $message ;
                   
             


                    $mail->send();

                    
                    
                    $log =  "\nRegistration_id: {$notification->registration_id} | Notification Type: {$notification->registration_id} | Email: {$registration_data['registered_email']} | Status: Email sent successfully";
                    echo $log;

                   
                    $notification_queue->notification_status = 'success';
                    $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
                    $notification_queue->notification_log = $log;
                    $notification_queue->save();


                } catch (Exception $e) {
                    $log =  "\nRegistration_id: {$notification->registration_id} | Notification Type: {$notification->registration_id} | Email: {$registration_data['registered_email']} | Status: Email sent unsuccessfully | Error: {$mail->ErrorInfo}";                   
                    echo $log;
                   
                    $notification_queue->notification_status = 'failed';
                    $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
                    $notification_queue->notification_log = $log;
                    $notification_queue->save();

                }


         

    }

    public function sendSMS($notification)
    {
         echo "\nRegistration Id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: skipped";
    }

    public function sendWhatsapp($notification)
    {
        echo "\nRegistration Id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: skipped";
    }

    public function zoikCommonListSync($notification)
    {

        $notification_queue = NotificationJobQueue::findOrFail($notification->notification_job_queue_id);
        $notification_queue->notification_status = 'running';
        $notification_queue->queue_process_start_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
        $notification_queue->save();

        $registration_data = (array) DB::table('event_program_registration')
        ->leftJoin('event_program','event_program_registration.program_id' , "=", 'event_program.program_id' )
        ->leftJoin('event_program_location','event_program_location.program_location_id' , "=", 'event_program_location.program_location_id' )
        ->leftJoin('event_program_location_venue','event_program_location_venue.program_location_id' , "=", 'event_program_location_venue.program_location_id' )
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
        ->where('event_program_registration.registration_id',$notification->registration_id)    
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


        if( !empty(DB::table('time_zone')
        ->where('time_zone_id',$registration_data['time_zone_id'])
        ->first()   ) ){
            $time_zone_name =   DB::table('time_zone')
            ->where('time_zone_id',$registration_data['time_zone_id'])
            ->first()->timezone; 
        }else{
            $time_zone_name ='';
        } 

        $registration_time = '';
        if (isset($registration_data['registration_time'])) {
          $registration_time = convertUtcToTimeZone($registration_data['registration_time'], $time_zone_name);
    
        }

        if (isset($registration_data['zoik_app_id']) && isset($registration_data['zoik_app_common_list_uid']) && $registration_data['zoik_app_common_list_uid']) {
            $zoik_app_setting =   DB::table('zoik_app_setting')
            ->where('zoik_app_id',$registration_data['zoik_app_id'])
            ->first(); 
           
            if ($zoik_app_setting) {
                $config = new \EmsApi\Config([
                    'apiUrl' => $zoik_app_setting->zoik_app_api_url,
                    'apiKey' => $zoik_app_setting->zoik_app_api_key,
        
                    // components
                    'components' => [
                      'cache' => [
                        'class' => \EmsApi\Cache\File::class,
                        'filesPath' => base_path() . '/storage/ems-api', // make sure it is writable by webserver
                      ]
                    ],
                  ]);
                  \EmsApi\Base::setConfig($config);



                  date_default_timezone_set('UTC');
    
                  if ($registration_data['zoik_app_common_list_field_mapping']) {

                    $registration_data['zoik_app_common_list_field_mapping'] = json_encode(json_decode($registration_data['zoik_app_common_list_field_mapping']), JSON_UNESCAPED_UNICODE);
                    $field_name = json_decode($registration_data['zoik_app_common_list_field_mapping'], true);     
                  

                    $endpointLists = new \EmsApi\Endpoint\Lists();
                    $common_list_response = $endpointLists->getList($registration_data['zoik_app_common_list_uid']);
                    $common_list_param = new \EmsApi\Params($common_list_response->body);
                    $shortcode_value = getShortcode($registration_data['registration_number']);
                    $sync_data = [];

                 
                    if ($common_list_param->itemAt('status') == 'success') {
                        $endpointListSubscribers = new \EmsApi\Endpoint\ListSubscribers();
                        $search_common_response = $endpointListSubscribers->emailSearch($registration_data['zoik_app_common_list_uid'], $registration_data['zoik_app_common_list_field_mapping']);
                       
                        if ($search_common_response) {
                            $search_common_param = new \EmsApi\Params($search_common_response->body);
  
                            if ( $search_common_param->itemAt('status') ==  'success' ) {                           
                               

                             }else{
                                if(!empty( $field_name)){
                                    foreach( $field_name as $shortcode_tag => $zoik_tag){
                                   
                                        $value = $shortcode_value[$shortcode_tag];
                                        $key = str_replace("[", '', $zoik_tag);
                                        $key = str_replace("]", '', $key);                                       
                                        $sync_data[$key] = $value;
                                    }
                                }
                             
                                $create_common_list_response = $endpointListSubscribers->create($registration_data['zoik_app_common_list_uid'], $sync_data );

                                if ($create_common_list_response) {
                                    $create_common_list_param = new \EmsApi\Params($create_common_list_response->body);
              
                                   
       
                                    $log = "\nRegistration Id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: success | Msg: Common list subscriber UID created | UID: {$create_common_list_param->itemAt('data')['record']['subscriber_uid']}";
                                    
                                    echo $log;

                                    $notification_queue = NotificationJobQueue::findOrFail($notification->notification_job_queue_id); 
                                    $notification_queue->notification_status = 'success';
                                    $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
                                    $notification_queue->notification_log = $log;
                                    $notification_queue->save();
              
                                 
              
                                  }


                             }
                        }
                    }
                   

                   

                  }
            }
        }


        // echo "\nRegistration Id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: skipped";
    }

    public function zoikProgramListSync($notification)
    {
        echo "\nRegistration Id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: skipped";
    }

    public function appendUTM($html,$utm_parameters)
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
