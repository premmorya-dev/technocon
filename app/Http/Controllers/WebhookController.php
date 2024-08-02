<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\IncomingWebhookJobQueue;
class WebhookController extends Controller
{
    public function  index(Request $request,$webhook_handle)
    {

       
        $error=0;
        $log='';
       Log::channel('webhook')->info("Webhook Start: {$webhook_handle}");
       $webhook_registry =  DB::table('incoming_webhook_registry')
            ->where('incoming_webhook_url','webhook/'.$webhook_handle)
            ->first();

      
        if(empty($webhook_registry)){
            $log = "Incoming webhook handle not found | webhook handle: {$webhook_handle}";
            Log::channel('webhook')->info($log);
            return ;
        } 

        // Log::channel('webhook')->info("{$webhook_registry->incoming_webhook_name} Webhook Payload:", $request->all());
        $headers = $request->headers->all();       
       Log::channel('webhook')->info("webhook {$webhook_registry->incoming_webhook_name}: ", ["header:"=> $headers ] ); 
        $payload = $request->getContent();
       Log::channel('webhook')->info("webhook {$webhook_registry->incoming_webhook_name}: ", ["payload:"=> $payload ] ); 
       
       

        $payload  =  json_decode( $payload , true);
       
        $webhook_registry_action =  DB::table('incoming_webhook_registry_to_action')
        ->where('incoming_webhook_id', $webhook_registry->incoming_webhook_id)
        ->get();

        if(empty($webhook_registry_action)){
            Log::channel('webhook')->info("webhook {$webhook_registry->incoming_webhook_name}: No webhook action found " ); 
            return;
        }
        if($webhook_registry_action){
            foreach($webhook_registry_action as $webhook_action){

                if($payload  ){
                    $log = "Incoming webhook id: {$webhook_registry->incoming_webhook_id} | name: {$webhook_registry->incoming_webhook_name} | data inserted successfully in webhook job queue";
                    Log::channel('webhook')->info($log);
                    IncomingWebhookJobQueue::create([
                        'incoming_webhook_id' => $webhook_registry->incoming_webhook_id,
                        'incoming_webhook_action' => $webhook_action->incoming_webhook_action,
                        'queue_processing' => $webhook_registry->queue_processing,
                        'incoming_webhook_header_json' => json_encode($headers),
                        'incoming_webhook_body_json' => json_encode( $payload) ,
                        'remote_address' => $_SERVER['REMOTE_ADDR'] ?? '' ,
                        'queue_process_status' => 'pending',
                        'added_datetime' => Carbon::now('UTC')->format('Y-m-d h:i:s'),
                        'queue_process_start_datetime' => NULL,
                        'queue_process_end_datetime' => NULL,
                        'webhook_log' => $log,
                        'process_log' => '',
                      
                    ]);
                }else{
                    $log = "Incoming webhook id: {$webhook_registry->incoming_webhook_id} | name: {$webhook_registry->incoming_webhook_name} | data inserted failed in webhook queue | reason payload empty";
                    Log::channel('webhook')->info($log);
                }
            }

        }



        
           
       

     


      




    }


    private function isValidSignature($payload, $signature, $secret, $webhookSecret)
    {

       
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        
        return hash_equals($expectedSignature, $signature);
    }


    public function inBoundWhatsappWebhook(Request $request)
    {

        Log::channel('webhook')->info('inbound whatsapp webhook: ', ["payload:"=> $request ] );
    }
}
