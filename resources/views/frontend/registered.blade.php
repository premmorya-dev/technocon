@extends('frontend.master')
@section('content')

<body style="overflow-x:hidden;">
    @if($data['form_setting']['start_body_script'])
    {!! $data['form_setting']['start_body_script'] !!}
    @endif

    @if( isset( $data['registration_success_event_url_parameter']) && $data['registration_success_event_url_parameter'] == $data['form_setting']['registration_success_event_url_parameter_value'] && $data['form_setting']['registration_success_event_script_position'] == 'after_body_start' )
    {!! $data['form_setting']['registration_success_event_script'] !!}
    @endif


    <div id="wrapper">
        <section id="registration-form-view">
            <div class="content-wrap content-wrap-workshop-registration">
                <div class="container">
                    <div class="mx-auto mb-0" id="tab-login-register" style="max-width: 700px;">
                        <div class="card mb-0s">
                            <div class="card-body" style="padding: 10px;">
                                <div class="row " style="border-bottom:var(--cnvs-themecolor) solid 5px;">
                                    <div>
                                        <img src="{{ asset($data['form_setting']['page_header_banner_image_url']) }}" alt="{{ asset($data['form_setting']['page_header_banner_image_alt']) }}">
                                    </div>
                                </div>
                                @if( isset($data['registration_data']['after_registration_show_support_contacts']) && $data['registration_data']['after_registration_show_support_contacts'] == 'Y' )
                                <div class="row">
                                    <div class="col-md-12 center">
                                        <strong>For Help & Support</strong>
                                    </div>

                                    @if( isset($data['registration_data']['support_contacts_json']) && $data['registration_data']['support_contacts_json'])

                                    @foreach($data['registration_data']['support_contacts_json'] as $position => $number)
                                    <div class="col-md-6">
                                        <span>{{ $position }} : {{$number}}</span>
                                    </div>

                                    @endforeach

                                    @endif
                                </div>

                                @endif

                                <div class="promo promo-light mt-2">
                                    <h4 class="mt-3 center">{{ $data['form_setting']['registration_page_form_heading'] ?? '' }}</h4>
                                </div>
                                <div class="row">




                                    <div class="col-md-5">
                                        <label for="Name">Name:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Name">
                                            {{ $data['registration_data']['first_name'] ?? '' }} {{ $data['registration_data']['last_name'] ?? '' }} </label>
                                    </div>




                                    <div class="col-md-5">
                                        <label for="Email">{{ $data['form_setting']['lable_email_field'] ?? '' }}:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Email Value">
                                            {{ $data['registration_data']['registered_email'] ?? '' }} </label>
                                    </div>


                                    <div class="col-md-5">
                                        <label for="Mobile No">{{ $data['registration_data']['lable_mobile_no_field'] ??  '' }}:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Mobile No Value">
                                            +{{ $data['registration_data']['country_code'] ?? '' }}-{{ $data['registration_data']['mobile_no'] ?? '' }} </label>
                                    </div>

                                    @if(!empty($data['form_setting']['after_registration_enable_college_insitute_field']) && $data['form_setting']['after_registration_enable_college_insitute_field'] == 'Y' )

                                    <div class="col-md-5">
                                        <label for="College Name">{{ $data['form_setting']['lable_college_field'] ?? '' }}:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="College Name Value">
                                            {{ $data['registration_data']['college'] ?? ''}} </label>
                                    </div>

                                    @endif



                                    @if(!empty($data['form_setting']['after_registration_enable_city_field']) && $data['form_setting']['after_registration_enable_city_field'] == 'Y' )

                                    <div class="col-md-5">
                                        <label for="City">{{ $data['form_setting']['lable_city_field'] ?? '' }}:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="City Value">
                                            {{ $data['registration_data']['city'] ?? ''}} </label>
                                    </div>
                                    @endif




                                    @if(!empty($data['form_setting']['after_registration_enable_country_field']) && $data['form_setting']['after_registration_enable_country_field'] == 'Y' )

                                    <div class="col-md-5">
                                        <label for="City">Country:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Country">
                                            {{ $data['registration_data']['country_name'] ?? '' }} </label>
                                    </div>
                                    @endif



                                    @if(!empty($data['form_setting']['after_registration_enable_state_field']) && $data['form_setting']['after_registration_enable_state_field'] == 'Y' )

                                    <div class="col-md-5">
                                        <label for="City">State:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="State">
                                            {{ $data['registration_data']['state_name'] ?? ''}} </label>
                                    </div>

                                    @endif



                                    <div class="col-md-5">
                                        <label for="Program Name">Program Name:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Program Name Value">
                                            <a href="{{ $data['registration_data']['program_details_page_url'] ?? '' }}"> {{ $data['registration_data']['program_name'] ?? '' }} <i class="fa-solid fa-arrow-up-right-from-square"></i></a>

                                        </label>

                                    </div>



                                    @if( isset($data['registration_data']['after_registration_show_sample_certificate']) && $data['registration_data']['after_registration_show_sample_certificate'] == 'Y')
                                    <div class="col-md-5">
                                        <label for="Program Name">Sample Certificate:</label>
                                    </div>
                                    <div class="col-md-7">


                                        <a href="#" id="view-certificate-modal-btn"> <img src="{{ asset($data['registration_data']['sample_certificate_url']) ?? '' }}" width="400px;" alt="sample certificate"></a>
                                    </div>

                                    @endif




                                    <div class="col-md-5">
                                        <label for="Program Date">Program Start Date:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Program Date Value">
                                            {{ $data['registration_data']['start_dates'] ?? '' }} {{ $data['registration_data']['start_time'] ?? '' }} ({{ $data['registration_data']['program_time_zone'] ?? '' }})</label>
                                    </div>

                                    <div class="col-md-5">
                                        <label for="Program Date">Program End Date:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Program Date Value">
                                            {{ $data['registration_data']['end_dates'] ?? '' }} {{ $data['registration_data']['end_time'] ?? '' }} ({{ $data['registration_data']['program_time_zone'] ?? ''}})</label>
                                    </div>


                                    <div class="col-md-5">
                                        <label for="Program Date">Program Location :</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Program Date Value">
                                            {{ $data['registration_data']['location_name'] ?? '' }} <br> {{ $data['registration_data']['location_sub_location_name'] ?? '' }}, {{ $data['registration_data']['location_address_line1'] ?? '' }},{{ $data['registration_data']['location_address_line2'] ?? '' }},{{ $data['registration_data']['location_address_city'] ?? '' }}, {{ $data['registration_data']['location_state'] ?? '' }}, {{ $data['registration_data']['location_country'] ?? '' }} </label>
                                    </div>

                                    <div class="col-md-5">
                                        <label for="Registration Number">Registration Number :</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Registration Number Value">
                                            {{ $data['registration_data']['registration_number'] ?? '' }}
                                        </label>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="Date of Registration">Date of Registration :</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Date of Registration Value">
                                            {{ $data['registration_data']['registration_time'] ?? '' }}
                                        </label>
                                    </div>

                                    <div class="col-md-5">
                                        <label for="Seats">Seats:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Seats Value">
                                            {{ $data['registration_data']['seats'] ?? '' }} Seat(s)
                                        </label>
                                    </div>

                                    <div class="col-md-5">
                                        <label for="Total Amount">Total Amount :</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Total Amount Value">
                                            {{ $data['registration_data']['symbol_left'] ?? '' }}
                                            {{ $data['registration_data']['total_fee_all_inclusive'] ?? '' }}
                                            {{ $data['registration_data']['symbol_right'] ?? '' }}
                                            (Inclusive {{ $data['registration_data']['tax_rate'] ?? '' }}% GST)
                                        </label>
                                    </div>

                                    <div class="col-md-5">
                                        <label for="Selection Status">Selection Status:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Selection Status Value">
                                            <a href="#" id="view-selection-status-modal-btn" class="{{ $data['registration_data']['selection_bootstrap_class'] ?? '' }}">
                                                {{ $data['registration_data']['selection_status'] ?? '' }}
                                                <i class="fa fa-info-circle" title="{{ $data['registration_data']['selection_status_description'] ?? '' }}" aria-hidden="true"></i>
                                            </a>
                                        </label>
                                    </div>

                                    <div class="col-md-5">
                                        <label for="Payment Status">Payment Status:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Payment Status Value">
                                            <a href="#" id="view-payment-status-modal-btn" class="{{ $data['registration_data']['payment_bootstrap_class'] ?? '' }}">
                                                {{ $data['registration_data']['payment_status'] ?? '' }}
                                                <i class="fa fa-info-circle" title="{{ $data['registration_data']['payment_status_description'] ?? '' }}" aria-hidden="true"></i>
                                            </a>
                                        </label>
                                    </div>

                                    @if( isset($data['registration_data']['payment_status_id']) && $data['registration_data']['payment_status_id'] !=   isset($data['registration_data']['payment_status_after_payment']) && $data['registration_data']['payment_status_after_payment'])
                                    <div class="col-md-5">
                                        <label for="Payment Last Date">Payment Last Date:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Payment Last Date Value">
                                            {{ $data['registration_data']['payment_last_date'] }} </label>
                                    </div>
                                    @endif


                                    @if(!empty($data['registration_data']['enable_gate_pass']) && $data['registration_data']['student_selection_status_id'] ==  $data['registration_data']['enable_gate_pass_on_selection_status_id'] )
                                    
                                    <div class="col-md-5">
                                        <label for="Download Gatepass">Download Gatepass:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Download Gatepass Value">
                                        <a href="#" class="{{ $data['registration_data']['payment_bootstrap_class'] }}" >Download Gatepass <i class="fa fa-info-circle" title="{{ $data['registration_data']['payment_status description'] }}" aria-hidden="true"></i></a>                                       
                                        </label>
                                    </div>

                       
                            @endif
                            



                            @if(!empty($data['registration_data']['enable_digital_certificate']) && $data['registration_data']['student_selection_status_id'] ==  $data['registration_data']['enable_digital_certificate_on_selection_status_id'] )
                                    
                                    <div class="col-md-5">
                                        <label for="City">Download Certificate</label>
                                    </div>
                                    <div class="col-md-7">
                                        <label for="Certificate">

                                        <a href="#" id="download-certificate" onclick="return false;"  class="{{ $data['registration_data']['payment_bootstrap_class'] }}" >Download Certificate <i class="fa fa-info-circle"  title="Download Your Certificate" aria-hidden="true"></i></a>                                       

                                         
                                        </label>
                                    </div>


                           
                                @endif




                                @if(!empty($data['registration_data']['payment_status_id']))
                                    <div class="col-md-5">
                                        <label for="Payment Last Date">Payment Id:</label>
                                    </div>
                                    <div class="col-md-7" style="font-family:Courier New,Courier,monospace">
                                        <label for="Payment Last Date Value">
                                            {{ $data['registration_data']['payment_id'] }} </label>
                                    </div>
                                    @endif




                                    @if(!empty($data['registration_data']['enable_address_field']) && $data['registration_data']['student_selection_status_id'] == isset($data['registration_data']['enable_address_field_on_selection_status_id']) && $data['registration_data']['enable_address_field_on_selection_status_id'] )
                                    <div class="col-md-5">
                                        <label for="City">Shipping Address</label>
                                    </div>
                                    <div class="col-md-7">
                                        <label for="Shipping">
                                            @if( !empty($data['registration_data']['shipping_address_firstname']) )
                                            <span>{{ ucfirst($data['registration_data']['shipping_address_firstname']) }}
                                            </span>
                                            @endif

                                            @if( !empty($data['registration_data']['shipping_address_lastname']) )
                                            <span>{{ ucfirst($data['registration_data']['shipping_address_lastname']) }}
                                            </span>
                                            @endif

                                            @if( !empty($data['registration_data']['shipping_address_firstname']) )
                                            <br>
                                            @endif

                                            @if( !empty($data['registration_data']['shipping_address_line_1']) )
                                            <span>{{ ucfirst($data['registration_data']['shipping_address_line_1']) }} </span> <br>
                                            @endif
                                            @if( !empty($data['registration_data']['shipping_address_line_2']) )
                                            <span>{{ ucfirst($data['registration_data']['shipping_address_line_2']) }} </span> <br>
                                            @endif
                                            <span>
                                                @if( !empty($data['registration_data']['shipping_address_city']) )
                                                {{ ucfirst($data['registration_data']['shipping_address_city']) }}
                                                @endif
                                                @if( !empty($data['registration_data']['shipping_address_state_id']) )
                                                {{ ucfirst($data['registration_data']['shipping_address_state_id']) }}
                                                @endif
                                                @if( !empty($data['registration_data']['shipping_address_country_id']) )
                                                {{ ucfirst($data['registration_data']['shipping_address_country_id']) }}
                                                @endif

                                            </span>
                                            @if( !empty($data['registration_data']['shipping_address_mobile']) )
                                            <span>{{ ucfirst($data['registration_data']['shipping_address_mobile_country_code_id']) }} {{ ucfirst($data['registration_data']['shipping_address_mobile']) }}</span> <br>
                                            @endif








                                            <button type="button" title="Update Address" name="shipping_address" id="shipping_address" class="btn btn-primary btn-sm mt-2"><i class="fa fa-edit"></i> Update Address </button>


                                        </label>
                                    </div>

                                    @endif




                                </div>

                            </div>

                        </div>


                        <div class="col-md-12" style="text-align:center; margin-top: 20px;">


                            <form action="{{ route('payment.store') }}" method="POST">
                                @csrf
                                @if( isset($data['registration_data']['payment_status_id']) && $data['registration_data']['payment_status_id'] != isset($data['registration_data']['payment_status_after_payment']) && $data['registration_data']['payment_status_after_payment'])
                                <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="rzp_live_D2lzE1os3o5bdY" data-amount="{{ $data['registration_data']['total_fee_all_inclusive'] * 100 }}" data-order_id="{{ $data['razorpay_order_id'] }}" data-buttontext="Pay Now" data-name="Technocon" data-description="{{ $data['form_setting']['registration_page_form_heading'] }} | registration number: {{ $data['registration_data']['registration_number'] }}" data-image="{{ asset($data['form_setting']['registration_meta_icon']) }}" data-notes.registration_number="{{ $data['registration_data']['registration_number'] }}" data-prefill.name="{{ $data['registration_data']['first_name']  }}" data-prefill.email="" data-prefill.contact="{{ $data['registration_data']['country_code'] }}{{ $data['registration_data']['mobile_no'] }}" data-theme.color="">
                                </script>
                                <input type="hidden" name="program_id" value="{{ $data['registration_data']['program_id'] }}">
                                <input type="hidden" name="registration_number" value="{{ $data['registration_data']['registration_number'] }}">
                                <script>
                                    var payNow = "{{ $data['registration_data']['enable_payment_link'] }}";
                                    if (payNow == 'N') {
                                        $('.razorpay-payment-button').addClass("disabled");
                                    }
                                    $('.razorpay-payment-button').addClass('btn btn-success btn-lg')
                                </script>

                                <button type="button" onclick="document.location.href='{{ $data['registration_data']['program_details_page_url'] }}'" class="btn btn-warning btn-lg" style="margin:10px;">Pay Later</button>
                                @endif

                                <button type="button" class="btn btn-info btn-lg" id="updateRegistrationBtn" style="margin:10px;">Edit Details</button>
                            </form>



                        </div>


                    </div>


                    <!-- #Shipping Address section  -->



                    <div class="modal fade modal-xl" style="min-height:500px;" id="shipping-address-modal" tabindex="-1" aria-labelledby="shipping-address-modalLabel" aria-hidden="true">
                        <div class="modal-dialog">


                            <div class="loader">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="shipping-address-modalLabel">Certificate Shipping Address</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body  shpping-address-body">

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="updateShippingAddress()" id="btn-update-address">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- #Shipping Address section end -->



                    <!-- #Edit Registration section  -->



                    <div class="modal fade modal-xl" style="min-height:500px;" id="update-registration-modal" tabindex="-1" aria-labelledby="update-registration-modalLabel" aria-hidden="true">
                        <div class="modal-dialog">

                            <div class="loader">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>


                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="update-registration-modalLabel">Update Registration</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body  edit-registration-body">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="updateRegistration()" id="btn-update-address">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- #Edit Registration section end -->





                </div>
            </div>

        </section><!-- #registration section end -->






        <!-- #Selection status section  -->



        <div class="modal fade modal-xl" style="min-height:500px;" id="view-selection-status-modal" tabindex="-1" aria-labelledby="view-selection-status-modalLabel" aria-hidden="true">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="view-selection-status-modalLabel">Selection Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $data['registration_data']['selection_status_description'] ?? '' }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>




        <!-- # Selection status section end -->

        <!-- #Payment status section  -->



        <div class="modal fade modal-xl" style="min-height:500px;" id="view-payment-status-modal" tabindex="-1" aria-labelledby="view-payment-status-modalLabel" aria-hidden="true">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="view-payment-status-modalLabel">Payment Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $data['registration_data']['payment_status description'] ?? '' }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>




        <!-- # Payment status section end -->


        <!-- #Certification section  -->



        <div class="modal fade modal-xl" style="min-height:500px;" id="view-certificate-modal" tabindex="-1" aria-labelledby="view-certificate-modalLabel" aria-hidden="true">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="view-certificate-modalLabel">Certificate Sample</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset($data['registration_data']['sample_certificate_url'] ?? '' ) }}" alt="sample certificate">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>




        <!-- # Certification section end -->


        <div class="span12" style="margin-left: 0px;">
            <div style="margin-bottom: 5px;margin-left: 5px; padding:30px;">
                <p style="text-align:center; text-decoration:underline;"><b>Status Code and their Meanings:</b></p>
                @if ($data['students_selection_status'])
                @foreach ($data['students_selection_status'] as $key => $status)
                <p><b>
                        {{ $status->selection_status ?? '' }} </b> : <i>
                        {{ $status->selection_status_description ?? '' }} </i></p>
                @endforeach
                @endif

            </div>
        </div>

        <!--===================  End Printable Area ==================================-->

    </div>




    @if( isset( $data['registration_success_event_url_parameter']) && $data['registration_success_event_url_parameter'] == isset($data['form_setting']['registration_success_event_url_parameter_value']) && $data['form_setting']['registration_success_event_url_parameter_value'] && isset($data['form_setting']['registration_success_event_script_position']) && $data['form_setting']['registration_success_event_script_position'] == 'before_body_close' )
    {!! isset($data['form_setting']['registration_success_event_script']) ? $data['form_setting']['registration_success_event_script'] : '' !!}
    @endif

    @include('frontend.help_text')
