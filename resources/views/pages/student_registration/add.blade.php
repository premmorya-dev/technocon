<x-default-layout>

    @section('title')
    Add Setting
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('workshop_setting.add') }}
    @endsection



    <form action="{{ route('workshop_setting.store') }}" class="row g-3" method="post" novalidate enctype="multipart/form-data" autocomplete="on" novalidate name="add-user">
        @csrf
        @method('post')

        <div class="col-md-12">
            <label for="workshop_name" class="form-label">Workshop Name</label>
            <input type="text" name="workshop_name" value="{{ old('workshop_name') }}" class="form-control" id="workshop_name">
            @error('workshop_name')
            <div class="alert alert-danger">{{ $errors->first('workshop_name') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="event_id" class="form-label">Event Name</label>
            <select id="event_id" name="event_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['events'] as $event)
                <option value="{{ $event->event_id }}" {{ old('event_id') ==  $event->event_id ? 'selected' : '' }} >{{ $event->event_name }}</option>
                @endforeach
               

            </select>


            @error('event_id')
            <div class="alert alert-danger">{{ $errors->first('event_id') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="workshop_code" class="form-label">Workshop Code</label>
            <input type="text" name="workshop_code" value="{{ generateUniqueCode(4) }}" class="form-control" id="workshop_code">
            @error('workshop_code')
            <div class="alert alert-danger">{{ $errors->first('workshop_code') }}</div>
            @enderror
        </div>


           

        <div class="col-md-12">
            <label for="workshop_name_for_certificate" class="form-label">Workshop Name For Certificate</label>
            <input type="text" name="workshop_name_for_certificate" value="{{ old('workshop_name_for_certificate') }}" class="form-control" id="workshop_name_for_certificate">
            @error('workshop_name_for_certificate')
            <div class="alert alert-danger">{{ $errors->first('workshop_name_for_certificate') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="workshop_name_sms_dlt" class="form-label">Workshop Name Sms Dlt</label>
            <input type="text" name="workshop_name_sms_dlt" value="{{ old('workshop_name_sms_dlt') }}" class="form-control" id="workshop_name_sms_dlt">
            @error('workshop_name_sms_dlt')
            <div class="alert alert-danger">{{ $errors->first('workshop_name_sms_dlt') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="status" class="form-label">Status</label>
            <select id="status" value="{{ old('status') }}" name="status" class="form-select">
                <option value=""><-- Status --> </option>
                <option value="1" {{ old('status') == '1' ? 'selected' : '' }} >Active</option>
                <option value="0" {{ old('status') == '0' ? 'selected' : '' }} >Deactive</option>

            </select>

            @error('status')
            <div class="alert alert-danger">{{ $errors->first('status') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="rstatus" class="form-label">R Status</label>
            <select id="rstatus" value="{{ old('status') }}" name="rstatus" class="form-select">
                <option value=""><-- Status --> </option>
                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Deactive</option>


            </select>
            @error('rstatus')
            <div class="alert alert-danger">{{ $errors->first('rstatus') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="program_type" class="form-label">Program Type</label>
            <select id="program_type" value="{{ old('program_type') }}" name="program_type" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="workshop"  {{ old('program_type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
            </select>

            @error('program_type')
            <div class="alert alert-danger">{{ $errors->first('program_type') }}</div>
            @enderror
        </div>


        <div class="col-md-6">
            <label for="workshop_duration" class="form-label">Duration</label>
            <input type="number" name="workshop_duration" value="{{ old('workshop_duration') }}" class="form-control" placeholder="Duration" >

            @error('workshop_duration')
            <div class="alert alert-danger">{{ $errors->first('workshop_duration') }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="workshop_duration_time_unit" class="form-label">Workshop Duration Time Unit</label>
            <select id="workshop_duration_time_unit" value="{{ old('workshop_duration_time_unit') }}" name="workshop_duration_time_unit" class="form-select input-group-text">
                                        <option value=""><-- Status --> </option>
                                        <option value="Hour" {{ old('workshop_duration_time_unit') == 'Hour' ? 'selected' : '' }}>Hour</option>
                                        <option value="Day" {{ old('workshop_duration_time_unit') == 'Day' ? 'selected' : '' }}>Day</option>
                                        <option value="Week" {{ old('workshop_duration_time_unit') == 'Week' ? 'selected' : '' }}>Week</option>
                                        <option value="Month" {{ old('workshop_duration_time_unit') == 'Month' ? 'selected' : '' }}>Month</option>
                                        <option value="Year" {{ old('workshop_duration_time_unit') == 'Year' ? 'selected' : '' }}>Year</option>
                                    
                                    </select>
            @error('workshop_duration_time_unit')
            <div class="alert alert-danger">{{ $errors->first('workshop_duration_time_unit') }}</div>
            @enderror
        </div>


 



        <div class="col-md-12">
            <label for="workshop_location" class="form-label">Workshop Location</label>
            <input type="text" name="workshop_location" value="{{ old('workshop_location') }}" class="form-control" id="workshop_location">
            @error('workshop_location')
            <div class="alert alert-danger">{{ $errors->first('workshop_location') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="workshop_venue" class="form-label">Workshop Venue</label>
            <input type="text" name="workshop_venue" value="{{ old('workshop_venue') }}" class="form-control" id="workshop_venue">
            @error('workshop_venue')
            <div class="alert alert-danger">{{ $errors->first('workshop_venue') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="workshop_timing" class="form-label">Workshop Timing</label>
            <input type="text" name="workshop_timing" value="{{ old('workshop_timing') }}" class="form-control" id="workshop_timing">
            @error('workshop_timing')
            <div class="alert alert-danger">{{ $errors->first('workshop_timing') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="workshop_venue_address" class="form-label">Workshop Venue Address</label><br>
            <textarea name="workshop_venue_address" id=""  rows="4" class="form-control">{{ old('workshop_venue_address') }}</textarea>
            @error('workshop_venue_address')
            <div class="alert alert-danger">{{ $errors->first('workshop_venue_address') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="workshop_venue_capacity" class="form-label">workshop Venue Capacity</label>
            <input type="number" name="workshop_venue_capacity" value="{{ old('workshop_venue_capacity') }}" class="form-control" id="workshop_venue_capacity">
            @error('workshop_venue_capacity')
            <div class="alert alert-danger">{{ $errors->first('workshop_venue_capacity') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="time_zone_id" class="form-label">Time Zone</label>
            <select id="time_zone_id" value="{{ old('time_zone_id') }}" name="time_zone_id" class="form-select">
                <option value=""><-- Select --> </option>
                @foreach ($data['time_zones'] as $time_zone)
                <option value="{{ $time_zone->time_zone_id }}" {{ old('time_zone_id') == $time_zone->time_zone_id ? 'selected' : '' }} >{{ $time_zone->timezone_lable }}</option>
                @endforeach
            </select>


            @error('time_zone_id')
            <div class="alert alert-danger">{{ $errors->first('time_zone_id') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="registration_no_prefix" class="form-label">Registration No Prefix</label>
            <input type="text" name="registration_no_prefix" value="{{ old('registration_no_prefix') }}" class="form-control" id="registration_no_prefix">
            @error('registration_no_prefix')
            <div class="alert alert-danger">{{ $errors->first('registration_no_prefix') }}</div>
            @enderror
        </div>

     
        <div class="col-md-12">
            <label for="start_dates" class="form-label">Start Date</label>
            <div class="date-input-wrapper">
                <input type="text" name="start_dates" value="{{ old('start_dates') }}" class="form-control" id="start_dates" placeholder="yyyy-mm-dd">
                <i class="fas fa-calendar-alt"></i>
            </div>
            @error('start_dates')
            <div class="alert alert-danger">{{ $errors->first('start_dates') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="last_date" class="form-label">Last Date</label>
            <div class="date-input-wrapper">
                <input type="text" name="last_date" value="{{ old('last_date') }}" class="form-control" id="last_date" placeholder="yyyy-mm-dd">
                <i class="fas fa-calendar-alt"></i>
            </div>
            @error('last_date')
            <div class="alert alert-danger">{{ $errors->first('last_date') }}</div>
            @enderror
        </div>




      

        <div class="col-md-12">
            <label for="end_dates" class="form-label">End Date</label>
            <div class="date-input-wrapper">
                <input type="text" name="end_dates" value="{{ old('end_dates') }}" class="form-control" id="end_dates" placeholder="yyyy-mm-dd">
                <i class="fas fa-calendar-alt"></i>
            </div>
            @error('end_dates')
            <div class="alert alert-danger">{{ $errors->first('end_dates') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="start_time" class="form-label">Start Time</label>
            <div class="time-input-wrapper">
                <input type="text" name="start_time" value="{{ old('start_time') }}" class="form-control" id="start_time" placeholder="hh:mm">
                <i class="fas fa-clock"></i>
            </div>
            @error('start_time')
            <div class="alert alert-danger">{{ $errors->first('start_time') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="end_time" class="form-label">End Time</label>
            <div class="time-input-wrapper">
                <input type="text" name="end_time" value="{{ old('end_time') }}" class="form-control" id="end_time" placeholder="hh:mm">
                <i class="fas fa-clock"></i>
            </div>
            @error('end_time')
            <div class="alert alert-danger">{{ $errors->first('end_time') }}</div>
            @enderror
        </div>

        



        <div class="col-md-12">
            <label for="level" class="form-label">Level</label>
            <select id="level" value="{{ old('level') }}" name="level" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="college" {{ old('level') == 'college' ? 'selected' : '' }} >College</option>
                <option value="school" {{ old('level') == 'school' ? 'selected' : '' }} >School</option>
            </select>
            @error('level')
            <div class="alert alert-danger">{{ $errors->first('level') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="fees" class="form-label">Fees</label>
                <input type="text" name="fees" value="{{ old('fees') }}" class="form-control" id="fees">
                @error('fees')
                <div class="alert alert-danger">{{ $errors->first('fees') }}</div>
                @enderror
        </div>

        <div class="col-md-12">
            <label for="currency_id" class="form-label">Currency</label>

            <select id="currency_id" value="{{ old('currency_id') }}" name="currency_id" class="form-select">
                <option value=""><-- Select --> </option>
                @foreach ($data['currency_settings'] as $currency_setting)
                <option value="{{ $currency_setting->currency_id }}" {{ old('currency_id') == $currency_setting->currency_id ? 'selected' : '' }} >{{ $currency_setting->code }} | {{ $currency_setting->title }}</option>
                @endforeach
                </select>
               


            @error('currency_id')
            <div class="alert alert-danger">{{ $errors->first('currency_id') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="fees_inclusive_tax" class="form-label">Fees Inclusive Tax</label>
            <select id="fees_inclusive_tax" value="{{ old('fees_inclusive_tax') }}" name="fees_inclusive_tax" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="Y" {{ old('fees_inclusive_tax') == 'Y' ? 'selected' : '' }}>Yes</option>
                <option value="N" {{ old('fees_inclusive_tax') == 'N' ? 'selected' : '' }}>No</option>
            </select>


            @error('fees_inclusive_tax')
            <div class="alert alert-danger">{{ $errors->first('fees_inclusive_tax') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="tax_rate" class="form-label">Tax Rate</label>
            <input type="number" name="tax_rate" value="{{ old('tax_rate') }}" class="form-control" id="tax_rate">
            @error('tax_rate')
            <div class="alert alert-danger">{{ $errors->first('tax_rate') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="payment_gateway_fee_rate" class="form-label">Payment Gateway Fee Rate</label>
            <input type="number" name="payment_gateway_fee_rate" value="{{ old('payment_gateway_fee_rate') }}" class="form-control" id="payment_gateway_fee_rate">
            @error('payment_gateway_fee_rate')
            <div class="alert alert-danger">{{ $errors->first('payment_gateway_fee_rate') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="max_member" class="form-label">Max Member</label>
            <input type="number" name="max_member" value="{{ old('max_member') }}" class="form-control" id="max_member">
            @error('max_member')
            <div class="alert alert-danger">{{ $errors->first('max_member') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="any_special_terms" class="form-label">Any Special Terms</label>
            <input type="text" name="any_special_terms" value="{{ old('any_special_terms') }}" class="form-control" id="any_special_terms">
            @error('any_special_terms')
            <div class="alert alert-danger">{{ $errors->first('any_special_terms') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="certificate_title" class="form-label">Certificate Title</label>
            <input type="text" name="certificate_title" value="{{ old('certificate_title') }}" class="form-control" id="certificate_title">
            @error('certificate_title')
            <div class="alert alert-danger">{{ $errors->first('certificate_title') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="online_content" class="form-label">Online Content</label>
            <input type="text" name="online_content" value="{{ old('online_content') }}" class="form-control" id="online_content">
            @error('online_content')
            <div class="alert alert-danger">{{ $errors->first('online_content') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="content_links" class="form-label">Content Links</label>
            <input type="text" name="content_links" value="{{ old('content_links') }}" class="form-control" id="content_links">
            @error('content_links')
            <div class="alert alert-danger">{{ $errors->first('content_links') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="registration_page_url" class="form-label">Registration Page Url</label>
            <input type="text" name="registration_page_url" value="{{ old('registration_page_url') }}" class="form-control" id="registration_page_url">
            @error('registration_page_url')
            <div class="alert alert-danger">{{ $errors->first('registration_page_url') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="registration_page_short_url" class="form-label">Registration Page Short Url</label>
            <input type="text" name="registration_page_short_url" value="{{ old('registration_page_short_url') }}" class="form-control" id="registration_page_short_url">
            @error('registration_page_short_url')
            <div class="alert alert-danger">{{ $errors->first('registration_page_short_url') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="registration_page_short_url" class="form-label">Registration Page Short Url</label>
            <input type="text" name="registration_page_short_url" value="{{ old('registration_page_short_url') }}" class="form-control" id="registration_page_short_url">
            @error('registration_page_short_url')
            <div class="alert alert-danger">{{ $errors->first('registration_page_short_url') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="registration_page_root_domain" class="form-label">Registration Page Root Domain</label>
            <input type="text" name="registration_page_root_domain" value="{{ old('registration_page_root_domain') }}" class="form-control" id="registration_page_root_domain">
            @error('registration_page_root_domain')
            <div class="alert alert-danger">{{ $errors->first('registration_page_root_domain') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="event_website" class="form-label">Event Website</label>
            <input type="text" name="event_website" value="{{ old('event_website') }}" class="form-control" id="event_website">
            @error('event_website')
            <div class="alert alert-danger">{{ $errors->first('event_website') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="workshop_details_page_url" class="form-label">Workshop Details Page Url</label>
            <input type="text" name="workshop_details_page_url" value="{{ old('workshop_details_page_url') }}" class="form-control" id="workshop_details_page_url">
            @error('workshop_details_page_url')
            <div class="alert alert-danger">{{ $errors->first('workshop_details_page_url') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="workshop_details_page_short_url" class="form-label">Workshop Details Page Short Url</label>
            <input type="text" name="workshop_details_page_short_url" value="{{ old('workshop_details_page_short_url') }}" class="form-control" id="workshop_details_page_short_url">
            @error('workshop_details_page_short_url')
            <div class="alert alert-danger">{{ $errors->first('workshop_details_page_short_url') }}</div>
            @enderror
        </div>





        <div class="col-md-12">
            <label for="enable_payment_link" class="form-label">Enable Payment Link</label>
            <select id="enable_payment_link" value="{{ old('enable_payment_link') }}" name="enable_payment_link" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="Y" {{ old('enable_payment_link') == 'Y' ? 'selected' : '' }}>Yes</option>
                <option value="N" {{ old('enable_payment_link') == 'N' ? 'selected' : '' }}>No</option>
            </select>
            @error('enable_payment_link')
            <div class="alert alert-danger">{{ $errors->first('enable_payment_link') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="payment_gateway_id" class="form-label">Payment Gateway</label>
            
             
            <select id="payment_gateway_id" value="{{ old('payment_gateway_id') }}" name="payment_gateway_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['payment_gateway_configs'] as $payment_gateway_config)
                <option value="{{ $payment_gateway_config->payment_gateway_id }}" {{ old('payment_gateway_id') == $payment_gateway_config->payment_gateway_id ? 'selected' : '' }} >{{ $payment_gateway_config->payment_gateway_name }}</option>
                @endforeach
            </select>
            
            @error('payment_gateway_id')
            <div class="alert alert-danger">{{ $errors->first('payment_gateway_id') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="sms_gateway_id" class="form-label">Sms Gateway</label>
            <input type="number" name="sms_gateway_id" value="{{ old('sms_gateway_id') }}" class="form-control" id="sms_gateway_id">
            @error('sms_gateway_id')
            <div class="alert alert-danger">{{ $errors->first('sms_gateway_id') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="smtp_id" class="form-label">Smtp</label>
                  
            <select id="smtp_id" value="{{ old('smtp_id') }}" name="smtp_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['smtp_settings'] as $smtp_setting)
                <option value="{{ $smtp_setting->smtp_id }}" {{ old('smtp_id') ==  $smtp_setting->smtp_id ? 'selected' : '' }} >{{ $smtp_setting->smtp_name }}</option>
                @endforeach
                </select>

            @error('smtp_id')
            <div class="alert alert-danger">{{ $errors->first('smtp_id') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="registration_email_template_id" class="form-label">Registration Email Template</label>            
            <select id="registration_email_template_id" value="{{ old('registration_email_template_id') }}" name="registration_email_template_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['email_templates'] as $email_template)
                <option value="{{ $email_template->email_template_id }}" {{ old('registration_email_template_id') == $email_template->email_template_id ? 'selected' : '' }}>{{ $email_template->email_template_name }}</option>
                @endforeach
                </select>

            @error('registration_email_template_id')
            <div class="alert alert-danger">{{ $errors->first('registration_email_template_id') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="registration_sms_template_id" class="form-label">Registration Sms Template</label>
            <input type="number" name="registration_sms_template_id" value="{{ old('registration_sms_template_id') }}" class="form-control" id="registration_sms_template_id">
            @error('registration_sms_template_id')
            <div class="alert alert-danger">{{ $errors->first('registration_sms_template_id') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="payment_email_template_id" class="form-label">Payment Email Template</label>          
                           
            <select id="payment_email_template_id" value="{{ old('payment_email_template_id') }}" name="payment_email_template_id" class="form-select">
            <option value=""><-- Status --> </option>
            @foreach ($data['email_templates'] as $email_template)
            <option value="{{ $email_template->email_template_id }}" {{ old('payment_email_template_id') ==  $email_template->email_template_id ? 'selected' : '' }}>{{ $email_template->email_template_name }}</option>
            @endforeach
            </select>

            @error('payment_email_template_id')
            <div class="alert alert-danger">{{ $errors->first('payment_email_template_id') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="payment_sms_template_id" class="form-label">Payment Sms Template</label>
            <input type="number" name="payment_sms_template_id" value="{{ old('payment_sms_template_id') }}" class="form-control" id="payment_sms_template_id">
            @error('payment_sms_template_id')
            <div class="alert alert-danger">{{ $errors->first('payment_sms_template_id') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="currency_exchange_gateway_id" class="form-label">Currency Exchange Gateway</label>
            <input type="number" name="currency_exchange_gateway_id" value="{{ old('currency_exchange_gateway_id') }}" class="form-control" id="currency_exchange_gateway_id">
            @error('currency_exchange_gateway_id')
            <div class="alert alert-danger">{{ $errors->first('currency_exchange_gateway_id') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="short_url_domain" class="form-label">Short Url_Domain</label>
            <input type="text" name="short_url_domain" value="{{ old('short_url_domain') }}" class="form-control" id="short_url_domain">
            @error('short_url_domain')
            <div class="alert alert-danger">{{ $errors->first('short_url_domain') }}</div>
            @enderror
        </div>



     

        <div class="col-md-12">
            <label for="contact_us_email" class="form-label">Contact Us Email</label>
            <input type="text" name="contact_us_email" value="{{ old('contact_us_email') }}" class="form-control" id="contact_us_email">
            @error('contact_us_email')
            <div class="alert alert-danger">{{ $errors->first('contact_us_email') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="contact_us_mobile" class="form-label">Contact Us Mobile</label>
            <input type="number" name="contact_us_mobile" value="{{ old('contact_us_mobile') }}" class="form-control" id="contact_us_mobile">
            @error('contact_us_mobile')
            <div class="alert alert-danger">{{ $errors->first('contact_us_mobile') }}</div>
            @enderror
        </div>







        <div class="col-md-12">
            <label for="zoik_app_workshop_list_uid" class="form-label">Zoik App Workshop List Uid</label>
            <input type="text" name="zoik_app_workshop_list_uid" value="{{ old('zoik_app_workshop_list_uid') }}" class="form-control" id="zoik_app_workshop_list_uid">
            @error('zoik_app_workshop_list_uid')
            <div class="alert alert-danger">{{ $errors->first('zoik_app_workshop_list_uid') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="zoik_app_common_list_uid" class="form-label">Zoik App Common List Uid</label>
            <input type="text" name="zoik_app_common_list_uid" value="{{ old('zoik_app_common_list_uid') }}" class="form-control" id="zoik_app_common_list_uid">
            @error('zoik_app_common_list_uid')
            <div class="alert alert-danger">{{ $errors->first('zoik_app_common_list_uid') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="zoik_app_id" class="form-label">Zoik App Id</label>
            
            <select id="zoik_app_id" value="{{ old('zoik_app_id') }}" name="zoik_app_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['zoik_app_settings'] as $zoik_app_setting)
                <option value="{{ $zoik_app_setting->zoik_app_id }}" {{ old('zoik_app_id') == $zoik_app_setting->zoik_app_id ? 'selected' : '' }}>{{ $zoik_app_setting->zoik_app_name }}</option>
                @endforeach
                </select>
            
            @error('zoik_app_id')
            <div class="alert alert-danger">{{ $errors->first('zoik_app_id') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="zoik_app_workshop_list_email_field_mapping" class="form-label">Zoik App Workshop List Email Field Mapping</label><br>

            <textarea name="zoik_app_workshop_list_email_field_mapping" id="zoik_app_workshop_list_email_field_mapping" class="form-control" rows="4">{{ old('zoik_app_workshop_list_email_field_mapping') }}</textarea>
            <br>
            @error('zoik_app_workshop_list_email_field_mapping')
            <div class="alert alert-danger">{{ $errors->first('zoik_app_workshop_list_email_field_mapping') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="zoik_app_common_list_email_field_mapping" class="form-label">Zoik App Common List Email Field Mapping</label> <br>
            <textarea name="zoik_app_common_list_email_field_mapping" id="zoik_app_common_list_email_field_mapping" class="form-control" rows="4">{{ old('zoik_app_common_list_email_field_mapping') }}</textarea>
            @error('zoik_app_common_list_email_field_mapping')
            <div class="alert alert-danger">{{ $errors->first('zoik_app_common_list_email_field_mapping') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="zoik_app_list_fields" class="form-label">Zoik App List Fields</label><br>
            <textarea name="zoik_app_list_fields" id="zoik_app_list_fields" class="form-control" rows="4">{{ old('zoik_app_list_fields') }}</textarea>


            @error('zoik_app_list_fields')
            <div class="alert alert-danger">{{ $errors->first('zoik_app_list_fields') }}</div>
            @enderror
        </div>






        <div class="col-md-12">
            <label for="selection_status_after_registartion" class="form-label">Selection Status After Registration</label>            
            <select id="selection_status_after_registartion" value="{{ old('selection_status_after_registartion') }}" name="selection_status_after_registartion" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['students_selection_statuses'] as $students_selection_status)
                <option value="{{ $students_selection_status->student_selection_status_id }}" {{ old('selection_status_after_registartion') == $students_selection_status->student_selection_status_id ? 'selected' : '' }} >{{ $students_selection_status->selection_status }}</option>
                @endforeach
                </select>

            @error('selection_status_after_registartion')
            <div class="alert alert-danger">{{ $errors->first('selection_status_after_registartion') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="selection_status_after_payment" class="form-label">Selection Status After Payment</label>
           
            <select id="selection_status_after_payment" value="{{ old('selection_status_after_payment') }}" name="selection_status_after_payment" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['students_selection_statuses'] as $students_selection_status)
                <option value="{{ $students_selection_status->student_selection_status_id }}" {{ old('selection_status_after_payment') == $students_selection_status->student_selection_status_id ? 'selected' : '' }} >{{ $students_selection_status->selection_status }}</option>
                @endforeach
                </select>

            @error('selection_status_after_payment')
            <div class="alert alert-danger">{{ $errors->first('selection_status_after_payment') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="invitation_status_after_registartion" class="form-label">Invitation Status After Registartion</label>            
            <select id="invitation_status_after_registartion" value="{{ old('invitation_status_after_registartion') }}" name="invitation_status_after_registartion" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['students_invitation_statuses'] as $students_invitation_status)
                <option value="{{ $students_invitation_status->student_invitation_status_id }}" {{ old('invitation_status_after_registartion') == $students_invitation_status->student_invitation_status_id ? 'selected' : '' }} >{{ $students_invitation_status->invitation_status }}</option>
                @endforeach
                </select>

            @error('invitation_status_after_registartion')
            <div class="alert alert-danger">{{ $errors->first('invitation_status_after_registartion') }}</div>
            @enderror
        </div>
        <div class="col-md-12">
            <label for="invitation_status_after_payment" class="form-label">Invitation Status After Payment</label>
            <select id="invitation_status_after_payment" value="{{ old('invitation_status_after_payment') }}" name="invitation_status_after_payment" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['students_invitation_statuses'] as $students_invitation_status)
                <option value="{{ $students_invitation_status->student_invitation_status_id }}" {{ old('invitation_status_after_payment') == $students_invitation_status->student_invitation_status_id ? 'selected' : '' }} >{{ $students_invitation_status->invitation_status }}</option>
                @endforeach
                </select>

            @error('invitation_status_after_payment')
            <div class="alert alert-danger">{{ $errors->first('invitation_status_after_payment') }}</div>
            @enderror
        </div>
        <div class="col-md-12">
            <label for="payment_status_after_registration" class="form-label">Payment Status After Registration</label>            
            <select id="payment_status_after_registration" value="{{ old('payment_status_after_registration') }}" name="payment_status_after_registration" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['payment_statuses'] as $payment_status)
                <option value="{{ $payment_status->payment_status_id }}" {{ old('payment_status_after_registration') == $payment_status->payment_status_id ? 'selected' : '' }}>{{ $payment_status->payment_status }}</option>
                @endforeach
                </select>

            @error('payment_status_after_registration')
            <div class="alert alert-danger">{{ $errors->first('payment_status_after_registration') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="payment_status_after_payment" class="form-label">Payment Status After Payment</label>            
            <select id="payment_status_after_payment" value="{{ old('payment_status_after_payment') }}" name="payment_status_after_payment" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['payment_statuses'] as $payment_status)
                <option value="{{ $payment_status->payment_status_id }}" {{ old('payment_status_after_payment') == $payment_status->payment_status_id ? 'selected' : '' }} >{{ $payment_status->payment_status }}</option>
                @endforeach
                </select>
            @error('payment_status_after_payment')
            <div class="alert alert-danger">{{ $errors->first('payment_status_after_payment') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="email_header_banner_url" class="form-label">Email Header Banner Url</label>
            <input type="text" name="email_header_banner_url" value="{{ old('email_header_banner_url') }}" class="form-control" id="email_header_banner_url">
            @error('email_header_banner_url')
            <div class="alert alert-danger">{{ $errors->first('email_header_banner_url') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="email_header_banner_alt" class="form-label">Email Header Banner Alt</label>
            <input type="text" name="email_header_banner_alt" value="{{ old('email_header_banner_alt') }}" class="form-control" id="email_header_banner_alt">
            @error('email_header_banner_alt')
            <div class="alert alert-danger">{{ $errors->first('email_header_banner_alt') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="short_url_api_id" class="form-label">Short Url Api Id</label>
            <input type="number" name="short_url_api_id" value="{{ old('short_url_api_id') }}" class="form-control" id="short_url_api_id">
            @error('short_url_api_id')
            <div class="alert alert-danger">{{ $errors->first('short_url_api_id') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="short_url_channel_id" class="form-label">Short Url Channel Id</label>
            <input type="number" name="short_url_channel_id" value="{{ old('short_url_channel_id') }}" class="form-control" id="short_url_channel_id">
            @error('short_url_channel_id')
            <div class="alert alert-danger">{{ $errors->first('short_url_channel_id') }}</div>
            @enderror
        </div>






        <div class="col-md-12">
            <label for="whatsapp_api_id" class="form-label">Whatsapp Api Id</label>           
            <select id="whatsapp_api_id" value="{{ old('whatsapp_api_id') }}" name="whatsapp_api_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['whatsapp_apis'] as $whatsapp_api)
                <option value="{{ $whatsapp_api->whatsapp_api_id }}" {{ old('whatsapp_api_id') == $whatsapp_api->whatsapp_api_id ? 'selected' : '' }} >{{ $whatsapp_api->whatsapp_api_provider_description }}</option>
                @endforeach
                </select>

            @error('whatsapp_api_id')
            <div class="alert alert-danger">{{ $errors->first('whatsapp_api_id') }}</div>
            @enderror
        </div>
        <div class="col-md-12">
            <label for="whatsapp_sender_id" class="form-label">Whatsapp Sender Id</label>           
            <select id="whatsapp_sender_id" value="{{ old('whatsapp_sender_id') }}" name="whatsapp_sender_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['whatsapp_senders'] as $whatsapp_sender)
                <option value="{{ $whatsapp_sender->whatsapp_sender_id }}" {{ old('whatsapp_sender_id') == $whatsapp_sender->whatsapp_sender_id  ? 'selected' : '' }}>+{{ $whatsapp_sender->sender_number }}</option>
                @endforeach
            </select>

            @error('whatsapp_sender_id')
            <div class="alert alert-danger">{{ $errors->first('whatsapp_sender_id') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="whatsapp_notification_on_registration" class="form-label">Whatsapp Notification On Registration</label>


            <select id="whatsapp_notification_on_registration" value="{{ old('whatsapp_notification_on_registration') }}" name="whatsapp_notification_on_registration" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="Y"  {{ old('whatsapp_notification_on_registration') == 'Y' ? 'selected' : '' }}>Yes</option>
                <option value="N" {{ old('whatsapp_notification_on_registration') == 'N' ? 'selected' : '' }}>No</option>
            </select>


            @error('whatsapp_notification_on_registration')
            <div class="alert alert-danger">{{ $errors->first('whatsapp_notification_on_registration') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="sms_notification_on_registration" class="form-label">Sms Notification On Registration</label>

            <select id="sms_notification_on_registration" value="{{ old('sms_notification_on_registration') }}" name="sms_notification_on_registration" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="Y" {{ old('sms_notification_on_registration') == 'Y' ? 'selected' : '' }} >Yes</option>
                <option value="N" {{ old('sms_notification_on_registration') == 'N' ? 'selected' : '' }} >No</option>

            </select>
            @error('sms_notification_on_registration')
            <div class="alert alert-danger">{{ $errors->first('sms_notification_on_registration') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="email_notification_on_registration" class="form-label">Email Notification On Registration</label>

            <select id="email_notification_on_registration" value="{{ old('email_notification_on_registration') }}" name="email_notification_on_registration" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="Y" {{ old('email_notification_on_registration') == 'Y' ? 'selected' : '' }} >Yes</option>
                <option value="N" {{ old('email_notification_on_registration') == 'N' ? 'selected' : '' }} >No</option>

            </select>

            @error('email_notification_on_registration')
            <div class="alert alert-danger">{{ $errors->first('email_notification_on_registration') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="whatsapp_notification_on_payment" class="form-label">Whatsapp Notification On Payment</label>

            <select id="whatsapp_notification_on_payment" value="{{ old('whatsapp_notification_on_payment') }}" name="whatsapp_notification_on_payment" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="Y" {{ old('whatsapp_notification_on_payment') == 'Y' ? 'selected' : '' }} >Yes</option>
                <option value="N" {{ old('whatsapp_notification_on_payment') == 'N' ? 'selected' : '' }} >No</option>

            </select>
            @error('whatsapp_notification_on_payment')
            <div class="alert alert-danger">{{ $errors->first('whatsapp_notification_on_payment') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="sms_notification_on_payment" class="form-label">Sms Notification On Payment</label>
            <select id="sms_notification_on_payment" value="{{ old('sms_notification_on_payment') }}" name="sms_notification_on_payment" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="Y" {{ old('sms_notification_on_payment') == 'Y' ? 'selected' : '' }} >Yes</option>
                <option value="N" {{ old('sms_notification_on_payment') == 'N' ? 'selected' : '' }} >No</option>

            </select>
            @error('sms_notification_on_payment')
            <div class="alert alert-danger">{{ $errors->first('sms_notification_on_payment') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="email_notification_on_payment" class="form-label">Email Notification On Payment</label>

            <select id="email_notification_on_payment" value="{{ old('email_notification_on_payment') }}" name="email_notification_on_payment" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="Y" {{ old('email_notification_on_payment') == 'Y' ? 'selected' : '' }}>Yes</option>
                <option value="N" {{ old('email_notification_on_payment') == 'N' ? 'selected' : '' }} >No</option>
            </select>

            @error('email_notification_on_payment')
            <div class="alert alert-danger">{{ $errors->first('email_notification_on_payment') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="whatsapp_template_id_on_registration_success" class="form-label">Whatsapp Template Id On Registration Success</label>         
            <select id="whatsapp_template_id_on_registration_success" value="{{ old('whatsapp_template_id_on_registration_success') }}" name="whatsapp_template_id_on_registration_success" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['whatsapp_templates'] as $whatsapp_template)
                <option value="{{ $whatsapp_template->whatsapp_template_id }}" {{ old('whatsapp_template_id_on_registration_success') == $whatsapp_template->whatsapp_template_id  ? 'selected' : '' }}>{{ $whatsapp_template->template_name }}</option>
                @endforeach
            </select>

            @error('whatsapp_template_id_on_registration_success')
            <div class="alert alert-danger">{{ $errors->first('whatsapp_template_id_on_registration_success') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="whatsapp_template_id_on_payment_success" class="form-label">Whatsapp Template Id On Payment Success</label>
            <select id="whatsapp_template_id_on_payment_success" value="{{ old('whatsapp_template_id_on_payment_success') }}" name="whatsapp_template_id_on_payment_success" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['whatsapp_templates'] as $whatsapp_template)
                <option value="{{ $whatsapp_template->whatsapp_template_id }}" {{ old('whatsapp_template_id_on_payment_success') == $whatsapp_template->whatsapp_template_id ? 'selected' : '' }} >{{ $whatsapp_template->template_name }}</option>
                @endforeach
            </select>
            @error('whatsapp_template_id_on_payment_success')
            <div class="alert alert-danger">{{ $errors->first('whatsapp_template_id_on_payment_success') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="enable_gate_pass" class="form-label">Enable Gate Pass</label>

            <select id="enable_gate_pass" value="{{ old('enable_gate_pass') }}" name="enable_gate_pass" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="Y" {{ old('enable_gate_pass') == 'Y' ? 'selected' : '' }}>Yes</option>
                <option value="N" {{ old('enable_gate_pass') == 'N' ? 'selected' : '' }}>No</option>

            </select>
            @error('enable_gate_pass')
            <div class="alert alert-danger">{{ $errors->first('enable_gate_pass') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="enable_gate_pass_on_selection_status_id" class="form-label">Enable Gate Pass On Selection Status</label>
           
            <select id="enable_gate_pass_on_selection_status_id" value="{{ old('enable_gate_pass_on_selection_status_id') }}" name="enable_gate_pass_on_selection_status_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['students_selection_statuses'] as $students_selection_status)
                <option value="{{ $students_selection_status->student_selection_status_id }}" {{ old('enable_gate_pass_on_selection_status_id') == $students_selection_status->student_selection_status_id ? 'selected' : '' }} >{{ $students_selection_status->selection_status }}</option>
                @endforeach
                </select>


            @error('enable_gate_pass_on_selection_status_id')
            <div class="alert alert-danger">{{ $errors->first('enable_gate_pass_on_selection_status_id') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="enable_address_field" class="form-label">Enable Address Field</label>

            <select id="enable_address_field" value="{{ old('enable_address_field') }}" name="enable_address_field" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="Y"  {{ old('enable_address_field') == 'Y' ? 'selected' : '' }} >Yes</option>
                <option value="N"  {{ old('enable_address_field') == 'N' ? 'selected' : '' }} >No</option>

            </select>
            @error('enable_address_field')
            <div class="alert alert-danger">{{ $errors->first('enable_address_field') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="enable_address_field_on_selection_status_id" class="form-label">Enable Address Field On Selection Status</label>
            
            <select id="enable_address_field_on_selection_status_id" value="{{ old('enable_address_field_on_selection_status_id') }}" name="enable_address_field_on_selection_status_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['students_selection_statuses'] as $students_selection_status)
                <option value="{{ $students_selection_status->student_selection_status_id }}" {{ old('enable_address_field_on_selection_status_id') == $students_selection_status->student_selection_status_id ? 'selected' : '' }} >{{ $students_selection_status->selection_status }}</option>
                @endforeach
                </select>

            @error('enable_address_field_on_selection_status_id')
            <div class="alert alert-danger">{{ $errors->first('enable_address_field_on_selection_status_id') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="enable_digital_certificate" class="form-label">Enable Digital Certificate</label>

            <select id="enable_digital_certificate" value="{{ old('enable_digital_certificate') }}" name="enable_digital_certificate" class="form-select">
                <option value=""><-- Select --> </option>
                <option value="Y" {{ old('enable_digital_certificate') == 'Y' ? 'selected' : '' }}>Yes</option>
                <option value="N" {{ old('enable_digital_certificate') == 'N' ? 'selected' : '' }}>No</option>
            </select>
            @error('enable_address_field')
            <div class="alert alert-danger">{{ $errors->first('enable_address_field') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="enable_digital_certificate_on_selection_status_id" class="form-label">Enable Digital Certificate On Selection Status</label>
            <select id="enable_digital_certificate_on_selection_status_id" value="{{ old('enable_digital_certificate_on_selection_status_id') }}" name="enable_digital_certificate_on_selection_status_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['students_selection_statuses'] as $students_selection_status)
                <option value="{{ $students_selection_status->student_selection_status_id }}" {{ old('enable_digital_certificate_on_selection_status_id') == $students_selection_status->student_selection_status_id ? 'selected' : '' }}>{{ $students_selection_status->selection_status }}</option>
                @endforeach
                </select>

            @error('enable_digital_certificate_on_selection_status_id')
            <div class="alert alert-danger">{{ $errors->first('enable_digital_certificate_on_selection_status_id') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="digital_certificate_config_json" class="form-label">Digital Certificate Config Json</label><br>
            <textarea name="digital_certificate_config_json" id="digital_certificate_config_json" class="form-control" rows="4"> {{ old('digital_certificate_config_json') }}</textarea>


            @error('digital_certificate_config_json')
            <div class="alert alert-danger">{{ $errors->first('digital_certificate_config_json') }}</div>
            @enderror
        </div>
        <div class="col-md-12">
            <label for="certificate_record_config_json" class="form-label">Certificate Record Config Json</label><br>

            <textarea name="certificate_record_config_json" id="certificate_record_config_json" class="form-control" rows="4">{{ old('certificate_record_config_json') }}</textarea>

            @error('certificate_record_config_json')
            <div class="alert alert-danger">{{ $errors->first('certificate_record_config_json') }}</div>
            @enderror
        </div>

        <div class="container mt-5">
        <div class="col-md-12">
        <button type="button" class="btn btn-primary btn-sm add-row float-end mt-3"><i class=" fa-solid fa-plus "></i></button>
            <table id="dynamicTable" class="table">
                <tbody>
               
                </tbody>
            </table>
        </div>
    </div>
      




        <div class="col-md-12 mt-5">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa-regular fa-floppy-disk"></i>Add</button>
        </div>


    </form>
    <script>

      

        $(document).ready(function() {
            var row = 0;
            $(document).on("click", ".add-row", function() {  
                row++;         
                var newRow = ` 
                <tr class="webhook_row${row}">
                    <td><input type="text" name="webhook[${row}][url]" class="form-control" placeholder="Webhook Url ${row}"></td>
                    <td>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="webhook[${row}][status]" id="webhook_status_${row}">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Status</label>
                        </div>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm add-row"><i class=" fa-solid fa-plus "></i></button>
                        <button type="button" class="btn btn-danger btn-sm delete-row"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
           `;
                $("#dynamicTable tbody").append(newRow);
            });

            $(document).on("click", ".delete-row", function() {
                $(this).closest("tr").remove();
            });
        });
    </script>

    <script>

        // for date input fields
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#dates", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "Y-m-d",
                allowInput: true, // Allow typing in the input
                onReady: function(selectedDates, dateStr, instance) {
                    instance.altInput.placeholder = "yyyy-mm-dd"; // Set placeholder for the altInput
                }
            });


            flatpickr("#last_date", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "Y-m-d",
                allowInput: true, // Allow typing in the input
                onReady: function(selectedDates, dateStr, instance) {
                    // instance.altInput.placeholder = "yyyy-mm-dd"; // Set placeholder for the altInput
                }
            });

            flatpickr("#start_dates", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "Y-m-d",
                allowInput: true, // Allow typing in the input
                onReady: function(selectedDates, dateStr, instance) {
                    // instance.altInput.placeholder = "yyyy-mm-dd"; // Set placeholder for the altInput
                }
            });

            flatpickr("#end_dates", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "Y-m-d",
                allowInput: true, // Allow typing in the input
                onReady: function(selectedDates, dateStr, instance) {
                    //instance.altInput.placeholder = "yyyy-mm-dd"; // Set placeholder for the altInput
                }
            });



            const startTimePicker = flatpickr("#start_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                allowInput: true, // Allow typing in the input
                onReady: function(selectedDates, dateStr, instance) {
                  //  instance.altInput.placeholder = "hh:mm"; // Set placeholder for the altInput
                }
            });

            document.querySelector('.fa-clock').addEventListener('click', function() {
                startTimePicker.open();
            });


            const endTimePicker = flatpickr("#end_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                allowInput: true, // Allow typing in the input
                onReady: function(selectedDates, dateStr, instance) {
                //    instance.altInput.placeholder = "hh:mm"; // Set placeholder for the altInput
                }
            });

            document.querySelector('.fa-clock').addEventListener('click', function() {
                endTimePicker.open();
            });





        });
    </script>

</x-default-layout>