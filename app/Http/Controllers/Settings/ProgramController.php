<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgramModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Rules\Webhook;
use Illuminate\Validation\ValidationException;
class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    { 
        $query = ProgramModel::query();
        $query->leftJoin('event', 'event_program.event_id', '=', 'event.event_id')
        ->select('event_program.*', 'event.*') ;
        $query->leftJoin('event_program_type', 'event_program_type.event_program_type_id', '=', 'event_program.event_program_type_id')
        ->select('event_program.*', 'event_program_type.*') ;
        // dd($query);

        // Apply filters
        if ($request->filled('filter_program_id')) {
            $query->where('event_program.program_id', 'like','%'. $request->input('filter_program_id') .'%');
        }
        if ($request->filled('filter_event_backend_name')) {
            $query->where('event_backend_name', 'like', '%' . $request->input('filter_event_backend_name') . '%');
        }
        if ($request->filled('filter_program_name')) {
            $query->where('program_name', 'like', '%' . $request->input('filter_program_name') . '%');
        }
        if ($request->filled('filter_status')) {
            $query->where('status', 'like', '%' . $request->input('filter_status') . '%');
        }
        if ($request->filled('filter_program_type')) {
            $query->where('event_program_type.event_program_title', 'like', '%' . $request->input('filter_program_type') . '%');
        }

        // $sql = $query->toSql();
        // dd($sql);
        // $bindings = $query->getBindings();
        
        // $fullSql = vsprintf(str_replace('?', "'%s'", $sql), $bindings);
        
        // dd($fullSql);


        $programs = $query->sortable()->paginate(setting('pagination_limit'));

        //  dd($programs);
      