</body>

<script>
    $(document).ready(function() {

        $('#updateRegistrationBtn').on('click', function(e) {
            e.preventDefault();
            const registration_id = "{{ $data['registration_data']['registration_id'] ?? '' }}";
            try {
                $.ajax({
                    url: `/event/edit-registration`,
                    data: {
                        registration_id: registration_id,
                        seo_handle: "{{ $data['form_setting']['seo_url'] ?? '' }}"
                    },
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                    success: function(response) {
                        $('.edit-registration-body').html(response);
                        $('#update-registration-modal').modal('show');


                    }

                });
            } catch (error) {
                console.error('Error:', error);
            }
        });


        $('#shipping_address').on('click', function(e) {
            e.preventDefault();
            const registration_id = "{{ $data['registration_data']['registration_id'] ?? '' }}";
            try {
                $.ajax({
                    url: `/event/shipping-address-view`,
                    data: {
                        registration_id: registration_id
                    },
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('.shpping-address-body').html(response);
                        $('#shipping-address-modal').modal('show');

                        $('#mobile_country_code_id').select2();
                        $('#country_id').select2();
                        $('#state_id').select2();

                    }

                });
            } catch (error) {
                console.error('Error:', error);
            }
        });







    });

    $('#view-certificate-modal-btn').on('click', function(e) {
        e.preventDefault();
        $('#view-certificate-modal').modal('show');

    });

    $('#view-selection-status-modal-btn').on('click', function(e) {
        e.preventDefault();
        $('#view-selection-status-modal').modal('show');

    });

    $('#view-payment-status-modal-btn').on('click', function(e) {
        e.preventDefault();
        $('#view-payment-status-modal').modal('show');

    });
</script>


@endsection