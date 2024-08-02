

    <form class="row g-3" method="post" novalidate enctype="multipart/form-data" autocomplete="on" novalidate name="add-user">
       
        <div class="col-md-12">
            <label for="program_id" class="form-label">Workshop</label>
            <select disabled id="program_id" value="{{ old('program_id') }}" name="program_id" class="form-select">
                <option value=""><-- Select --> </option>
                @foreach ($data['programs'] as $program)
                <option value="{{ $program->program_id }}" {{ old('program_id',$data['student_registration']['program_id']) == $program->program_id ? 'selected' : '' }} >{{ $program->program_name }}</option>
                @endforeach
            </select>

            @error('time_zone_id')
            <div class="alert alert-danger">{{ $errors->first('time_zone_id') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="time_zone_id" class="form-label">Time Zone</label>
            <select disabled id="time_zone_id" value="{{ old('time_zone_id') }}" name="time_zone_id" class="form-select">
                <option value=""><-- Select --> </option>
                @foreach ($data['time_zones'] as $time_zone)
                <option value="{{ $time_zone->time_zone_id }}" {{ old('time_zone_id',$data['student_registration']['time_zone_id']) == $time_zone->time_zone_id ? 'selected' : '' }} >{{ $time_zone->timezone_lable }}</option>
                @endforeach
            </select>

            @error('time_zone_id')
            <div class="alert alert-danger">{{ $errors->first('time_zone_id') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="first_name" class="form-label">First Name</label>
            <input disabled  type="text" name="first_name" value="{{ old('first_name',$data['student_registration']['first_name']) }}" class="form-control" id="first_name">
            @error('first_name')
            <div class="alert alert-danger">{{ $errors->first('first_name') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="last_name" class="form-label">Last Name</label>
            <input disabled  type="text" name="last_name" value="{{ old('last_name',$data['student_registration']['last_name']) }}" class="form-control" id="last_name">
            @error('last_name')
            <div class="alert alert-danger">{{ $errors->first('last_name') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="registered_email" class="form-label">Registered Email</label>
            <input disabled  type="text" name="registered_email" value="{{ old('registered_email',$data['student_registration']['registered_email']) }}" class="form-control" id="registered_email">
            @error('registered_email')
            <div class="alert alert-danger">{{ $errors->first('registered_email') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="registered_email_alias" class="form-label">Registered Email Alias</label>
            <input disabled  type="text" name="registered_email_alias" value="{{ old('registered_email_alias',$data['student_registration']['registered_email_alias']) }}" class="form-control" id="registered_email_alias">
            @error('registered_email_alias')
            <div class="alert alert-danger">{{ $errors->first('registered_email_alias') }}</div>
            @enderror
        </div>


        <div class="col-md-3">
            <label for="country_code" class="form-label">Country Code</label>
            <select disabled id="country_code" value="{{ old('country_code',$data['student_registration']['country_code']) }}" name="country_code" class="form-select">
                     <option value=""><-- Select Country Code --> </option>
                     @foreach ($data['countries'] as $country)
                <option value="{{ $country->country_code }}" {{ old('country_code',$data['student_registration']['country_code']) == $country->country_code ? 'selected' : '' }}>+{{ $country->country_code }} ( {{ $country->country_name }} )</option>
                @endforeach
                   
                  
            </select>
            @error('country_code')
                        <div class="alert alert-danger">{{ $errors->first('country_code') }}</div>
            @enderror
        </div>

        <div class="col-md-9">
            <label for="contact" class="form-label">Mobile Number</label>
            <input disabled  type="text"   name="contact" value="{{ old('contact',$data['student_registration']['contact']) }}" class="form-control" id="contact">
            @error('contact')
                        <div class="alert alert-danger">{{ $errors->first('contact') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="college" class="form-label">College</label>
            <input disabled  type="text"   name="college" value="{{ old('college',$data['student_registration']['college']) }}" class="form-control" id="college">
            @error('college')
                        <div class="alert alert-danger">{{ $errors->first('college') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="city" class="form-label">City</label>
            <input disabled  type="text"   name="city" value="{{ old('city',$data['student_registration']['city']) }}" class="form-control" id="city">
            @error('city')
                        <div class="alert alert-danger">{{ $errors->first('city') }}</div>
            @enderror
        </div>
      


        <div class="col-md-12">
            <label for="country_id" class="form-label">Country</label>
            <select disabled id="country_id" value="{{ old('country_id',$data['student_registration']['country_id']) }}" name="country_id" class="form-select">
                     <option value=""><-- Select Country --> </option>
                     @foreach ($data['countries'] as $country)
                <option value="{{ $country->country_id }}" {{ old('country_id',$data['student_registration']['country_id']) == $country->country_id ? 'selected' : '' }}>{{ $country->country_name }} </option>
                @endforeach
                   
                  
            </select>
            @error('country_id')
                        <div class="alert alert-danger">{{ $errors->first('country_id') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="country_state_id" class="form-label">State</label>
            <select disabled id="country_state_id" value="{{ old('country_state_id',$data['student_registration']['country_state_id']) }}" name="country_state_id" class="form-select">
                     <option value=""><-- Select Country --> </option>
                     @foreach ($data['technocon_country_to_state_code'] as $state)
                <option value="{{ $state->country_state_id }}" {{ old('country_state_id',$data['student_registration']['country_state_id']) == $state->country_state_id ? 'selected' : '' }}>{{ $state->state_name }} </option>
                @endforeach
                   
                  
            </select>
            @error('country_state_id')
                        <div class="alert alert-danger">{{ $errors->first('country_state_id') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="registration_number" class="form-label">Registration Number</label>
            <input disabled  type="text"   name="registration_number" value="{{ old('registration_number',$data['student_registration']['registration_number']) }}" class="form-control" id="registration_number">
            @error('registration_number')
                        <div class="alert alert-danger">{{ $errors->first('registration_number') }}</div>
            @enderror
        </div>
      

        <div class="col-md-12">
            <label for="auto_login_string" class="form-label">Auto Login String</label>
            <input disabled  type="text"   name="auto_login_string" value="{{ old('auto_login_string',$data['student_registration']['auto_login_string']) }}" class="form-control" id="auto_login_string">
            @error('auto_login_string')
                        <div class="alert alert-danger">{{ $errors->first('auto_login_string') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="shortcode" class="form-label">Shortcode</label>
            <input disabled  type="text"   name="shortcode" value="{{ old('shortcode',$data['student_registration']['shortcode']) }}" class="form-control" id="shortcode">
            @error('shortcode')
                        <div class="alert alert-danger">{{ $errors->first('shortcode') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="direct_login_url" class="form-label">Direct Login Url</label>
            <input disabled  type="text"   name="direct_login_url" value="{{ old('direct_login_url',$data['student_registration']['direct_login_url']) }}" class="form-control" id="direct_login_url">
            @error('direct_login_url')
                        <div class="alert alert-danger">{{ $errors->first('direct_login_url') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="direct_login_short_url" class="form-label">Direct Login Short Url</label>
            <input disabled  type="text"   name="direct_login_short_url" value="{{ old('direct_login_short_url',$data['student_registration']['direct_login_short_url']) }}" class="form-control" id="direct_login_short_url">
            @error('direct_login_short_url')
                        <div class="alert alert-danger">{{ $errors->first('direct_login_short_url') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="direct_login_qr_code_url" class="form-label">Direct Login Qr Code Url</label>
            <input disabled  type="text"   name="direct_login_qr_code_url" value="{{ old('direct_login_qr_code_url',$data['student_registration']['direct_login_qr_code_url']) }}" class="form-control" id="direct_login_qr_code_url">
            @error('direct_login_qr_code_url')
                        <div class="alert alert-danger">{{ $errors->first('direct_login_qr_code_url') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="direct_payment_url" class="form-label">Direct Payment Url</label>
            <input disabled  type="text"   name="direct_payment_url" value="{{ old('direct_payment_url',$data['student_registration']['direct_payment_url']) }}" class="form-control" id="direct_payment_url">
            @error('direct_payment_url')
                        <div class="alert alert-danger">{{ $errors->first('direct_payment_url') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="direct_payment_short_url" class="form-label">Direct Payment Short Url</label>
            <input disabled  type="text"   name="direct_payment_short_url" value="{{ old('direct_payment_short_url',$data['student_registration']['direct_payment_short_url']) }}" class="form-control" id="direct_payment_short_url">
            @error('direct_payment_short_url')
                        <div class="alert alert-danger">{{ $errors->first('direct_payment_short_url') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="seats" class="form-label">Seats</label>
            <input disabled  type="number"   name="seats" value="{{ old('seats',$data['student_registration']['seats']) }}" class="form-control" id="seats">
            @error('seats')
                        <div class="alert alert-danger">{{ $errors->first('seats') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="amount" class="form-label">Amount</label>
            <input disabled  type="number"   name="amount" value="{{ old('amount',$data['student_registration']['amount']) }}" class="form-control" id="amount">
            @error('amount')
                        <div class="alert alert-danger">{{ $errors->first('amount') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="tax_amount" class="form-label">Tax Amount</label>
            <input disabled  type="number"   name="tax_amount" value="{{ old('tax_amount',$data['student_registration']['tax_amount']) }}" class="form-control" id="tax_amount">
            @error('tax_amount')
                        <div class="alert alert-danger">{{ $errors->first('tax_amount') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="payment_gateway_fee" class="form-label">Payment Gateway Fee</label>
            <input disabled  type="number"   name="payment_gateway_fee" value="{{ old('payment_gateway_fee',$data['student_registration']['payment_gateway_fee']) }}" class="form-control" id="payment_gateway_fee">
            @error('payment_gateway_fee')
                        <div class="alert alert-danger">{{ $errors->first('payment_gateway_fee') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="total_fee_all_inclusive" class="form-label">Total Fee All Inclusive</label>
            <input disabled  type="number"   name="total_fee_all_inclusive" value="{{ old('total_fee_all_inclusive',$data['student_registration']['total_fee_all_inclusive']) }}" class="form-control" id="total_fee_all_inclusive">
            @error('total_fee_all_inclusive')
                        <div class="alert alert-danger">{{ $errors->first('total_fee_all_inclusive') }}</div>
            @enderror
        </div>



     
        <div class="col-md-12">
            <label for="payment_status_id" class="form-label">Payment Status</label>
            <select disabled id="payment_status_id" value="{{ old('payment_status_id',$data['student_registration']['payment_status_id']) }}" name="payment_status_id" class="form-select">
                     <option value=""><-- Select Country --> </option>
                     @foreach ($data['payment_statuses'] as $payment_status)
                <option value="{{ $payment_status->payment_status_id }}" {{ old('payment_status_id',$data['student_registration']['payment_status_id']) == $payment_status->payment_status_id ? 'selected' : '' }}>{{ $payment_status->payment_status }} </option>
                @endforeach
                   
                  
            </select>
            @error('payment_status_id')
                        <div class="alert alert-danger">{{ $errors->first('payment_status_id') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="payment_id" class="form-label">Payment Id</label>
            <input disabled  type="text"   name="payment_id" value="{{ old('payment_id',$data['student_registration']['payment_id']) }}" class="form-control" id="payment_id">
            @error('payment_id')
                        <div class="alert alert-danger">{{ $errors->first('payment_id') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="order_id" class="form-label">Order Id</label>
            <input disabled  type="text"   name="order_id" value="{{ old('order_id',$data['student_registration']['order_id']) }}" class="form-control" id="order_id">
            @error('order_id')
                        <div class="alert alert-danger">{{ $errors->first('order_id') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="payment_gateway_id" class="form-label">Payment Gateway Id</label>
            <input disabled  type="text"   name="payment_gateway_id" value="{{ old('payment_gateway_id',$data['student_registration']['payment_gateway_id']) }}" class="form-control" id="payment_gateway_id">
            @error('payment_gateway_id')
                        <div class="alert alert-danger">{{ $errors->first('payment_gateway_id') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="razorpay_webhook_status" class="form-label">Razorpay Webhook Status</label>
            <select disabled id="razorpay_webhook_status" value="{{ old('razorpay_webhook_status',$data['student_registration']['razorpay_webhook_status']) }}" name="razorpay_webhook_status" class="form-select">
                     <option value=""><-- Select Country --> </option>                    
                <option value="pending" {{ old('razorpay_webhook_status',$data['student_registration']['razorpay_webhook_status']) == 'pending' ? 'selected' : '' }}>Pending</option>

                <option value="ignored" {{ old('razorpay_webhook_status',$data['student_registration']['razorpay_webhook_status']) == 'ignored' ? 'selected' : '' }}>Ignored</option>


                <option value="Success" {{ old('razorpay_webhook_status',$data['student_registration']['razorpay_webhook_status']) == 'Success' ? 'selected' : '' }}>Success</option>


                <option value="Failed" {{ old('razorpay_webhook_status',$data['student_registration']['razorpay_webhook_status']) == 'Failed' ? 'selected' : '' }}>Failed</option>

               
                   
                  
            </select>
            @error('razorpay_webhook_status')
                        <div class="alert alert-danger">{{ $errors->first('razorpay_webhook_status') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="currency_id" class="form-label">Currency Id</label>
            <select disabled id="currency_id" value="{{ old('currency_id',$data['student_registration']['currency_id']) }}" name="currency_id" class="form-select">
                     <option value=""><-- Select Country --> </option>
                     @foreach ($data['currency_settings'] as $currency)
                <option value="{{ $currency->currency_id }}" {{ old('currency_id',$data['student_registration']['currency_id']) == $currency->currency_id ? 'selected' : '' }}>{{ $currency->code }} </option>
                @endforeach
                   
                  
            </select>
            @error('currency_id')
                        <div class="alert alert-danger">{{ $errors->first('currency_id') }}</div>
            @enderror
        </div>



      
        <div class="col-md-12">
            <label for="payment_timestamp" class="form-label">Payment Timestamp</label>
            <input disabled  type="text"   name="payment_timestamp" value="{{ old('payment_timestamp',$data['student_registration']['payment_timestamp']) }}" class="form-control" id="payment_timestamp">
            @error('payment_timestamp')
                        <div class="alert alert-danger">{{ $errors->first('payment_timestamp') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="student_selection_status_id" class="form-label">Selection Status</label>            
            <select disabled id="student_selection_status_id" value="{{ old('student_selection_status_id') }}" name="student_selection_status_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['students_selection_statuses'] as $students_selection_status)
                <option value="{{ $students_selection_status->student_selection_status_id }}" {{ old('student_selection_status_id',$data['student_registration']['student_selection_status_id']) == $students_selection_status->student_selection_status_id ? 'selected' : '' }} >{{ $students_selection_status->selection_status }}</option>
                @endforeach
                </select>

            @error('student_selection_status_id')
            <div class="alert alert-danger">{{ $errors->first('student_selection_status_id') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="student_invitation_status_id" class="form-label">Invitation Status</label>            
            <select disabled id="student_invitation_status_id" value="{{ old('student_invitation_status_id') }}" name="student_invitation_status_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['students_invitation_statuses'] as $students_invitation_status)
                <option value="{{ $students_invitation_status->student_invitation_status_id }}" {{ old('student_invitation_status_id',$data['student_registration']['student_invitation_status_id']) == $students_invitation_status->student_invitation_status_id ? 'selected' : '' }} >{{ $students_invitation_status->invitation_status }}</option>
                @endforeach
                </select>

            @error('student_invitation_status_id')
            <div class="alert alert-danger">{{ $errors->first('student_invitation_status_id') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="last_date" class="form-label">Last Date</label>
            <div class="date-input-wrapper">
                <input disabled  type="text" name="last_date" value="{{ old('last_date',$data['student_registration']['last_date']) }}" class="form-control" id="last_date" placeholder="yyyy-mm-dd">
                <i class="fas fa-calendar-alt"></i>
            </div>
            @error('last_date')
            <div class="alert alert-danger">{{ $errors->first('last_date') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="venue_id" class="form-label">Venue Id</label>
            <input disabled  type="text"   name="venue_id" value="{{ old('venue_id',$data['student_registration']['venue_id']) }}" class="form-control" id="venue_id">
            @error('venue_id')
                        <div class="alert alert-danger">{{ $errors->first('venue_id') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="timing" class="form-label">Timing</label>
            <input disabled  type="time"   name="timing" value="{{ old('timing',$data['student_registration']['timing']) }}" class="form-control" id="timing">
            @error('timing')
                        <div class="alert alert-danger">{{ $errors->first('timing') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="student_certificate_status_id" class="form-label">Student Certificate Status</label>            
            <select disabled id="student_certificate_status_id" value="{{ old('student_certificate_status_id') }}" name="student_certificate_status_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['students_certificate_statuses'] as $students_certificate_status)
                <option value="{{ $students_certificate_status->student_certificate_status_id }}" {{ old('student_certificate_status_id',$data['student_registration']['student_certificate_status_id']) == $students_certificate_status->student_certificate_status_id ? 'selected' : '' }} >{{ $students_certificate_status->certificate_status }}</option>
                @endforeach
                </select>

            @error('student_certificate_status_id')
            <div class="alert alert-danger">{{ $errors->first('student_certificate_status_id') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="certificate_print_status" class="form-label">Certificate Print Status</label>
            <select disabled id="certificate_print_status" value="{{ old('certificate_print_status',$data['student_registration']['certificate_print_status']) }}" name="certificate_print_status" class="form-select">
                     <option value=""><-- Select Country --> </option>                    
                <option value="pending" {{ old('certificate_print_status',$data['student_registration']['certificate_print_status']) == 'pending' ? 'selected' : '' }}>Pending</option>

                <option value="printed" {{ old('certificate_print_status',$data['student_registration']['certificate_print_status']) == 'printed' ? 'selected' : '' }}>Printed</option>           
                   
                  
            </select>
            @error('certificate_print_status')
                        <div class="alert alert-danger">{{ $errors->first('certificate_print_status') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="certificate_title" class="form-label">Certificate Title</label>
            <input disabled  type="text"   name="certificate_title" value="{{ old('certificate_title',$data['student_registration']['certificate_title']) }}" class="form-control" id="certificate_title">
            @error('certificate_title')
                        <div class="alert alert-danger">{{ $errors->first('certificate_title') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="certificate_code" class="form-label">Certificate Code</label>
            <input disabled  type="text"   name="certificate_code" value="{{ old('certificate_code',$data['student_registration']['certificate_code']) }}" class="form-control" id="certificate_code">
            @error('certificate_code')
                        <div class="alert alert-danger">{{ $errors->first('certificate_code') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="referrer" class="form-label">Referrer</label>
            <input disabled  type="text"   name="referrer" value="{{ old('referrer',$data['student_registration']['referrer']) }}" class="form-control" id="referrer">
            @error('referrer')
                        <div class="alert alert-danger">{{ $errors->first('referrer') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="utm_source" class="form-label">Utm Source</label>
            <input disabled  type="text"   name="utm_source" value="{{ old('utm_source',$data['student_registration']['utm_source']) }}" class="form-control" id="utm_source">
            @error('utm_source')
                        <div class="alert alert-danger">{{ $errors->first('utm_source') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="utm_medium" class="form-label">Utm Medium</label>
            <input disabled  type="text"   name="utm_medium" value="{{ old('utm_medium',$data['student_registration']['utm_medium']) }}" class="form-control" id="utm_medium">
            @error('utm_medium')
                        <div class="alert alert-danger">{{ $errors->first('utm_medium') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="utm_campaign" class="form-label">Utm Campaign</label>
            <input disabled  type="text"   name="utm_campaign" value="{{ old('utm_campaign',$data['student_registration']['utm_campaign']) }}" class="form-control" id="utm_campaign">
            @error('utm_campaign')
                        <div class="alert alert-danger">{{ $errors->first('utm_campaign') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="utm_term" class="form-label">Utm Term</label>
            <input disabled  type="text"   name="utm_term" value="{{ old('utm_term',$data['student_registration']['utm_term']) }}" class="form-control" id="utm_term">
            @error('utm_term')
                        <div class="alert alert-danger">{{ $errors->first('utm_term') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="utm_content" class="form-label">Utm Content</label>
            <input disabled  type="text"   name="utm_content" value="{{ old('utm_content',$data['student_registration']['utm_content']) }}" class="form-control" id="utm_content">
            @error('utm_content')
                        <div class="alert alert-danger">{{ $errors->first('utm_content') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="full_url" class="form-label">Full Url</label>
            <input disabled  type="text"   name="full_url" value="{{ old('full_url',$data['student_registration']['full_url']) }}" class="form-control" id="full_url">
            @error('full_url')
                        <div class="alert alert-danger">{{ $errors->first('full_url') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="zoik_app_workshop_list_subscriber_uid" class="form-label">Zoik App Workshop List Subscriber Uid</label>
            <input disabled  type="text"   name="zoik_app_workshop_list_subscriber_uid" value="{{ old('zoik_app_workshop_list_subscriber_uid',$data['student_registration']['zoik_app_workshop_list_subscriber_uid']) }}" class="form-control" id="zoik_app_workshop_list_subscriber_uid">
            @error('zoik_app_workshop_list_subscriber_uid')
                        <div class="alert alert-danger">{{ $errors->first('zoik_app_workshop_list_subscriber_uid') }}</div>
            @enderror
        </div>





        <div class="col-md-12">
            <label for="zoik_app_common_list_subscriber_uid" class="form-label">Zoik App Common List Subscriber Uid</label>
            <input disabled  type="text"   name="zoik_app_common_list_subscriber_uid" value="{{ old('zoik_app_common_list_subscriber_uid',$data['student_registration']['zoik_app_common_list_subscriber_uid']) }}" class="form-control" id="zoik_app_common_list_subscriber_uid">
            @error('zoik_app_common_list_subscriber_uid')
                        <div class="alert alert-danger">{{ $errors->first('zoik_app_common_list_subscriber_uid') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="registration_update_url_on_zoik_app" class="form-label">Registration Update Url On Zoik App</label>
            <input disabled  type="text"   name="registration_update_url_on_zoik_app" value="{{ old('registration_update_url_on_zoik_app',$data['student_registration']['registration_update_url_on_zoik_app']) }}" class="form-control" id="registration_update_url_on_zoik_app">
            @error('registration_update_url_on_zoik_app')
                        <div class="alert alert-danger">{{ $errors->first('registration_update_url_on_zoik_app') }}</div>
            @enderror
        </div>





        <div class="col-md-12">
            <label for="whatsapp_api_log_registration_success" class="form-label">Whatsapp Api Log Registration Success</label>
            <input disabled  type="text"   name="whatsapp_api_log_registration_success" value="{{ old('whatsapp_api_log_registration_success',$data['student_registration']['whatsapp_api_log_registration_success']) }}" class="form-control" id="whatsapp_api_log_registration_success">
            @error('whatsapp_api_log_registration_success')
                        <div class="alert alert-danger">{{ $errors->first('whatsapp_api_log_registration_success') }}</div>
            @enderror
        </div>





        <div class="col-md-12">
            <label for="whatsapp_api_log_payment_success" class="form-label">Whatsapp Api Log Payment Success</label>
            <input disabled  type="text"   name="whatsapp_api_log_payment_success" value="{{ old('whatsapp_api_log_payment_success',$data['student_registration']['whatsapp_api_log_payment_success']) }}" class="form-control" id="whatsapp_api_log_payment_success">
            @error('whatsapp_api_log_payment_success')
                        <div class="alert alert-danger">{{ $errors->first('whatsapp_api_log_payment_success') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="whatsapp_optin" class="form-label">Whatsapp Optin</label>
            <select disabled id="whatsapp_optin" value="{{ old('whatsapp_optin',$data['student_registration']['whatsapp_optin']) }}" name="whatsapp_optin" class="form-select">
                     <option value=""><-- Select --> </option>                    
                <option value="Y" {{ old('whatsapp_optin',$data['student_registration']['whatsapp_optin']) == 'Y' ? 'selected' : '' }}>Yes</option>

                <option value="N" {{ old('whatsapp_optin',$data['student_registration']['whatsapp_optin']) == 'N' ? 'selected' : '' }}>No</option>           
                   
                  
            </select>
            @error('whatsapp_optin')
                        <div class="alert alert-danger">{{ $errors->first('whatsapp_optin') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="whatsapp_optout_datetime" class="form-label">Whatsapp Optout Datetime</label>
            <div class="date-input-wrapper">
                <input disabled  type="text" name="whatsapp_optout_datetime" value="{{ old('whatsapp_optout_datetime',$data['student_registration']['whatsapp_optout_datetime']) }}" class="form-control" id="whatsapp_optout_datetime" placeholder="yyyy-mm-dd">
                <i class="fas fa-calendar-alt"></i>
            </div>
            @error('whatsapp_optout_datetime')
            <div class="alert alert-danger">{{ $errors->first('whatsapp_optout_datetime') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="whatsapp_re_optin_datetime" class="form-label">Whatsapp Re Optin Datetime</label>
            <div class="date-input-wrapper">
                <input disabled  type="text" name="whatsapp_re_optin_datetime" value="{{ old('whatsapp_re_optin_datetime',$data['student_registration']['whatsapp_re_optin_datetime']) }}" class="form-control" id="whatsapp_re_optin_datetime" placeholder="yyyy-mm-dd">
                <i class="fas fa-calendar-alt"></i>
            </div>
            @error('whatsapp_re_optin_datetime')
            <div class="alert alert-danger">{{ $errors->first('whatsapp_re_optin_datetime') }}</div>
            @enderror
        </div>      





        <div class="col-md-12">
            <label for="whatsapp_optout_webbook_log" class="form-label">Whatsapp Optout Webbook Log</label>
            <input disabled  type="text"   name="whatsapp_optout_webbook_log" value="{{ old('whatsapp_optout_webbook_log',$data['student_registration']['whatsapp_optout_webbook_log']) }}" class="form-control" id="whatsapp_optout_webbook_log">
            @error('whatsapp_optout_webbook_log')
                        <div class="alert alert-danger">{{ $errors->first('whatsapp_optout_webbook_log') }}</div>
            @enderror
        </div>





        <div class="col-md-12">
            <label for="whatsapp_optout_webbook_log" class="form-label">Whatsapp Optout Webbook Log</label>
            <input disabled  type="text"   name="whatsapp_optout_webbook_log" value="{{ old('whatsapp_optout_webbook_log',$data['student_registration']['whatsapp_optout_webbook_log']) }}" class="form-control" id="whatsapp_optout_webbook_log">
            @error('whatsapp_optout_webbook_log')
                        <div class="alert alert-danger">{{ $errors->first('whatsapp_optout_webbook_log') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="whatsapp_re_optin_webbook_log" class="form-label">Whatsapp Re Optin Webbook Log</label>
            <input disabled  type="text"   name="whatsapp_re_optin_webbook_log" value="{{ old('whatsapp_re_optin_webbook_log',$data['student_registration']['whatsapp_re_optin_webbook_log']) }}" class="form-control" id="whatsapp_re_optin_webbook_log">
            @error('whatsapp_re_optin_webbook_log')
                        <div class="alert alert-danger">{{ $errors->first('whatsapp_re_optin_webbook_log') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="whatsapp_optout_notification_api_log" class="form-label">Whatsapp Optout Notification Api Log</label>
            <input disabled  type="text"   name="whatsapp_optout_notification_api_log" value="{{ old('whatsapp_optout_notification_api_log',$data['student_registration']['whatsapp_optout_notification_api_log']) }}" class="form-control" id="whatsapp_optout_notification_api_log">
            @error('whatsapp_optout_notification_api_log')
                        <div class="alert alert-danger">{{ $errors->first('whatsapp_optout_notification_api_log') }}</div>
            @enderror
        </div>



      

        <div class="col-md-12">
            <label for="whatsapp_re_optin_notification_api_log" class="form-label">Whatsapp Re Optin Notification Api Log</label>
            <input disabled  type="text"   name="whatsapp_re_optin_notification_api_log" value="{{ old('whatsapp_re_optin_notification_api_log',$data['student_registration']['whatsapp_re_optin_notification_api_log']) }}" class="form-control" id="whatsapp_re_optin_notification_api_log">
            @error('whatsapp_re_optin_notification_api_log')
                        <div class="alert alert-danger">{{ $errors->first('whatsapp_re_optin_notification_api_log') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="student_registration_status_id" class="form-label">Student Registration Status</label>
            <select disabled id="student_registration_status_id" name="student_registration_status_id" class="form-select">
                <option value=""><-- Status --> </option>
                @foreach ($data['students_registration_statuses'] as $students_registration_status)
                <option value="{{ $students_registration_status->student_registration_status_id }}" {{ old('student_registration_status_id',$data['student_registration']['student_registration_status_id']) ==  $students_registration_status->student_registration_status_id ? 'selected' : '' }} >{{ $students_registration_status->registration_status }}</option>
                @endforeach
               

            </select>
            @error('student_registration_status_id')
                        <div class="alert alert-danger">{{ $errors->first('student_registration_status_id') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="send_reg_notification_cron_start_datetime" class="form-label">Send Reg Notification Cron Start Datetime</label>
            <div class="date-input-wrapper">
                <input disabled  type="text" name="send_reg_notification_cron_start_datetime" value="{{ old('send_reg_notification_cron_start_datetime',$data['student_registration']['send_reg_notification_cron_start_datetime']) }}" class="form-control" id="send_reg_notification_cron_start_datetime" placeholder="yyyy-mm-dd">
                <i class="fas fa-calendar-alt"></i>
            </div>
            @error('send_reg_notification_cron_start_datetime')
            <div class="alert alert-danger">{{ $errors->first('send_reg_notification_cron_start_datetime') }}</div>
            @enderror
        </div>



       



        <div class="col-md-12">
            <label for="send_reg_notification_cron_status" class="form-label">Send Reg Notification Cron Status</label>
            <select disabled id="send_reg_notification_cron_status" value="{{ old('send_reg_notification_cron_status',$data['student_registration']['send_reg_notification_cron_status']) }}" name="send_reg_notification_cron_status" class="form-select">
                     <option value=""><-- Select --> </option>                    
                <option value="pending" {{ old('send_reg_notification_cron_status',$data['student_registration']['send_reg_notification_cron_status']) == 'pending' ? 'selected' : '' }}>pending</option>

                <option value="running" {{ old('send_reg_notification_cron_status',$data['student_registration']['send_reg_notification_cron_status']) == 'running' ? 'selected' : '' }}>Running</option>

                <option value="success" {{ old('send_reg_notification_cron_status',$data['student_registration']['send_reg_notification_cron_status']) == 'success' ? 'selected' : '' }}>Success</option>


                <option value="failed" {{ old('send_reg_notification_cron_status',$data['student_registration']['send_reg_notification_cron_status']) == 'failed' ? 'selected' : '' }}>Failed</option>

                      
                   
                  
            </select>
            @error('send_reg_notification_cron_status')
                        <div class="alert alert-danger">{{ $errors->first('send_reg_notification_cron_status') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="send_reg_notification_cron_end_datetime" class="form-label">Send Reg Notification Cron End Datetime</label>
            <div class="date-input-wrapper">
                <input disabled  type="text" name="send_reg_notification_cron_end_datetime" value="{{ old('send_reg_notification_cron_end_datetime',$data['student_registration']['send_reg_notification_cron_end_datetime']) }}" class="form-control" id="send_reg_notification_cron_end_datetime" placeholder="yyyy-mm-dd">
                <i class="fas fa-calendar-alt"></i>
            </div>
            @error('send_reg_notification_cron_end_datetime')
            <div class="alert alert-danger">{{ $errors->first('send_reg_notification_cron_end_datetime') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="send_reg_notification_cron_log" class="form-label">Send Reg Notification Cron Log</label>
            <input disabled  type="text"   name="send_reg_notification_cron_log" value="{{ old('send_reg_notification_cron_log',$data['student_registration']['send_reg_notification_cron_log']) }}" class="form-control" id="send_reg_notification_cron_log">
            @error('send_reg_notification_cron_log')
                        <div class="alert alert-danger">{{ $errors->first('send_reg_notification_cron_log') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="zoik_app_registration_sync_cron_status" class="form-label">Zoik App Registration Sync Cron Status</label>
            <select disabled id="zoik_app_registration_sync_cron_status" value="{{ old('zoik_app_registration_sync_cron_status',$data['student_registration']['zoik_app_registration_sync_cron_status']) }}" name="zoik_app_registration_sync_cron_status" class="form-select">
                     <option value=""><-- Select --> </option>                    
                <option value="pending" {{ old('zoik_app_registration_sync_cron_status',$data['student_registration']['zoik_app_registration_sync_cron_status']) == 'pending' ? 'selected' : '' }}>pending</option>

                <option value="running" {{ old('zoik_app_registration_sync_cron_status',$data['student_registration']['zoik_app_registration_sync_cron_status']) == 'running' ? 'selected' : '' }}>Running</option>

                <option value="success" {{ old('zoik_app_registration_sync_cron_status',$data['student_registration']['zoik_app_registration_sync_cron_status']) == 'success' ? 'selected' : '' }}>Success</option>


                <option value="failed" {{ old('zoik_app_registration_sync_cron_status',$data['student_registration']['zoik_app_registration_sync_cron_status']) == 'failed' ? 'selected' : '' }}>Failed</option>

                      
                   
                  
            </select>
            @error('zoik_app_registration_sync_cron_status')
                        <div class="alert alert-danger">{{ $errors->first('zoik_app_registration_sync_cron_status') }}</div>
            @enderror
        </div>






        <div class="col-md-12">
            <label for="zoik_app_registration_sync_cron_start_datetime" class="form-label">Zoik App Registration Sync Cron Start Datetime</label>
            <div class="date-input-wrapper">
                <input disabled  type="text" name="zoik_app_registration_sync_cron_start_datetime" value="{{ old('zoik_app_registration_sync_cron_start_datetime',$data['student_registration']['zoik_app_registration_sync_cron_start_datetime']) }}" class="form-control" id="zoik_app_registration_sync_cron_start_datetime" placeholder="yyyy-mm-dd">
                <i class="fas fa-calendar-alt"></i>
            </div>
            @error('zoik_app_registration_sync_cron_start_datetime')
            <div class="alert alert-danger">{{ $errors->first('zoik_app_registration_sync_cron_start_datetime') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="zoik_app_registration_sync_cron_end_datetime" class="form-label">Zoik App Registration Sync Cron End Datetime</label>
            <div class="date-input-wrapper">
                <input disabled  type="text" name="zoik_app_registration_sync_cron_end_datetime" value="{{ old('zoik_app_registration_sync_cron_end_datetime',$data['student_registration']['zoik_app_registration_sync_cron_end_datetime']) }}" class="form-control" id="zoik_app_registration_sync_cron_end_datetime" placeholder="yyyy-mm-dd">
                <i class="fas fa-calendar-alt"></i>
            </div>
            @error('zoik_app_registration_sync_cron_end_datetime')
            <div class="alert alert-danger">{{ $errors->first('zoik_app_registration_sync_cron_end_datetime') }}</div>
            @enderror
        </div>







        
        <div class="col-md-12">
            <label for="sms_cron_status" class="form-label">Sms Cron Status</label>
            <select disabled id="sms_cron_status" value="{{ old('sms_cron_status',$data['student_registration']['sms_cron_status']) }}" name="sms_cron_status" class="form-select">
                     <option value=""><-- Select --> </option>                    
                <option value="pending" {{ old('sms_cron_status',$data['student_registration']['sms_cron_status']) == 'pending' ? 'selected' : '' }}>pending</option>

                <option value="success" {{ old('sms_cron_status',$data['student_registration']['sms_cron_status']) == 'success' ? 'selected' : '' }}>Success</option>

                <option value="failed" {{ old('sms_cron_status',$data['student_registration']['sms_cron_status']) == 'failed' ? 'selected' : '' }}>failed</option>
                  
            </select>
            @error('sms_cron_status')
                        <div class="alert alert-danger">{{ $errors->first('sms_cron_status') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="sms_cron_status_log" class="form-label">Sms Cron Status Log</label>
            <input disabled  type="text"   name="sms_cron_status_log" value="{{ old('sms_cron_status_log',$data['student_registration']['sms_cron_status_log']) }}" class="form-control" id="sms_cron_status_log">
            @error('sms_cron_status_log')
                        <div class="alert alert-danger">{{ $errors->first('sms_cron_status_log') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="email_cron_status" class="form-label">Email Cron Status</label>
            <select disabled id="email_cron_status" value="{{ old('email_cron_status',$data['student_registration']['email_cron_status']) }}" name="email_cron_status" class="form-select">
                     <option value=""><-- Select --> </option>                    
                <option value="pending" {{ old('email_cron_status',$data['student_registration']['email_cron_status']) == 'pending' ? 'selected' : '' }}>pending</option>

                <option value="success" {{ old('email_cron_status',$data['student_registration']['email_cron_status']) == 'success' ? 'selected' : '' }}>Success</option>

                <option value="failed" {{ old('email_cron_status',$data['student_registration']['email_cron_status']) == 'failed' ? 'selected' : '' }}>failed</option>
                  
            </select>
            @error('email_cron_status')
                        <div class="alert alert-danger">{{ $errors->first('email_cron_status') }}</div>
            @enderror
        </div>





        <div class="col-md-12">
            <label for="whatsapp_optout_notification_api_log" class="form-label">Whatsapp Optout Notification Api Log</label>
            <input disabled  type="text"   name="whatsapp_optout_notification_api_log" value="{{ old('whatsapp_optout_notification_api_log',$data['student_registration']['whatsapp_optout_notification_api_log']) }}" class="form-control" id="whatsapp_optout_notification_api_log">
            @error('whatsapp_optout_notification_api_log')
                        <div class="alert alert-danger">{{ $errors->first('whatsapp_optout_notification_api_log') }}</div>
            @enderror
        </div>





        <div class="col-md-12">
            <label for="email_cron_status_log" class="form-label">Email Cron Status Log</label>
            <input disabled  type="text"   name="email_cron_status_log" value="{{ old('email_cron_status_log',$data['student_registration']['email_cron_status_log']) }}" class="form-control" id="email_cron_status_log">
            @error('email_cron_status_log')
                        <div class="alert alert-danger">{{ $errors->first('email_cron_status_log') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="whatsapp_cron_status" class="form-label">Whatsapp Cron Status</label>
            <select disabled id="whatsapp_cron_status" value="{{ old('whatsapp_cron_status',$data['student_registration']['whatsapp_cron_status']) }}" name="whatsapp_cron_status" class="form-select">
                     <option value=""><-- Select --> </option>                    
                <option value="pending" {{ old('whatsapp_cron_status',$data['student_registration']['whatsapp_cron_status']) == 'pending' ? 'selected' : '' }}>pending</option>

                <option value="success" {{ old('whatsapp_cron_status',$data['student_registration']['whatsapp_cron_status']) == 'success' ? 'selected' : '' }}>Success</option>

                <option value="failed" {{ old('whatsapp_cron_status',$data['student_registration']['whatsapp_cron_status']) == 'failed' ? 'selected' : '' }}>failed</option>
                  
            </select>
            @error('whatsapp_cron_status')
                        <div class="alert alert-danger">{{ $errors->first('whatsapp_cron_status') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="whatsapp_cron_status_log" class="form-label">Whatsapp Cron Status Log</label>
            <input disabled  type="text"   name="whatsapp_cron_status_log" value="{{ old('whatsapp_cron_status_log',$data['student_registration']['whatsapp_cron_status_log']) }}" class="form-control" id="whatsapp_cron_status_log">
            @error('whatsapp_cron_status_log')
                        <div class="alert alert-danger">{{ $errors->first('whatsapp_cron_status_log') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="email_status" class="form-label">Email Status</label>
            <select disabled id="email_status" value="{{ old('email_status',$data['student_registration']['email_status']) }}" name="email_status" class="form-select">
                     <option value=""><-- Select --> </option>                    
                <option value="active" {{ old('email_status',$data['student_registration']['email_status']) == 'active' ? 'selected' : '' }}>Active</option>

                <option value="bounced" {{ old('email_status',$data['student_registration']['email_status']) == 'bounced' ? 'selected' : '' }}>Bounced</option>

                
                  
            </select>
            @error('email_status')
                        <div class="alert alert-danger">{{ $errors->first('email_status') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="email_bounce_log" class="form-label">Email Bounce Log</label>
            <input disabled  type="text"   name="email_bounce_log" value="{{ old('email_bounce_log',$data['student_registration']['email_bounce_log']) }}" class="form-control" id="email_bounce_log">
            @error('email_bounce_log')
                        <div class="alert alert-danger">{{ $errors->first('email_bounce_log') }}</div>
            @enderror
        </div>





        <div class="col-md-12">
            <label for="email_bounce_datetime" class="form-label">Email Bounce Datetime</label>
            <input disabled  type="text"   name="email_bounce_datetime" value="{{ old('email_bounce_datetime',$data['student_registration']['email_bounce_datetime']) }}" class="form-control" id="email_bounce_datetime">
            @error('email_bounce_datetime')
                        <div class="alert alert-danger">{{ $errors->first('email_bounce_datetime') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="registration_time" class="form-label">Registration Time</label>
            <div class="date-input-wrapper">
                <input disabled  type="text" name="registration_time" value="{{ old('registration_time',$data['student_registration']['registration_time']) }}" class="form-control" id="registration_time" placeholder="yyyy-mm-dd">
                <i class="fas fa-calendar-alt"></i>
            </div>
            @error('registration_time')
            <div class="alert alert-danger">{{ $errors->first('registration_time') }}</div>
            @enderror
        </div>
        

      





        <div class="col-md-12">
            <label for="shipping_address_firstname" class="form-label">Shipping Address Firstname</label>
            <input disabled  type="text"   name="shipping_address_firstname" value="{{ old('shipping_address_firstname',$data['student_registration']['shipping_address_firstname']) }}" class="form-control" id="shipping_address_firstname">
            @error('shipping_address_firstname')
                        <div class="alert alert-danger">{{ $errors->first('shipping_address_firstname') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="shipping_address_lastname" class="form-label">Shipping Address Lastname</label>
            <input disabled  type="text"   name="shipping_address_lastname" value="{{ old('shipping_address_lastname',$data['student_registration']['shipping_address_lastname']) }}" class="form-control" id="shipping_address_lastname">
            @error('shipping_address_lastname')
                        <div class="alert alert-danger">{{ $errors->first('shipping_address_lastname') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="shipping_address_line_1" class="form-label">Shipping Address Line 1</label>
            <input disabled  type="text"   name="shipping_address_line_1" value="{{ old('shipping_address_line_1',$data['student_registration']['shipping_address_line_1']) }}" class="form-control" id="shipping_address_line_1">
            @error('shipping_address_line_1')
                        <div class="alert alert-danger">{{ $errors->first('shipping_address_line_1') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="shipping_address_line_2" class="form-label">Shipping Address Line 2</label>
            <input disabled  type="text"   name="shipping_address_line_2" value="{{ old('shipping_address_line_2',$data['student_registration']['shipping_address_line_2']) }}" class="form-control" id="shipping_address_line_2">
            @error('shipping_address_line_2')
                        <div class="alert alert-danger">{{ $errors->first('shipping_address_line_2') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="shipping_address_city" class="form-label">Shipping Address City</label>
            <input disabled  type="text"   name="shipping_address_city" value="{{ old('shipping_address_city',$data['student_registration']['shipping_address_city']) }}" class="form-control" id="shipping_address_city">
            @error('shipping_address_city')
                        <div class="alert alert-danger">{{ $errors->first('shipping_address_city') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="shipping_address_country_id" class="form-label">Country</label>
            <select disabled id="shipping_address_country_id" value="{{ old('shipping_address_country_id',$data['student_registration']['shipping_address_country_id']) }}" name="shipping_address_country_id" class="form-select">
                     <option value=""><-- Select Country --> </option>
                     @foreach ($data['countries'] as $country)
                <option value="{{ $country->country_id }}" {{ old('country_id',$data['student_registration']['country_id']) == $country->country_id ? 'selected' : '' }}>{{ $country->country_name }} </option>
                @endforeach
                   
                  
            </select>
            @error('shipping_address_country_id')
                        <div class="alert alert-danger">{{ $errors->first('shipping_address_country_id') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="shipping_address_state_id" class="form-label">State</label>
            <select disabled id="shipping_address_state_id" value="{{ old('shipping_address_state_id',$data['student_registration']['shipping_address_state_id']) }}" name="shipping_address_state_id" class="form-select">
                     <option value=""><-- Select Country --> </option>
                     @foreach ($data['technocon_country_to_state_code'] as $state)
                <option value="{{ $state->country_state_id }}" {{ old('country_state_id',$data['student_registration']['shipping_address_state_id']) == $state->country_state_id ? 'selected' : '' }}>{{ $state->state_name }} </option>
                @endforeach
                   
                  
            </select>
            @error('shipping_address_state_id')
                        <div class="alert alert-danger">{{ $errors->first('shipping_address_state_id') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="shipping_address_post_code" class="form-label">Shipping Address Post Code</label>
            <input disabled  type="text"   name="shipping_address_post_code" value="{{ old('shipping_address_post_code',$data['student_registration']['shipping_address_post_code']) }}" class="form-control" id="shipping_address_post_code">
            @error('shipping_address_post_code')
                        <div class="alert alert-danger">{{ $errors->first('shipping_address_post_code') }}</div>
            @enderror
        </div>


     <div class="col-md-3">
            <label for="shipping_address_mobile_country_code_id" class="form-label">Country Code</label>
            <select disabled id="shipping_address_mobile_country_code_id" value="{{ old('shipping_address_mobile_country_code_id',$data['student_registration']['shipping_address_mobile_country_code_id']) }}" name="shipping_address_mobile_country_code_id" class="form-select">
                     <option value=""><-- Select Country Code --> </option>
                     @foreach ($data['countries'] as $country)
                <option value="{{ $country->country_code }}" {{ old('country_code',$data['student_registration']['shipping_address_mobile_country_code_id']) == $country->country_code ? 'selected' : '' }}>+{{ $country->country_code }} ( {{ $country->country_name }} )</option>
                @endforeach
                   
                  
            </select>
            @error('shipping_address_mobile_country_code_id')
                        <div class="alert alert-danger">{{ $errors->first('shipping_address_mobile_country_code_id') }}</div>
            @enderror
        </div>

        <div class="col-md-9">
            <label for="shipping_address_mobile" class="form-label">Mobile Number</label>
            <input disabled  type="text"   name="shipping_address_mobile" value="{{ old('shipping_address_mobile',$data['student_registration']['shipping_address_mobile']) }}" class="form-control" id="shipping_address_mobile">
            @error('shipping_address_mobile')
                        <div class="alert alert-danger">{{ $errors->first('shipping_address_mobile') }}</div>
            @enderror
        </div>





        <div class="col-md-12">
            <label for="last_update_datetime" class="form-label">Last Update Datetime</label>
            <input disabled  type="text"   name="last_update_datetime" value="{{ old('last_update_datetime',$data['student_registration']['last_update_datetime']) }}" class="form-control" id="last_update_datetime">
            @error('last_update_datetime')
                        <div class="alert alert-danger">{{ $errors->first('last_update_datetime') }}</div>
            @enderror
        </div>

        <div class="col-md-12 mt-5">
            <button type="submit" id="submit" class="btn btn-sm btn-primary"><i class="fa-regular fa-floppy-disk"></i>Update</button>
        </div>

    </form>
