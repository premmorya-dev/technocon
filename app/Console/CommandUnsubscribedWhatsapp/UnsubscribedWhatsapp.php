<?php

namespace App\Console\CommandSubscribedWhatsapp;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\NotificationJobQueue;
use App\Models\IncomingWebhookJobQueue;
use Illuminate\Support\Facades\Log;
class SubscribedWhatsapp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:subscribed-whatsapp {--incoming_webhook_job_queue_id=0} {--limit=10000}';

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
      $log = '';
      $incoming_webhook_job_queue_id = $this->option('incoming_webhook_job_queue_id');
      $limit = $this->option('limit');

    

         

      if($incoming_webhook_job_queue_id != 0){ 
        $notifications = DB::table('incoming_webhook_job_queue')
        ->where('queue_processing', 'Y')
        ->where('incoming_webhook_job_queue_id', $incoming_webhook_job_queue_id )
        ->where('incoming_webhook_action', 'msg91-whatsapp-unsubscribe')
        ->orderBy('incoming_webhook_job_queue_id', 'asc') // Change 'created_at' to the appropriate column
        ->limit($limit) // Set the limit to any number you need
        ->get();

      }else{

        $notifications = DB::table('incoming_webhook_job_queue')
        ->where('queue_processing', 'Y')
        ->where('incoming_webhook_action', 'msg91-whatsapp-unsubscribe')
        ->orderBy('incoming_webhook_job_queue_id', 'asc') // Change 'created_at' to the appropriate column
        ->limit($limit) // Set the limit to any number you need
        ->get();


      }

     
     
      if ($notifications->isEmpty()) {
        $log = "\nNotification queue is empty\n";
        exit($log);
      }
  
      foreach ($notifications  as $notification ) {
  
        $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);
  
        if(  $notification->queue_process_status  == 'running'   ){
            $log =  "\nIncoming Webhook Job Queue Id: {$notification->incoming_webhook_job_queue_id} | Status: Process is running";                  
            echo $log;    
          //  continue;
        }else  if( $notification->queue_process_status  == 'success'   ){        
            $log =  "\nIncoming Webhook Job Queue Id: {$notification->incoming_webhook_job_queue_id} | Status: Already Unsubscribed";                  
            echo $log;    
            continue;
        }else  if( $notification->queue_processing  == 'N'   ){
            $log =  "\nIncoming Webhook Job Queue Id: {$notification->incoming_webhook_job_queue_id} | Status: Queue processing is disable";                  
            echo $log;    
            continue;
        }



        $this->sendRegistrationWhatsapp($notification);
      }

      echo "\n";
    }


    public function sendRegistrationWhatsapp($notification)
    {

      $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);     
      $notification_queue->queue_process_status = 'running';
      $notification_queue->queue_process_start_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
      $notification_queue->save();


      $payload = json_decode($notification->incoming_webhook_body_json);
      
      $sender_number =  $payload->integrated_number;

      $whatsapp_sender_detail =(array)  DB::table('whatsapp_sender')
      ->where('sender_number',$sender_number)
      ->first();
  
      $text_msg = $payload->text;
      
      $fullContactNumber = $payload->sender;    
     
      $mobileNumber = substr($fullContactNumber, -10);

      $countryCode = substr($fullContactNumber, 0, strlen($fullContactNumber) - 10);

      $unsubscribed_keyword = explode(",",  $whatsapp_sender_detail['unsubscribe_keyword']);
        
     
      if($unsubscribed_keyword){
        foreach($unsubscribed_keyword as $unsubscribe){

           if((strtolower($text_msg) == strtolower($unsubscribe)) ){
            
              $whatsapp_templates =(array)  DB::table('whatsapp_templates')             
              ->where('whatsapp_template_id',$whatsapp_sender_detail['unsubscribe_whatsapp_template_id'])
              ->first();
             
              DB::table('event_program_registration')
              ->where('mobile_country_code_id', $countryCode)
              ->where('mobile_no', $mobileNumber)
              ->update([
                  'whatsapp_optin' => 'N'
            
              ]);

              $job_queue_type = 'whatsapp-unsubscribed';
           }


        }
      }


      $resubscribed_keyword = explode(",",  $whatsapp_sender_detail['resubscribe_keyword']);
      if($resubscribed_keyword){
        foreach($resubscribed_keyword as $resubscribe){

           if((strtolower($text_msg) == strtolower($resubscribe)) ){
            
              $whatsapp_templates =(array)  DB::table('whatsapp_templates')             
              ->where('whatsapp_template_id',$whatsapp_sender_detail['resubscribe_whatsapp_template_id'])
              ->first();
             
              DB::table('event_program_registration')
              ->where('mobile_country_code_id', $countryCode)
              ->where('mobile_no', $mobileNumber)
              ->update([
                  'whatsapp_optin' => 'Y'
            
              ]);

              $job_queue_type = 'whatsapp-resubscribed';
           }


        }
      }




      $whatsapp_templates_api =(array)  DB::table('whatsapp_api')             
      ->where('whatsapp_api_id',$whatsapp_sender_detail['whatsapp_api_id'])
      ->first();

      if(isset($whatsapp_templates_api['config_json']) && $whatsapp_templates_api['config_json']){

       $whatsapp_templates_api_decode = json_decode($whatsapp_templates_api['config_json']);

       $auth_key = $whatsapp_templates_api_decode->authkey ?? '' ;

      }else{
        $auth_key = '';
      }



 
  //  if( empty(json_decode($whatsapp_templates_api['template_short_code_mapping_config_json'],true))  ){       
  //   $request_json_object = '{}';
  // }else{    

  //   $template_config_json =   json_decode($whatsapp_templates_api['template_config_json'] ,true);
  //   $mapping_json_code = shortcode($registration_number,$whatsapp_templates_api['template_short_code_mapping_config_json']);   
  //   $template_short_code_mapping_config_json =   json_decode($mapping_json_code ,true);     
  //   $component[] = $template_short_code_mapping_config_json['headers'];
  //   $component[] = $template_short_code_mapping_config_json['body'];
  //   $component[] = $template_short_code_mapping_config_json['button'];    
  //   $mergedJson = array_merge_recursive(...$component);
  //   $request_json_object = str_replace("\n","" ,json_encode($mergedJson, JSON_PRETTY_PRINT));  

  // }
 
    
  $request_json_object = '{}';

  $payload_json_string = '{
    "integrated_number": "'.$whatsapp_sender_detail['sender_number'].'",
    "content_type": "template",
    "payload": {
      "type": "template",
      "template": {
        "name": "'.$whatsapp_templates['template_name'].'",
        "language": {
          "code": "En_US",
          "policy": "deterministic"
        },
        "to_and_components": [
          {
            "to": [
              "'. $countryCode . $mobileNumber.'"
            ],
            "components": '.$request_json_object.'
          }
         
        ]
      },
      "messaging_product": "whatsapp"
    }
  }';




  $curl = curl_init();
  
  curl_setopt_array($curl, [
    CURLOPT_URL => "https://control.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $payload_json_string,
    CURLOPT_HTTPHEADER => [
      "accept: application/json",
      "authkey: {$auth_key}",
      "content-type: application/json"
    ],
  ]);
  
  $response =  curl_exec($curl);
  $err = curl_error($curl);       
  curl_close($curl);

  $response_json = json_decode($response);
 
  $response_decode = json_decode($response);

  if ($err) {  
    $log =  "\nIncoming Webhook Job Queue Id: {$notification->incoming_webhook_job_queue_id} | Contact No.: {$fullContactNumber} | Whatsapp Noitification Platform: msg91 | Template Name: {$whatsapp_templates['template_name']} | Status: failed | Msg: {$err}"; 
  
    echo $log;

    $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);     
    $notification_queue->queue_process_status = 'failed';
    $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
    $notification_queue->process_log =  $log;
    $notification_queue->save();   

  


  } else if ($response_json->hasError) {


    $log =  "\nIncoming Webhook Job Queue Id: {$notification->incoming_webhook_job_queue_id} | Contact No.: {$fullContactNumber} |  Whatsapp Noitification Platform: msg91 | Template Name: {$whatsapp_templates['template_name']} | Api response: {$response}";  
    
    echo $log;
    
    $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);     
    $notification_queue->queue_process_status = 'failed';
    $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
    $notification_queue->process_log =  $log;
    $notification_queue->save();   

  }else{
    $log =  "\nIncoming Webhook Job Queue Id: {$notification->incoming_webhook_job_queue_id} | Contact No.: {$fullContactNumber} |  Whatsapp Noitification Platform: msg91 | Template Name: {$whatsapp_templates['template_name']} | Api response: {$response} | Request Id: {$response_decode->request_id}";  
    
    echo $log;
    
    $notification_queue = IncomingWebhookJobQueue::findOrFail($notification->incoming_webhook_job_queue_id);     
    $notification_queue->queue_process_status = 'success';
    $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
    $notification_queue->process_log =  $log;
    $notification_queue->save();   



    NotificationJobQueue::create([
      'notification_job_queue_type' =>  $job_queue_type,
      'bulk_notification_job_id' => NULL,
      'notification_enabled' =>'Y',
      'registration_id' => NULL,
      'notification_status' => 'success',
      'added_datetime' => Carbon::now('UTC')->format('Y-m-d h:i:s'),
      'queue_process_start_datetime' =>Carbon::now('UTC')->format('Y-m-d h:i:s'),
      'queue_process_end_datetime' => Carbon::now('UTC')->format('Y-m-d h:i:s'),
      'notification_log' => $log,

      'request_id' => $response_decode->request_id,
      'delivery_status' => NULL,
      'delivery_status_datettime' => NULL,

      ]);



  }
 
   
 
    }

    // public function msg91($data){   

    //   $status = 1;
    //   $platform_config = json_decode($data->config_json,true);

    //   $whatsapp_sender= (array) DB::table('whatsapp_sender')
    //   ->where('whatsapp_sender_id',$data->whatsapp_sender_id)
    //   ->first();

    //   $job_queue_type = '';
    //   $payload = json_decode($data->incoming_webhook_body_json,false);
    //   $text_msg = $payload->text;
      
         
    //   $unsubscribed_keyword = explode(",",  $whatsapp_sender['unsubscribe_keyword']);
        
     
    //   if($unsubscribed_keyword){
    //     foreach($unsubscribed_keyword as $unsubscribe){

    //        if((strtolower($text_msg) == strtolower($unsubscribe)) ){
            
    //           $whatsapp_templates =(array)  DB::table('whatsapp_templates')
    //           ->where('whatsapp_api_id',$data->whatsapp_api_id)
    //           ->where('whatsapp_template_id',$whatsapp_sender['unsubscribe_whatsapp_template_id'])
    //           ->first();
             
    //           DB::table('event_program_registration')
    //           ->where('registration_id', $data->registration_id)
    //           ->update([
    //               'whatsapp_optin' => 'N'
            
    //           ]);

    //           $job_queue_type = 'whatsapp-unsubscribed';
    //        }


    //     }
    //   }


    //   $resubscribed_keyword = explode(",",  $whatsapp_sender['resubscribe_keyword']);
    //   if($resubscribed_keyword){
    //     foreach($resubscribed_keyword as $resubscribe){

    //        if((strtolower($text_msg) == strtolower($resubscribe)) ){
    //          $whatsapp_templates =(array)  DB::table('whatsapp_templates')
    //           ->where('whatsapp_api_id',$data->whatsapp_api_id)
    //           ->where('whatsapp_template_id',$whatsapp_sender['resubscribe_whatsapp_template_id'])
    //           ->first();
             
    //           DB::table('event_program_registration')
    //           ->where('registration_id', $data->registration_id)
    //           ->update([
    //               'whatsapp_optin' => 'Y'
            
    //           ]);

    //           $job_queue_type = 'whatsapp-resubscribed';
    //        }

           
    //     }
    //   }
     

    
     
    //   if( empty(json_decode($whatsapp_templates['template_short_code_mapping_config_json'],true))  ){       
    //     $request_json_object = '{}';
    //   }else{

        
    //     $template_config_json =   json_decode($whatsapp_templates['template_config_json'] ,true);


     
   
    //     $mapping_json_code = shortcode($data->registration_number,$whatsapp_templates['template_short_code_mapping_config_json']);
       
       
       
   
  
    //     $template_short_code_mapping_config_json =   json_decode($mapping_json_code ,true);
  
     
        
    //     $component[] = $template_short_code_mapping_config_json['headers'];
    //     $component[] = $template_short_code_mapping_config_json['body'];
    //     $component[] = $template_short_code_mapping_config_json['button'];
        
    //     $mergedJson = array_merge_recursive(...$component);
    //     $request_json_object = str_replace("\n","" ,json_encode($mergedJson, JSON_PRETTY_PRINT)) ;   


    //   }
     
   
    

    //   $payload_json_string = '{
    //     "integrated_number": "'.$whatsapp_sender['sender_number'].'",
    //     "content_type": "template",
    //     "payload": {
    //       "type": "template",
    //       "template": {
    //         "name": "'.$whatsapp_templates['template_name'].'",
    //         "language": {
    //           "code": "En_US",
    //           "policy": "deterministic"
    //         },
    //         "to_and_components": [
    //           {
    //             "to": [
    //               "'. $data->country_code . $data->mobile_no.'"
    //             ],
    //             "components": '.$request_json_object.'
    //           }
             
    //         ]
    //       },
    //       "messaging_product": "whatsapp"
    //     }
    //   }';
   
   
   
    //   $curl = curl_init();
      
    //   curl_setopt_array($curl, [
    //     CURLOPT_URL => "https://control.msg91.com/api/v5/whatsapp/whatsapp-outbound-message/bulk/",
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_ENCODING => "",
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 30,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => "POST",
    //     CURLOPT_POSTFIELDS => $payload_json_string,
    //     CURLOPT_HTTPHEADER => [
    //       "accept: application/json",
    //       "authkey: {$platform_config['authkey']}",
    //       "content-type: application/json"
    //     ],
    //   ]);
      
    //   $response =  curl_exec($curl);
    //   $err = curl_error($curl);       
    //   curl_close($curl);

    //   $response_json = json_decode($response);

     
    //   if ($err) {  
    //     $log =  "\nIncoming Webhook Job Queue Id: {$data->incoming_webhook_job_queue_id} | Registration id: {$data->registration_id} | Contact No.: {$data->fullContactNumber} | Whatsapp Noitification Platform: msg91 | Template Name: {$whatsapp_templates['template_name']} | Status: failed | Msg: {$err}"; 
      
    //     echo $log;

    //     $notification_queue = IncomingWebhookJobQueue::findOrFail($data->incoming_webhook_job_queue_id);     
    //     $notification_queue->queue_process_status = 'failed';
    //     $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
    //     $notification_queue->process_log =  $log;
    //     $notification_queue->save();   

      


    //   } else if ($response_json->hasError) {


    //     $log =  "\nIncoming Webhook Job Queue Id: {$data->incoming_webhook_job_queue_id} | Registration id: {$data->registration_id} | Contact No.: {$data->fullContactNumber} |  Whatsapp Noitification Platform: msg91 | Template Name: {$whatsapp_templates['template_name']} | Api response: {$response}";  
        
    //     echo $log;
        
    //     $notification_queue = IncomingWebhookJobQueue::findOrFail($data->incoming_webhook_job_queue_id);     
    //     $notification_queue->queue_process_status = 'failed';
    //     $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
    //     $notification_queue->process_log =  $log;
    //     $notification_queue->save();   

    //   }else{
    //     $log =  "\nIncoming Webhook Job Queue Id: {$data->incoming_webhook_job_queue_id} | Registration id: {$data->registration_id} | Contact No.: {$data->fullContactNumber} |  Whatsapp Noitification Platform: msg91 | Template Name: {$whatsapp_templates['template_name']} | Api response: {$response}";  
        
    //     echo $log;
        
    //     $notification_queue = IncomingWebhookJobQueue::findOrFail($data->incoming_webhook_job_queue_id);     
    //     $notification_queue->queue_process_status = 'success';
    //     $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
    //     $notification_queue->process_log =  $log;
    //     $notification_queue->save();   


    //    $response_decode = json_decode($response);

    //     NotificationJobQueue::create([
    //       'notification_job_queue_type' =>  $job_queue_type,
    //       'bulk_notification_job_id' => NULL,
    //       'notification_enabled' =>'Y',
    //       'registration_id' => NULL,
    //       'notification_status' => 'success',
    //       'added_datetime' => Carbon::now('UTC')->format('Y-m-d h:i:s'),
    //       'queue_process_start_datetime' =>Carbon::now('UTC')->format('Y-m-d h:i:s'),
    //       'queue_process_end_datetime' => Carbon::now('UTC')->format('Y-m-d h:i:s'),
    //       'notification_log' => $log,

    //       'request_id' => $response_decode->request_id,
    //       'delivery_status' => NULL,
    //       'delivery_status_datettime' => NULL,

    //       ]);



    //   }
     



    // }
    




}
