<?php

namespace App\Console\CommandRazorpayWebhook;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\IncomingWebhookJobQueue;
use App\Models\StudentRegistrationModel;
use App\Models\PaymentsModel;
use App\Models\NotificationJobQueue;


class RazorpayWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:razorpay-webhook {--incoming_webhook_job_queue_id=0} {--limit=100}';

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
        $error = '';
        $log='';
        $incoming_webhook_job_queue_id = $this->option('incoming_webhook_job_queue_id');
        $limit = $this->option('limit');
     

        if($incoming_webhook_job_queue_id != 0){ 
            $notifications = DB::table('incoming_webhook_job_queue')
            ->where('incoming_webhook_job_queue_id', $incoming_webhook_job_queue_id)    
            ->where('queue_process_status', 'pending')
            ->where('incoming_webhook_action', 'razorpay-payment-success')
            ->orderBy('incoming_webhook_job_queue_id', 'asc') 
            ->limit($limit) // Set the limit to any number you need
            ->get();
    
        }else{       
            $notifications = DB::table('incoming_webhook_job_queue')            
            ->where('queue_process_status', 'pending')
            ->where('incoming_webhook_action', 'razorpay-payment-success')
            ->orderBy('incoming_webhook_job_queue_id', 'asc') 
            ->limit($limit) // Set the limit to any number you need
            ->get();

          }

      

        if ($notifications->isEmpty()) {
            $log = "\nNotification queue is empty\n";
            Log::channel('webhook')->info($log); 
            exit($log);
        }



      foreach ($notifications  as $notification) {
  
        $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);
  
        if(  $notification_queue->queue_process_status  == 'running'   ){
            $log =  "\Job Queue Id: {$notification_queue->incoming_webhook_job_queue_id} | Msg: Payment Process Running";                  
            echo $log;  
            Log::channel('webhook')->info($log);   
            continue;
        }else  if( $notification_queue->queue_process_status  == 'success'   ){        
            $log =  "\nJob Queue Id: {$notification_queue->incoming_webhook_job_queue_id} | Msg: Already Paid";                  
            echo $log;   
            Log::channel('webhook')->info($log);  
            continue;
        }else  if( $notification_queue->queue_processing  == 'N'   ){
            $log =  "\nJob Queue Id: {$notification_queue->incoming_webhook_job_queue_id} | Msg: Queue Processing Disabled";                  
            echo $log; 
            Log::channel('webhook')->info($log);    
            continue;
        }
        $this->doPayment($notification);
      }

    echo "\n";

        // dd($incoming_webhook_job_queue_id);
    }




    public function doPayment($notification)
    {


      $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);     
      $notification_queue->queue_process_status = 'running';
      $notification_queue->queue_process_start_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
      $notification_queue->save();


      $payload = json_decode($notification->incoming_webhook_body_json,true);

      if($payload['event'] == 'payment.captured' ){

        $payload_payment = $payload['payload']['payment']['entity'];

        if(  $payload_payment['status'] == 'captured' ){


                $registration_number = $payload_payment['notes']['registration_number'];
                
                $registration =  (array) DB::table('event_program_registration')
                ->leftJoin('event_program', 'event_program_registration.program_id', "=", 'event_program.program_id')                          
                ->leftJoin('event_program_registration_seo_url', 'event_program_registration_seo_url.registration_seo_url_id', '=', 'event_program.registration_seo_url_id')
                ->where('event_program_registration.registration_number', $registration_number)
                ->first();

                if(empty($registration)){
                    $log =  "\nJob Queue Id: {$notification_queue->incoming_webhook_job_queue_id} | Msg: No student data found";                  
                    echo $log; 
                    Log::channel('webhook')->info($log); 
                    $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);     
                    $notification_queue->queue_process_status = 'failed';
                    $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
                    $notification_queue->save();
                    return;  
                }


                $payment_exist =  DB::table('payments')              
                ->where('registration_number', $registration_number)
                ->get();

                if( $payment_exist->count() > 0 ){
                    $log =  "\nJob Queue Id: {$notification_queue->incoming_webhook_job_queue_id} | Msg: Payment Already Recorded";                  
                    echo $log;
                    Log::channel('webhook')->info($log); 
                    $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);     
                    $notification_queue->queue_process_status = 'success';
                    $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
                    $notification_queue->save();                  
                    return;  
                }


                $student_registration_payment = PaymentsModel::find($registration['registration_id']);

                if(!empty($student_registration_payment)){
                    $log =  "\nJob Queue Id: {$notification_queue->incoming_webhook_job_queue_id} | Msg: Payment Already Recorded";                  
                    echo $log; 
                    Log::channel('webhook')->info($log); 
                    $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);     
                    $notification_queue->queue_process_status = 'success';
                    $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
                    $notification_queue->save();    
                    return; 
                    
                }


                try {

                    DB::beginTransaction();

                    $payment =  PaymentsModel::create([
                        'payment_gateway_id' => $registration['payment_gateway_id'],
                        'registration_number' => $registration['registration_number'],
                        'created_by' => 'webhook',
                        'registration_id' => $registration['registration_id'],
                        'payment_id' => $payload_payment['id'],
                        'order_id' =>  $payload_payment['order_id'],
                        'contact' => str_replace("+","",$payload_payment['contact']) ,
                        'amount_subunit' => $payload_payment['amount'] ,
                        'amount' => ( $payload_payment['amount']) / 100,
                        'currency' => $payload_payment['currency'],
                        'status' =>  $payload_payment['status'],   
                        'description' => $payload_payment['description'],
                        'payment_response_json' => json_encode((array)$payload ),
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
                    $log =  "\nJob Queue Id: {$notification_queue->incoming_webhook_job_queue_id} | Msg: Payment Recorded Successfully";                  
                    echo $log;  
                    Log::channel('webhook')->info($log); 

                    $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);     
                    $notification_queue->queue_process_status = 'success';
                    $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
                    $notification_queue->save();   
                    return; 
                  
    
    
                } catch (\Exception $e) { 

                    $log =  "\nJob Queue Id: {$notification_queue->incoming_webhook_job_queue_id} | Msg: Payment not Recorded | Error: {$e->getMessage()}";                  
                    echo $log;  
                    Log::channel('webhook')->info($log); 
                    DB::rollBack(); 

                    $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);     
                    $notification_queue->queue_process_status = 'failed';
                    $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
                    $notification_queue->save();   
                    return; 

                   
                }       
                



        }

      }
     
    }












    
}