        return view('pages/program.list', compact('programs'));

       
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    public function create()
    {
        $data['events'] = DB::table('event')->get();        
        $data['students_certificate_statuses'] = DB::table('students_certificate_status')->get();
        $data['students_invitation_statuses'] = DB::table('students_invitation_status')->get();
        $data['students_registration_statuses'] = DB::table('students_registration_status')->get();
        $data['students_selection_statuses'] = DB::table('students_selection_status')->get();
        $data['time_zones'] = DB::table('time_zone')->get();
        $data['currency_settings'] = DB::table('currency_settings')->get();
        $data['smtp_settings'] = DB::table('smtp_settings')->get();
        $data['email_templates'] = DB::table('email_templates')->get();
        $data['payment_gateway_configs'] = DB::table('payment_gateway_config')->where('status','active')->get();
        $data['zoik_app_settings'] = DB::table('zoik_app_setting')->get();
        $data['payment_statuses'] = DB::table('payment_status')->get();
        $data['whatsapp_apis'] = DB::table('whatsapp_api')->get();
        $data['whatsapp_senders'] = DB::table('whatsapp_sender')->get();
        $data['whatsapp_templates'] = DB::table('whatsapp_templates')->get();
        
        $data['registration_seo_urls_prefix'] = route('events.registration');
        $data['program_registration_seo_urls'] = DB::table('event_program_registration_seo_url')->get();
        
        $data['event_program_locations'] = DB::table('event_program_location')->get();
        $data['program_type'] = DB::table('event_program_type')->get();
        $data['event_program_certificates'] = DB::table('event_program_certificate')->get();

        // $data['program'] = ProgramModel::find($program_id);

        // dd($data);
        
        return view('pages/program.add',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

 

        $validator =   $request->validate([
            'time_zone_id' => 'required',
            'event_id' => 'required',
            'registration_seo_url_id' => 'required',
            'program_location_id' => 'required',
            'program_code' => 'required|string|min:4|max:4|unique:event_program,program_code',               
            'program_name' => 'required|max:100',
            'program_name_for_certificate' => 'required|string|max:250',
            'program_name_sms_dlt' => 'required|max:29',
            'status' => 'required',
            'rstatus' => 'required',
            'event_program_type_id' => 'required',
            'program_duration' => 'required',
            'program_duration_time_unit' => 'required',           
            'registration_no_prefix' => 'required|max:255',
            'payment_last_date' => 'required|date_format:Y-m-d',
            'start_dates' => 'required|date_format:Y-m-d',
            'end_dates' => 'required|date_format:Y-m-d',
            'start_time' => 'required',
            'end_time' => 'required',
            'level' => 'required',
            'fees' => 'required|numeric',
            'currency_id' => 'required',
            'fees_inclusive_tax' => 'required',
            'tax_rate' => 'required|numeric',
            'payment_gateway_fee_rate' => 'required|numeric',
            'max_member' => 'required|numeric',
            'any_special_terms' => 'required',
            'online_content' => 'required',
            'content_links' => 'required',
            'registration_page_url' => 'required',
            'registration_page_short_url' => 'required',
            'registration_page_root_domain' => 'required',
            'event_website' => 'required',
            'program_details_page_url' => 'required',
            'program_details_page_short_url' => 'required',
            'enable_payment_link' => 'required',
            'payment_gateway_id' => 'required',
            'sms_gateway_id' => 'required',
            'smtp_id' => 'required',
            'registration_email_template_id' => 'required',
            'registration_sms_template_id' => 'required',
            'payment_email_template_id' => 'required',
            'payment_sms_template_id' => 'required',
            'currency_exchange_gateway_id' => 'required',
            'short_url_domain' => 'required',
            'contact_us_email' => 'required|email',
            'contact_us_mobile' => 'required|numeric',
            'zoik_app_program_list_uid' => 'required|max:13',
            'zoik_app_common_list_uid' => 'required|max:13',
            'zoik_app_id' => 'required',
            'zoik_app_program_list_field_mapping' => 'required|json',
            'zoik_app_common_list_field_mapping' => 'required|json',
            'selection_status_after_registartion' => 'required',
            'selection_status_after_payment' => 'required',
            'invitation_status_after_registartion' => 'required',
            'invitation_status_after_payment' => 'required',
            'payment_status_after_registration' => 'required',
            'payment_status_after_payment' => 'required',
            'email_header_banner_url' => 'required|max:255',
            'email_header_banner_alt' => 'required|max:255',
            'short_url_api_id' => 'required',
            'short_url_channel_id' => 'required',
            'whatsapp_api_id' => 'required',
            'whatsapp_sender_id' => 'required',
            'whatsapp_notification_on_registration' => 'required',
            'sms_notification_on_registration' => 'required',
            'email_notification_on_registration' => 'required',
            'whatsapp_notification_on_payment' => 'required',
            'sms_notification_on_payment' => 'required',
            'email_notification_on_payment' => 'required',
            'whatsapp_template_id_on_registration_success' => 'required',
            'whatsapp_template_id_on_payment_success' => 'required',
            'enable_gate_pass' => 'required',
            'enable_gate_pass_on_selection_status_id' => 'required',
            'enable_address_field' => 'required',
            'enable_address_field_on_selection_status_id' => 'required',
            'enable_digital_certificate' => 'required',
            'program_certificate_id' => 'required',
            'enable_digital_certificate_on_selection_status_id' => 'required',
            'registration_outgoing_webhooks' => 'json',
         
           


        ],[],[
            'event_id' => 'Event Name',
            'time_zone_id' => 'Time Zone',
            'registration_seo_url_id' => 'Registration Seo Url',
            'program_location_id' => 'Program Location',
            'currency_id' => 'Currency',
            'payment_gateway_id' => 'Payment Gateway',
            'sms_gateway_id' => 'Sms Gateway',
            'smtp_id' => 'Smtp',
            'registration_email_template_id' => 'Registration Email Template',
            'registration_sms_template_id' => 'Registration Sms Template',
            'payment_email_template_id' => 'Payment Email Template',
            'payment_sms_template_id' => 'Payment Sms Template',
            'currency_exchange_gateway_id' => 'Currency Exchange Gateway',
        
        ]);
   

        $webhook = '';    
        if( isset($request->webhook) && $request->webhook){
            $webhook = json_encode($request->webhook) ;
        }
      

        $program = ProgramModel::create([
          
                'time_zone_id' => $request->time_zone_id,
                'event_id' => $request->event_id,
                'registration_seo_url_id' => 1,
                'program_location_id' => $request->program_location_id,
                'program_code' => $request->program_code,
                'program_name' => $request->program_name,
                'program_name_for_certificate' => $request->program_name_for_certificate,
                'program_name_sms_dlt' => $request->program_name_sms_dlt,
                'status' => $request->status,
                'rstatus' => $request->rstatus,
                'event_program_type_id' => $request->event_program_type_id,
                'program_certificate_id' => $request->program_certificate_id,
                'program_duration' => $request->program_duration,
                'program_duration_time_unit' => $request->program_duration_time_unit,
                'registration_no_prefix' => $request->registration_no_prefix,
                'payment_last_date' => $request->payment_last_date,
                'start_dates' => $request->start_dates,
                'end_dates' => $request->end_dates,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'level' => $request->level,
                'fees' => $request->fees,
                'currency_id' => $request->currency_id,
                'fees_inclusive_tax' => $request->fees_inclusive_tax,
                'tax_rate' => $request->tax_rate,
                'payment_gateway_fee_rate' => $request->payment_gateway_fee_rate,
                'max_member' => $request->max_member,
                'any_special_terms' => $request->any_special_terms,
                'online_content' => $request->online_content,
                'content_links' => $request->content_links,
                'registration_page_url' => $request->registration_page_url,
                'registration_page_short_url' => $request->registration_page_short_url,
                'registration_page_root_domain' => $request->registration_page_root_domain,
                'event_website' => $request->event_website,
                'program_details_page_url' => $request->program_details_page_url,
                'program_details_page_short_url' => $request->program_details_page_short_url,
                'enable_payment_link' => $request->enable_payment_link,
                'payment_gateway_id' => $request->payment_gateway_id,
                'sms_gateway_id' => $request->sms_gateway_id,
                'smtp_id' => $request->smtp_id,
                'registration_email_template_id' => $request->registration_email_template_id,
                'registration_sms_template_id' => $request->registration_sms_template_id,
                'payment_email_template_id' => $request->payment_email_template_id,
                'payment_sms_template_id' => $request->payment_sms_template_id,
                'currency_exchange_gateway_id' => $request->currency_exchange_gateway_id,
                'short_url_domain' => $request->short_url_domain,
                'contact_us_email' => $request->contact_us_email,
                'contact_us_mobile' => $request->contact_us_mobile,
                'zoik_app_program_list_uid' => $request->zoik_app_program_list_uid,
                'zoik_app_common_list_uid' => $request->zoik_app_common_list_uid,
                'zoik_app_id' => $request->zoik_app_id,
                'zoik_app_program_list_field_mapping' => $request->zoik_app_program_list_field_mapping,
                'zoik_app_common_list_field_mapping' => $request->zoik_app_common_list_field_mapping,
                'selection_status_after_registartion' => $request->selection_status_after_registartion,
                'selection_status_after_payment' => $request->selection_status_after_payment,
                'invitation_status_after_registartion' => $request->invitation_status_after_registartion,
                'invitation_status_after_payment' => $request->invitation_status_after_payment,
                'payment_status_after_registration' => $request->payment_status_after_registration,
                'payment_status_after_payment' => $request->payment_status_after_payment,
                'email_header_banner_url' => $request->email_header_banner_url,
                'email_header_banner_alt' => $request->email_header_banner_alt,
                'short_url_api_id' => $request->short_url_api_id,
                'short_url_channel_id' => $request->short_url_channel_id,
                'whatsapp_api_id' => $request->whatsapp_api_id,
                'whatsapp_sender_id' => $request->whatsapp_sender_id,
                'whatsapp_notification_on_registration' => $request->whatsapp_notification_on_registration,
                'sms_notification_on_registration' => $request->sms_notification_on_registration,
                'email_notification_on_registration' => $request->email_notification_on_registration,
                'whatsapp_notification_on_payment' => $request->whatsapp_notification_on_payment,
                'sms_notification_on_payment' => $request->sms_notification_on_payment,
                'email_notification_on_payment' => $request->email_notification_on_payment,
                'whatsapp_template_id_on_registration_success' => $request->whatsapp_template_id_on_registration_success,
                'whatsapp_template_id_on_payment_success' => $request->whatsapp_template_id_on_payment_success,
                'enable_gate_pass' => $request->enable_gate_pass,
                'enable_gate_pass_on_selection_status_id' => $request->enable_gate_pass_on_selection_status_id,
                'enable_address_field' => $request->enable_address_field,
                'enable_address_field_on_selection_status_id' => $request->enable_address_field_on_selection_status_id,
                'enable_digital_certificate' => $request->enable_digital_certificate,
                'enable_digital_certificate_on_selection_status_id' => $request->enable_digital_certificate_on_selection_status_id,
                'registration_outgoing_webhooks' => $request->registration_outgoing_webhooks,
                'addional_email_notification' => $request->addional_email_notification,
        ]);

        return redirect()->route('program.list')->with('success', 'Program created successfully.');
    }

   

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($program_id)
    {
        $data['events'] = DB::table('event')->get();        
        $data['students_certificate_statuses'] = DB::table('students_certificate_status')->get();
        $data['students_invitation_statuses'] = DB::table('students_invitation_status')->get();
        $data['students_registration_statuses'] = DB::table('students_registration_status')->get();
        $data['students_selection_statuses'] = DB::table('students_selection_status')->get();
        $data['time_zones'] = DB::table('time_zone')->get();
        $data['currency_settings'] = DB::table('currency_settings')->get();
        $data['smtp_settings'] = DB::table('smtp_settings')->get();
        $data['email_templates'] = DB::table('email_templates')->get();
        $data['payment_gateway_configs'] = DB::table('payment_gateway_config')->where('status','active')->get();
        $data['zoik_app_settings'] = DB::table('zoik_app_setting')->get();
        $data['payment_statuses'] = DB::table('payment_status')->get();
        $data['whatsapp_apis'] = DB::table('whatsapp_api')->get();
        $data['whatsapp_senders'] = DB::table('whatsapp_sender')->get();
        $data['whatsapp_templates'] = DB::table('whatsapp_templates')->get();
        
        $data['registration_seo_urls_prefix'] = route('events.registration');
        $data['program_registration_seo_urls'] = DB::table('event_program_registration_seo_url')->get();
        
        $data['event_program_locations'] = DB::table('event_program_location')->get();
        $data['program_type'] = DB::table('event_program_type')->get();
        $data['event_program_certificates'] = DB::table('event_program_certificate')->get();

        $data['program'] = ProgramModel::find($program_id);
        $data['webhook'] = '';
        if(isset($data['program']['registration_outgoing_webhooks']) && $data['program']['registration_outgoing_webhooks'] ){
            $data['webhook']  =  json_decode($data['program']['registration_outgoing_webhooks'],true);            
        }
        // dd($data);
       return view('pages/program.edit', compact('data'));

    }

    public function view(Request $request)
    {
        
      
        $data['events'] = DB::table('event')->get();        
        $data['students_certificate_statuses'] = DB::table('students_certificate_status')->get();
        $data['students_invitation_statuses'] = DB::table('students_invitation_status')->get();
        $data['students_registration_statuses'] = DB::table('students_registration_status')->get();
        $data['students_selection_statuses'] = DB::table('students_selection_status')->get();
        $data['time_zones'] = DB::table('time_zone')->get();
        $data['currency_settings'] = DB::table('currency_settings')->get();
        $data['smtp_settings'] = DB::table('smtp_settings')->get();
        $data['email_templates'] = DB::table('email_templates')->get();
        $data['payment_gateway_configs'] = DB::table('payment_gateway_config')->where('status','active')->get();
        $data['zoik_app_settings'] = DB::table('zoik_app_setting')->get();
        $data['payment_statuses'] = DB::table('payment_status')->get();
        $data['whatsapp_apis'] = DB::table('whatsapp_api')->get();
        $data['whatsapp_senders'] = DB::table('whatsapp_sender')->get();
        $data['whatsapp_templates'] = DB::table('whatsapp_templates')->get();
        $data['program_type'] = DB::table('event_program_type')->get();
        $data['program'] = ProgramModel::find($request->program_id);
        $data['event_program_certificates'] = DB::table('event_program_certificate')->get();
        $data['webhook'] = '';
        if(isset($data['program']['registration_outgoing_webhooks']) && $data['program']['registration_outgoing_webhooks'] ){
            $data['webhook']  =  json_decode($data['program']['registration_outgoing_webhooks'],true);            
        }
        // dd($data['program']);
        // dd(view('pages/program.view', compact('data')));
       return view('pages/program.view', compact('data'));

    }

    // /**
    //  * Update the specified resource in storage.
    //  */
    public function update(Request $request,$program_id)
    {

 
            $validator =   $request->validate([
                'time_zone_id' => 'required',
                'event_id' => 'required',
                'registration_seo_url_id' => 'required',
                'program_location_id' => 'required',
                'program_code' => 'required|string|min:4|max:4',                   
                'program_name' => 'required|max:100',
                'program_name_for_certificate' => 'required|string|max:250',
                'program_name_sms_dlt' => 'required|max:29',
                'status' => 'required',
                'rstatus' => 'required',
                'event_program_type_id' => 'required',   
                'program_certificate_id' => 'required', 
                'program_duration' => 'required',
                'program_duration_time_unit' => 'required',           
                'registration_no_prefix' => 'required|max:255',
                'payment_last_date' => 'required|date_format:Y-m-d',
                'start_dates' => 'required|date_format:Y-m-d',
                'end_dates' => 'required|date_format:Y-m-d',
                'start_time' => 'required',
                'end_time' => 'required',
                'level' => 'required',
                'fees' => 'required|numeric',
                'currency_id' => 'required',
                'fees_inclusive_tax' => 'required',
                'tax_rate' => 'required|numeric',
                'payment_gateway_fee_rate' => 'required|numeric',
                'max_member' => 'required|numeric',
                'any_special_terms' => 'required',
                'online_content' => 'required',
                'content_links' => 'required',
                'registration_page_url' => 'required',
                'registration_page_short_url' => 'required',
                'registration_page_root_domain' => 'required',
                'event_website' => 'required',
                'program_details_page_url' => 'required',
                'program_details_page_short_url' => 'required',
                'enable_payment_link' => 'required',
                'payment_gateway_id' => 'required',
                'sms_gateway_id' => 'required',
                'smtp_id' => 'required',
                'registration_email_template_id' => 'required',
                'registration_sms_template_id' => 'required',
                'payment_email_template_id' => 'required',
                'payment_sms_template_id' => 'required',
                'currency_exchange_gateway_id' => 'required',
                'short_url_domain' => 'required',
                'contact_us_email' => 'required|email',
                'contact_us_mobile' => 'required|numeric',
                'zoik_app_program_list_uid' => 'required|max:13',
                'zoik_app_common_list_uid' => 'required|max:13',
                'zoik_app_id' => 'required',
                'zoik_app_program_list_field_mapping' => 'required|json',
                'zoik_app_common_list_field_mapping' => 'required|json',
                'selection_status_after_registartion' => 'required',
                'selection_status_after_payment' => 'required',
                'invitation_status_after_registartion' => 'required',
                'invitation_status_after_payment' => 'required',
                'payment_status_after_registration' => 'required',
                'payment_status_after_payment' => 'required',
                'email_header_banner_url' => 'required|max:255',
                'email_header_banner_alt' => 'required|max:255',
                'short_url_api_id' => 'required',
                'short_url_channel_id' => 'required',
                'whatsapp_api_id' => 'required',
                'whatsapp_sender_id' => 'required',
                'whatsapp_notification_on_registration' => 'required',
                'sms_notification_on_registration' => 'required',
                'email_notification_on_registration' => 'required',
                'whatsapp_notification_on_payment' => 'required',
                'sms_notification_on_payment' => 'required',
                'email_notification_on_payment' => 'required',
                'whatsapp_template_id_on_registration_success' => 'required',
                'whatsapp_template_id_on_payment_success' => 'required',
                'enable_gate_pass' => 'required',
                'enable_gate_pass_on_selection_status_id' => 'required',
                'enable_address_field' => 'required',
                'enable_address_field_on_selection_status_id' => 'required',
                'enable_digital_certificate' => 'required',
                'enable_digital_certificate_on_selection_status_id' => 'required',
                'registration_outgoing_webhooks' => 'json',
             
               
    
    
            ],[],[
                'event_id' => 'Event Name',
                'time_zone_id' => 'Time Zone',
                'registration_seo_url_id' => 'Registration Seo Url',
                'program_location_id' => 'Program Location',
                'currency_id' => 'Currency',
                'payment_gateway_id' => 'Payment Gateway',
                'sms_gateway_id' => 'Sms Gateway',
                'smtp_id' => 'Smtp',
                'registration_email_template_id' => 'Registration Email Template',
                'registration_sms_template_id' => 'Registration Sms Template',
                'payment_email_template_id' => 'Payment Email Template',
                'payment_sms_template_id' => 'Payment Sms Template',
                'currency_exchange_gateway_id' => 'Currency Exchange Gateway',
            
            ]);
       
      

     

        $webhook = '';    
        if( isset($request->webhook) && $request->webhook){
            $webhook = json_encode($request->webhook) ;
        }

        $program = ProgramModel::findOrFail($program_id);
        $program->time_zone_id = $request->time_zone_id;
        $program->event_id = $request->event_id;

        $program->registration_seo_url_id = $request->registration_seo_url_id;
        $program->program_location_id = $request->program_location_id;
        $program->program_code = $request->program_code;
        $program->program_name = $request->program_name;
        $program->program_name_for_certificate = $request->program_name_for_certificate;
        $program->program_name_sms_dlt = $request->program_name_sms_dlt;
        $program->status = $request->status;
        $program->rstatus = $request->rstatus;
        $program->event_program_type_id = $request->event_program_type_id;
        $program->program_certificate_id = $request->program_certificate_id;
        $program->program_duration = $request->program_duration;
        $program->program_duration_time_unit = $request->program_duration_time_unit;
        $program->registration_no_prefix = $request->registration_no_prefix;
        $program->payment_last_date = $request->payment_last_date;
        $program->start_dates = $request->start_dates;
        $program->end_dates = $request->end_dates;
        $program->start_time = $request->start_time;
        $program->end_time = $request->end_time;
        $program->level = $request->level;
        $program->fees = $request->fees;
        $program->currency_id = $request->currency_id;
        $program->fees_inclusive_tax = $request->fees_inclusive_tax;
        $program->tax_rate = $request->tax_rate;
        $program->payment_gateway_fee_rate = $request->payment_gateway_fee_rate;
        $program->max_member = $request->max_member;
        $program->any_special_terms = $request->any_special_terms;
        $program->online_content = $request->online_content;
        $program->content_links = $request->content_links;
        $program->registration_page_url = $request->registration_page_url;
        $program->registration_page_short_url = $request->registration_page_short_url;
        $program->registration_page_root_domain = $request->registration_page_root_domain;
        $program->event_website = $request->event_website;
        $program->program_details_page_url = $request->program_details_page_url;
        $program->program_details_page_short_url = $request->program_details_page_short_url;
        $program->enable_payment_link = $request->enable_payment_link;
        $program->payment_gateway_id = $request->payment_gateway_id;
        $program->sms_gateway_id = $request->sms_gateway_id;
        $program->smtp_id = $request->smtp_id;
        $program->registration_email_template_id = $request->registration_email_template_id;
        $program->registration_sms_template_id = $request->registration_sms_template_id;
        $program->payment_email_template_id = $request->payment_email_template_id;
        $program->payment_sms_template_id = $request->payment_sms_template_id;
        $program->currency_exchange_gateway_id = $request->currency_exchange_gateway_id;
        $program->short_url_domain = $request->short_url_domain;
        $program->contact_us_email = $request->contact_us_email;
        $program->contact_us_mobile = $request->contact_us_mobile;
        $program->zoik_app_program_list_uid = $request->zoik_app_program_list_uid;
        $program->zoik_app_common_list_uid = $request->zoik_app_common_list_uid;
        $program->zoik_app_id = $request->zoik_app_id;
        $program->zoik_app_program_list_field_mapping = $request->zoik_app_program_list_field_mapping;
        $program->zoik_app_common_list_field_mapping = $request->zoik_app_common_list_field_mapping;
        $program->selection_status_after_registartion = $request->selection_status_after_registartion;
        $program->selection_status_after_payment = $request->selection_status_after_payment;
        $program->invitation_status_after_registartion = $request->invitation_status_after_registartion;
        $program->invitation_status_after_payment = $request->invitation_status_after_payment;
        $program->payment_status_after_registration = $request->payment_status_after_registration;
        $program->payment_status_after_payment = $request->payment_status_after_payment;
        $program->email_header_banner_url = $request->email_header_banner_url;
        $program->email_header_banner_alt = $request->email_header_banner_alt;
        $program->short_url_api_id = $request->short_url_api_id;
        $program->short_url_channel_id = $request->short_url_channel_id;
        $program->whatsapp_api_id = $request->whatsapp_api_id;
        $program->whatsapp_sender_id = $request->whatsapp_sender_id;
        $program->whatsapp_notification_on_registration = $request->whatsapp_notification_on_registration;
        $program->sms_notification_on_registration = $request->sms_notification_on_registration;
        $program->email_notification_on_registration = $request->email_notification_on_registration;
        $program->whatsapp_notification_on_payment = $request->whatsapp_notification_on_payment;
        $program->sms_notification_on_payment = $request->sms_notification_on_payment;
        $program->email_notification_on_payment = $request->email_notification_on_payment;
        $program->whatsapp_template_id_on_registration_success = $request->whatsapp_template_id_on_registration_success;
        $program->whatsapp_template_id_on_payment_success = $request->whatsapp_template_id_on_payment_success;
        $program->enable_gate_pass = $request->enable_gate_pass;
        $program->enable_gate_pass_on_selection_status_id = $request->enable_gate_pass_on_selection_status_id;
        $program->enable_address_field = $request->enable_address_field;
        $program->enable_address_field_on_selection_status_id = $request->enable_address_field_on_selection_status_id;
        $program->enable_digital_certificate = $request->enable_digital_certificate;
        $program->enable_digital_certificate_on_selection_status_id = $request->enable_digital_certificate_on_selection_status_id;
        $program->registration_outgoing_webhooks = $request->webhook;    
        $program->addional_email_notification = $request->addional_email_notification;        
        $program->save();

        return redirect()->route('program.list')->with('success', 'Program updated successfully.');

    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    public function destroy(Request $request)
    {
        

        if ($request->program_id) {


            $program = ProgramModel::find($request->program_id);

            if ($program) {
                $program->delete();
                session()->flash('success', 'Program deleted successfully');
                return response()->json(['error' => '0']);
            } else {
                session()->flash('success', 'Program not found');
                return response()->json(['error' => '1']);
            }
        }

        if ($request->program_ids) {
            foreach ($request->program_ids as $program_id) { 
                $program = ProgramModel::find($program_id);
                if ($program) {
                    $program->delete();
                    session()->flash('success', 'All Program deleted successfully');
                }
            }

            return response()->json(['error' => '0']);
        }
    }




}
