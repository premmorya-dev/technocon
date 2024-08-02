<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use DOMDocument;
use Carbon\Carbon;
use EmsApi\Config;
use EmsApi\Base;
use EmsApi\Cache\File;
use EmsApi\Endpoint\Lists;
use EmsApi\Params;

use App\Models\NotificationJobQueue;

class ZoikmailProgramListSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:zoikmail-program-list-sync {limit?}';

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
      $limit = $this->argument('limit');
  
      $notifications = DB::table('notification_job_queue')
        ->where('notification_status', 'pending')
        ->where('notification_job_queue_type', 'zoikmail-program-list-sync')
        ->orderBy('notification_job_queue_id', 'asc') // Change 'created_at' to the appropriate column
        ->limit($limit) // Set the limit to any number you need
        ->get();
 
  
      if ($notifications->isEmpty()) {
        $log = "\nNotification queue is empty\n";
        exit($log);
      }
  
      foreach ($notifications  as $notification) {
  
        $notification_queue = NotificationJobQueue::findOrFail($notification->notification_job_queue_id);
  
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

      echo "\n";
    }
  
  
    public function zoikProgramListSync($notification)
    {
  
      $notification_queue = NotificationJobQueue::findOrFail($notification->notification_job_queue_id);
      $notification_queue->notification_status = 'running';
      $notification_queue->queue_process_start_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
      $notification_queue->save();
  
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
        ->where('event_program_registration.registration_id', $notification->registration_id)
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
  
      if (!empty(DB::table('time_zone')
        ->where('time_zone_id', $registration_data['time_zone_id'])
        ->first())) {
        $time_zone_name =   DB::table('time_zone')
          ->where('time_zone_id', $registration_data['time_zone_id'])
          ->first()->timezone;
      } else {
        $time_zone_name = '';
      }
  
      $registration_time = '';
      if (isset($registration_data['registration_time'])) {
        $registration_time = convertUtcToTimeZone($registration_data['registration_time'], $time_zone_name);
      }
  
      if (isset($registration_data['zoik_app_id']) && isset($registration_data['zoik_app_program_list_uid']) && $registration_data['zoik_app_program_list_uid']) {
        $zoik_app_setting =   DB::table('zoik_app_setting')
          ->where('zoik_app_id', $registration_data['zoik_app_id'])
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
  
          if ($registration_data['zoik_app_program_list_field_mapping']) {
  
            $registration_data['zoik_app_program_list_field_mapping'] = json_encode(json_decode($registration_data['zoik_app_program_list_field_mapping']), JSON_UNESCAPED_UNICODE);
            $field_name = json_decode($registration_data['zoik_app_program_list_field_mapping'], true);
  
  
            $endpointLists = new \EmsApi\Endpoint\Lists();
            $program_list_response = $endpointLists->getList($registration_data['zoik_app_program_list_uid']);
            $program_list_param = new \EmsApi\Params($program_list_response->body);
            $shortcode_value = getShortcode($registration_data['registration_number']);
            $sync_data = [];
  
            // dd( $field_name);
            if (!empty($field_name)) {
              foreach ($field_name as $shortcode_tag => $zoik_tag) {
  
                $value = $shortcode_value[$shortcode_tag];
                $key = str_replace("[", '', $zoik_tag);
                $key = str_replace("]", '', $key);
                $sync_data[$key] = $value;
              }
            }
            

            if ($program_list_param->itemAt('status') == 'success') {
              $endpointListSubscribers = new \EmsApi\Endpoint\ListSubscribers();
              $search_program_response = $endpointListSubscribers->emailSearch($registration_data['zoik_app_program_list_uid'], $registration_data['registered_email']);
  
  
              if ($search_program_response) {
                $search_program_param = new \EmsApi\Params($search_program_response->body);
  
                if ($search_program_param->itemAt('status') ==  'success') {
  
  
  
                  $update_response = $endpointListSubscribers->update($registration_data['zoik_app_program_list_uid'], $search_program_param->itemAt('data')['subscriber_uid'], $sync_data);
  
  
                  if ($update_response) {
                    $update_param = new \EmsApi\Params($update_response->body);
  
                    if ($update_param->itemAt('data')) {
  
                      $log = "\nRegistration Id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: success | Msg: Program list subscriber UID updated | List UID: {$registration_data['zoik_app_program_list_uid']} | Subscriber UID: {$search_program_param->itemAt('data')['subscriber_uid']}";
  
                      echo $log;
  
                      $notification_queue = NotificationJobQueue::findOrFail($notification->notification_job_queue_id);
                      $notification_queue->notification_status = 'success';
                      $notification_queue->queue_process_end_datetime = Carbon::now('UTC')->format('Y-m-d h:i:s');
                      $notification_queue->notification_log = $log;
                      $notification_queue->save();
                    }
                  }
                } else {
  
  
                  $create_program_list_response = $endpointListSubscribers->create($registration_data['zoik_app_program_list_uid'], $sync_data);
  
  
                  if ($create_program_list_response) {
                    $create_program_list_param = new \EmsApi\Params($create_program_list_response->body);
  
  
  
                    $log = "\nRegistration Id: {$notification->registration_id} | Notification Type: {$notification->notification_job_queue_type} | Status: success | Msg: Program list subscriber UID created | List UID: {$registration_data['zoik_app_program_list_uid']} | Subscriber UID: {$create_program_list_param->itemAt('data')['record']['subscriber_uid']}";
  
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
    }
}
