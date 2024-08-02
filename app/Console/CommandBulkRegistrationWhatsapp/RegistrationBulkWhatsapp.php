<?php

namespace App\Console\CommandBulkRegistrationWhatsapp;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use DOMDocument;
use Carbon\Carbon;
use EmsApi\Config;
use EmsApi\Base;
use EmsApi\Cache\File;
use EmsApi\Endpoint\Lists;
use EmsApi\Params;

use Twilio\Rest\Client;
use App\Models\BulkWhatsappNotificationJobQueue;
use Illuminate\Support\Facades\Log;
class BulkRegistrationWhatsapp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bulk-registration-whatsapp {--registration_id=0} {--bulk_whatsapp_notification_job_queue_id=0} {--limit=0} {--force=no}';

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
      $registration_id = $this->option('registration_id');
      $bulk_whatsapp_notification_job_queue_id = $this->option('bulk_whatsapp_notification_job_queue_id');
      $limit = $this->option('limit');
      $force = $this->option('force');


      if( $bulk_whatsapp_notification_job_queue_id != 0 ){ 

        $query = DB::table('bulk_whatsapp_notification_job')
        ->leftJoin('bulk_whatsapp_notification_job_queue','bulk_whatsapp_notification_job.bulk_whatsapp_notification_job_id','=','bulk_whatsapp_notification_job_queue.bulk_whatsapp_notification_job_id')
        ->where('bulk_whatsapp_notification_job_queue.bulk_whatsapp_notification_job_queue_id', $bulk_whatsapp_notification_job_queue_id )
        ->orderBy('bulk_whatsapp_notification_job_queue_id', 'asc');

      } else if( $registration_id != 0 ){
        $query = DB::table('bulk_whatsapp_notification_job')
        ->leftJoin('bulk_whatsapp_notification_job_queue','bulk_whatsapp_notification_job.bulk_whatsapp_notification_job_id','=','bulk_whatsapp_notification_job_queue.bulk_whatsapp_notification_job_id')
        ->where('bulk_whatsapp_notification_job_queue.registration_id', $registration_id )
        ->orderBy('bulk_whatsapp_notification_job_queue_id', 'asc');

      }else{       
        $query = DB::table('bulk_whatsapp_notification_job')
        ->leftJoin('bulk_whatsapp_notification_job_queue','bulk_whatsapp_notification_job.bulk_whatsapp_notification_job_id','=','bulk_whatsapp_notification_job_queue.bulk_whatsapp_notification_job_id')
        ->orderBy('bulk_whatsapp_notification_job_queue_id', 'asc');

      }

      $query->when($force != 'yes', function ($query) {
     
        return $query->where('bulk_whatsapp_notification_job_queue.notification_status', 'pending')
        ->where('bulk_whatsapp_notification_job.job_status', 'active');
      });


      $query->when($limit != 0 , function ($query) {
     
        return $query->limit($this->option('limit'));
      });

      $notifications = $query->get();
      
    

      if ($notifications->isEmpty()) {
        $log = "\nNotification queue is empty\n";
        exit($log);
      }
  
      foreach ($notifications  as $notification) {

        $notification_data = DB::table('bulk_whatsapp_notification_job')
        ->leftJoin('bulk_whatsapp_notification_job_queue','bulk_whatsapp_notification_job.bulk_whatsapp_notification_job_id','=','bulk_whatsapp_notification_job_queue.bulk_whatsapp_notification_job_id')
        ->where('bulk_whatsapp_notification_job_queue.bulk_whatsapp_notification_job_queue_id',$notification->bulk_whatsapp_notification_job_queue_id )
        ->first();

      
       
     
  
        // $notification_queue = BulkWhatsappNotificationJobQueue::findOrFail($notification->bulk_whatsapp_notification_job_queue_id);
        if(  $notification_data->notification_status  == 'running'   ){
            $log =  "\nRegistration id: {$notification_data->registration_id} | Queue Id: {$notification_data->bulk_whatsapp_notification_job_queue_id} | Status: Whatsapp sent unsuccessfully | Msg: notification is already running";                  
            echo $log;    
            continue;
        }else  if( $notification_data->notification_status  == 'success'  && $force !='yes' ){        
            $log =  "\nRegistration id: {$notification_data->registration_id} | Queue Id: {$notification_data->bulk_whatsapp_notification_job_queue_id} | Status: Whatsapp sent unsuccessfully | Msg: notification is already completed";                  
            echo $log;    
            continue;
        }else  if( $notification_data->job_status  == 'suspended'   ){
            $log =  "\nRegistration id: {$notification_data->registration_id} | Queue Id: {$notification_data->bulk_whatsapp_notification_job_queue_id} | Status: Whatsapp sent unsuccessfully | Msg: notification is suspended";                  
            echo $log;    
            continue;
        }
        $this->sendBulkWhatsapp($notification_data);
      }

      echo "\n";
    }


    public function sendBulkWhatsapp($notification)
    {

     
        $notification_queue = BulkWhatsappNotificationJobQueue::findOrFail($notification->bulk_whatsapp_notification_job_queue_id);
        $notification_queue->notification_status = 'running';
        $notification_queue->queue_process_start_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
        $notification_queue->save();
    
        $registration_data =  getRegistrationData($notification->registration_id);

        
        if (!empty(DB::table('time_zone')
          ->where('time_zone_id', $registration_data['user_time_zone_id'])
          ->first())) {
          $time_zone_name =   DB::table('time_zone')
            ->where('time_zone_id', $registration_data['user_time_zone_id'])
            ->first()->timezone;
        } else {
          $time_zone_name = '';
        }
    
        $registration_time = '';
        if (isset($registration_data['registration_time'])) {
          $registration_time = convertUtcToTimeZone($registration_data['registration_time'], $time_zone_name);
        }


        $whatsapp_templates= (array) DB::table('whatsapp_templates')
        ->leftJoin('whatsapp_api','whatsapp_api.whatsapp_api_id','=','whatsapp_templates.whatsapp_api_id')
        ->where('whatsapp_template_id', $notification->template_id)
        ->first();

       


        $data = [
          "registration_data" => $registration_data,
          "time_zone_name" => $time_zone_name,
          "registration_time" => $registration_time,
          "whatsapp_api_id" =>json_decode(json_encode($whatsapp_templates),false),
          "notification" => $notification,
        ];


        if( $whatsapp_templates['whatsapp_api_provider'] == 'twilio'  ){
          $this->twilio($data);
        }
        if( $whatsapp_templates['whatsapp_api_provider'] == 'msg91'  ){
          $this->msg91($data);
        }



     


     
     

       

       

       
    
    }

    public function msg91($data){   

        $status = 1;
        $platform_config = json_decode($data['whatsapp_api_id']->config_json,true);

        $whatsapp_sender= (array) DB::table('whatsapp_sender')
        ->where('whatsapp_sender_id',$data['registration_data']['whatsapp_sender_id'])
        ->first();
     
        $whatsapp_templates =(array)  DB::table('whatsapp_templates')
        ->where('whatsapp_api_id',$data['whatsapp_api_id']->whatsapp_api_id)
        ->where('whatsapp_template_id',$data['registration_data']['whatsapp_template_id_on_registration_success'])
        ->first();
  
       
        $template_config_json =   json_decode($whatsapp_templates['template_config_json'] ,true);
  
 
        $mapping_json_code = shortcode($data['registration_data']['registration_number'],$whatsapp_templates['template_short_code_mapping_config_json']);
       

        
   
  
        $template_short_code_mapping_config_json =   json_decode($mapping_json_code ,true);
        
        $component[] = $template_short_code_mapping_config_json['headers'];
        $component[] = $template_short_code_mapping_config_json['body'];
        $component[] = $template_short_code_mapping_config_json['button'];
        
        $mergedJson = array_merge_recursive(...$component);
        $request_json_object = str_replace("\n","" ,json_encode($mergedJson, JSON_PRETTY_PRINT)) ;       
      

        $payload_json_string = '{
          "integrated_number": "'.$whatsapp_sender['sender_number'].'",
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
                    "'. $data['registration_data']['country_code'] . $data['registration_data']['mobile_no'].'"
                  ],
                  "components": '.$request_json_object.'
                }
               
              ]
            },
            "messaging_product": "whatsapp"
          }
        }';
     
     
     dd( $payload_json_string);
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
            "authkey: {$platform_config['authkey']}",
            "content-type: application/json"
          ],
        ]);
        
        $response =  curl_exec($curl);
        $err = curl_error($curl);       
        curl_close($curl);

        $response_json = json_decode($response);

       
        if ($err) {  
          $log =  "\nRegistration id: {$data['notification']->registration_id} | Queue Id: {$data['notification']->bulk_whatsapp_notification_job_queue_id} | Whatsapp Noitification Platform: msg91 | Status: failed | Msg: {$err}"; 
        
          echo $log;
  
          $notification_queue = BulkWhatsappNotificationJobQueue::findOrFail($data['notification']->bulk_whatsapp_notification_job_queue_id);
          $notification_queue->notification_status = 'failed';
          $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
          $notification_queue->notification_log = $log;
          $notification_queue->save();    



        } else if ($response_json->hasError) {


          $log =  "\nRegistration id: {$data['notification']->registration_id} | Queue Id: {$data['notification']->bulk_whatsapp_notification_job_queue_id} |  Whatsapp Noitification Platform: msg91 | Api response: {$response} | Mobile no: +{$data['registration_data']['country_code']}{$data['registration_data']['mobile_no']}";  
          
          echo $log;
          
          $notification_queue = BulkWhatsappNotificationJobQueue::findOrFail($data['notification']->bulk_whatsapp_notification_job_queue_id);
          $notification_queue->notification_status = 'failed';
          $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
          $notification_queue->notification_log = $log;
          $notification_queue->save(); 


        }else{
          $log =  "\nRegistration id: {$data['notification']->registration_id} | Queue Id: {$data['notification']->bulk_whatsapp_notification_job_queue_id} |  Whatsapp Noitification Platform: msg91 | Api response: {$response} | Mobile no: +{$data['registration_data']['country_code']}{$data['registration_data']['mobile_no']}";  
          
          echo $log;
          
          $notification_queue = BulkWhatsappNotificationJobQueue::findOrFail($data['notification']->bulk_whatsapp_notification_job_queue_id);
          $notification_queue->notification_status = 'success';
          $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
          $notification_queue->notification_log = $log;
          $notification_queue->save(); 

        }
       

  
  
      }
  
    public function twilio($data){


      $status = 1;

      $platform_config = json_decode($data['whatsapp_api_id']->config_json,true);

      $whatsapp_sender= (array) DB::table('whatsapp_sender')
      ->where('whatsapp_sender_id',$data['registration_data']['whatsapp_sender_id'])
      ->first();
   
      $whatsapp_templates =(array)  DB::table('whatsapp_templates')
      ->where('whatsapp_api_id',$data['whatsapp_api_id']->whatsapp_api_id)
      ->where('whatsapp_template_id',$data['registration_data']['whatsapp_template_id_on_registration_success'])
      ->first();


      $template_config_json =   json_decode($whatsapp_templates['template_config_json'] ,true);


      $mapping_json_code = shortcode($data['registration_data']['registration_number'],$whatsapp_templates['template_short_code_mapping_config_json']);
     
    

      $template_short_code_mapping_config_json =   json_decode($mapping_json_code ,true);

      $template_short_code_mapping_config_json_decode = [];  //pass this array to whatsapp template

      if( !empty($template_short_code_mapping_config_json) ){
        foreach( $template_short_code_mapping_config_json as $key=>$value ){
           $key = str_replace("{{","",$key);          
           $key = str_replace("}}","",$key);
           $template_short_code_mapping_config_json_decode[$key] = $value;
          
     
        }
      
      } 
      Log::info('whatsapp info: ', ["data:"=> $template_short_code_mapping_config_json_decode  ] );
      $sid = $platform_config['account_sid'];
      $token = $platform_config['auth_token'];
      $twilio = new Client($sid, $token);

      try{
        $message = $twilio->messages
        ->create(
            "whatsapp:" .'+'. $data['registration_data']['country_code'] . $data['registration_data']['mobile_no'], // to
            [
                "contentSid" => $template_config_json['content_template_sid'],
                // "contentSid" => "HX4f2a2fcfe1b7bd46c79961e8dff3e781",
                "from" => "whatsapp:" .'+'. $whatsapp_sender['sender_number'],
                "contentVariables" => json_encode($template_short_code_mapping_config_json_decode),
                "messagingServiceSid" => $template_config_json['messaging_service_sid']
            ]
        );
      }catch(\Exception $e){
        $status = 0;
        $log =  "\nRegistration id: {$data['notification']->registration_id} | Queue Id: {$data['notification']->bulk_whatsapp_notification_job_queue_id} |  Whatsapp Noitification Platform: twilio | Status: failed | Msg: {$e->getMessage()}"; 
        
        echo $log;

        $notification_queue = BulkWhatsappNotificationJobQueue::findOrFail($data['notification']->bulk_whatsapp_notification_job_queue_id);
        $notification_queue->notification_status = 'failed';
        $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
        $notification_queue->notification_log = $log;
        $notification_queue->save();    

       

      }


      if($status){

        $log =  "\nRegistration id: {$data['notification']->registration_id} | Queue Id: {$data['notification']->bulk_whatsapp_notification_job_queue_id} | Whatsapp Noitification Platform: twilio | Status: success | Msg: Whatsapp sent successfully | Api response: {$message} | Mobile no: +{$data['registration_data']['country_code']}{$data['registration_data']['mobile_no']}";  
        echo $log;
        $notification_queue = BulkWhatsappNotificationJobQueue::findOrFail($data['notification']->bulk_whatsapp_notification_job_queue_id);
        $notification_queue->notification_status = 'success';
        $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
        $notification_queue->notification_log = $log;
        $notification_queue->save(); 
      }
     
     


    }





}
