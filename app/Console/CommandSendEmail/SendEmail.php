<?php

namespace App\Console\CommandSendEmail;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DOMDocument;
use Carbon\Carbon;

use App\Models\NotificationJobQueue;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-registration-email {limit}';

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
        $limit = $this->argument('limit');

        $notifications = DB::table('notification_job_queue')
        ->where('notification_status', 'pending')
        ->where('notification_job_queue_type', 'registration-email')
        ->orderBy('notification_job_queue_id', 'asc') // Change 'created_at' to the appropriate column
        ->limit($limit) // Set the limit to any number you need
        ->get();

        if ($notifications->isEmpty()) {
          $log = "\nNotification queue is empty\n";   
          exit($log);     
        }
  

        foreach($notifications  as $notification){
      
          $notification_queue = NotificationJobQueue::findOrFail($notification->notification_job_queue_id);

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
 
 
                     $message = appendUTM($message,$email_templates_data['email_template_link_utm']);
 
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

     


}
