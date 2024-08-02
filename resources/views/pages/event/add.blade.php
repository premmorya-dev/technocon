<x-default-layout>

    @section('title')
    Add User
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('user.add') }}
    @endsection



    <form action="{{ route('event.store') }}" class="row g-3" method="post" novalidate enctype="multipart/form-data" autocomplete="on" novalidate name="add-user">
        @csrf
        @method('post')
        <div class="col-md-12">
            <label for="event_code" class="form-label">Event Code</label>
            <input type="event_code" name="event_code" value="{{ old('event_code') }}" class="form-control" id="event_code">
            @error('event_code')
            <div class="alert alert-danger">{{ $errors->first('event_code') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="event_backend_name" class="form-label">Event Backend Name</label>
            <input type="event_backend_name" name="event_backend_name" value="{{ old('event_backend_name') }}" class="form-control" id="event_backend_name">
            @error('event_backend_name')
            <div class="alert alert-danger">{{ $errors->first('event_backend_name') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="event_presenting_partner_1" class="form-label">Event Presenting Partner 1</label>
            <input type="text" name="event_presenting_partner_1" value="{{ old('event_presenting_partner_1') }}" class="form-control" id="event_presenting_partner_1">
            @error('event_presenting_partner_1')
            <div class="alert alert-danger">{{ $errors->first('event_presenting_partner_1') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="event_presenting_partner_2_association_slug" class="form-label">Event Presenting Partner 2 Association Slug</label>
            <input type="text" name="event_presenting_partner_2_association_slug" value="{{ old('event_presenting_partner_2_association_slug') }}" class="form-control" id="event_presenting_partner_2_association_slug">
            @error('event_presenting_partner_2_association_slug')
            <div class="alert alert-danger">{{ $errors->first('event_presenting_partner_2_association_slug') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="event_presenting_partner_2" class="form-label">Event Presenting Partner 2</label>
            <input type="text" name="event_presenting_partner_2" value="{{ old('event_presenting_partner_2') }}" class="form-control" id="event_presenting_partner_2">
            @error('event_presenting_partner_2')
            <div class="alert alert-danger">{{ $errors->first('event_presenting_partner_2') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="event_presenting_partner_1_association_slug" class="form-label">Event Presenting Partner 1 Association Slug</label>
            <input type="text" name="event_presenting_partner_1_association_slug" value="{{ old('event_presenting_partner_1_association_slug') }}" class="form-control" id="event_presenting_partner_1_association_slug">
            @error('event_presenting_partner_1_association_slug')
            <div class="alert alert-danger">{{ $errors->first('event_presenting_partner_1_association_slug') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="event_name_prefix" class="form-label">Event Name Prefix</label>
            <input type="text" name="event_name_prefix" value="{{ old('event_name_prefix') }}" class="form-control" id="event_name_prefix">
            @error('event_name_prefix')
            <div class="alert alert-danger">{{ $errors->first('event_name_prefix') }}</div>
            @enderror
        </div>


       
        <div class="col-md-12">
            <label for="event_name" class="form-label">Event Name</label>
            <input type="text" name="event_name" value="{{ old('event_name') }}" class="form-control" id="event_name">
            @error('event_name')
            <div class="alert alert-danger">{{ $errors->first('event_name') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="event_name_prefix" class="form-label">Event Name Prefix</label>
            <input type="text" name="event_name_prefix" value="{{ old('event_name_prefix') }}" class="form-control" id="event_name_prefix">
            @error('event_name_prefix')
            <div class="alert alert-danger">{{ $errors->first('event_name_prefix') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="event_name_suffix" class="form-label">Event Name Suffix</label>
            <input type="text" name="event_name_suffix" value="{{ old('event_name_suffix') }}" class="form-control" id="event_name_suffix">
            @error('event_name_suffix')
            <div class="alert alert-danger">{{ $errors->first('event_name_suffix') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="event_partner_1_association_slug" class="form-label">Event Partner 1 Association Slug</label>
            <input type="text" name="event_partner_1_association_slug" value="{{ old('event_partner_1_association_slug') }}" class="form-control" id="event_partner_1_association_slug">
            @error('event_partner_1_association_slug')
            <div class="alert alert-danger">{{ $errors->first('event_partner_1_association_slug') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="event_partner_1" class="form-label">Event Partner 1</label>
            <input type="text" name="event_partner_1" value="{{ old('event_partner_1') }}" class="form-control" id="event_partner_1">
            @error('event_partner_1')
            <div class="alert alert-danger">{{ $errors->first('event_partner_1') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="event_partner_1_suffix" class="form-label">Event Partner 1 Suffix</label>
            <input type="text" name="event_partner_1_suffix" value="{{ old('event_partner_1_suffix') }}" class="form-control" id="event_partner_1_suffix">
            @error('event_partner_1_suffix')
            <div class="alert alert-danger">{{ $errors->first('event_partner_1_suffix') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="event_partner_2_association_slug" class="form-label">Event Partner 2 Association Slug</label>
            <input type="text" name="event_partner_2_association_slug" value="{{ old('event_partner_2_association_slug') }}" class="form-control" id="event_partner_2_association_slug">
            @error('event_partner_2_association_slug')
            <div class="alert alert-danger">{{ $errors->first('event_partner_2_association_slug') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="event_partner_2" class="form-label">Event Partner 2</label>
            <input type="text" name="event_partner_2" value="{{ old('event_partner_2') }}" class="form-control" id="event_partner_2">
            @error('event_partner_2')
            <div class="alert alert-danger">{{ $errors->first('event_partner_2') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="event_partner_2_suffix" class="form-label">Event Partner 2 Suffix</label>
            <input type="text" name="event_partner_2_suffix" value="{{ old('event_partner_2_suffix') }}" class="form-control" id="event_partner_2_suffix">
            @error('event_partner_2_suffix')
            <div class="alert alert-danger">{{ $errors->first('event_partner_2_suffix') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="event_partner_3_association_slug" class="form-label">Event Partner 3 Association Slug</label>
            <input type="text" name="event_partner_3_association_slug" value="{{ old('event_partner_3_association_slug') }}" class="form-control" id="event_partner_3_association_slug">
            @error('event_partner_3_association_slug')
            <div class="alert alert-danger">{{ $errors->first('event_partner_3_association_slug') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="event_partner_3" class="form-label">Event Partner 3</label>
            <input type="text" name="event_partner_3" value="{{ old('event_partner_3') }}" class="form-control" id="event_partner_3">
            @error('event_partner_3')
            <div class="alert alert-danger">{{ $errors->first('event_partner_3') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="event_partner_3_suffix" class="form-label">Event Partner 3 Suffix</label>
            <input type="text" name="event_partner_3_suffix" value="{{ old('event_partner_3_suffix') }}" class="form-control" id="event_partner_3_suffix">
            @error('event_partner_3_suffix')
            <div class="alert alert-danger">{{ $errors->first('event_partner_3_suffix') }}</div>
            @enderror
        </div>



        <div class="col-md-6">
            <label for="event_from_date" class="form-label">Event From Date</label>
            <input type="date" name="event_from_date" value="{{ old('event_from_date') }}" class="form-control" id="event_from_date">
            @error('event_from_date')
            <div class="alert alert-danger">{{ $errors->first('event_from_date') }}</div>
            @enderror
        </div>


        <div class="col-md-6">
            <label for="event_end_date" class="form-label">Event End Date</label>
            <input type="date" name="event_end_date" value="{{ old('event_end_date') }}" class="form-control" id="event_end_date">
            @error('event_end_date')
            <div class="alert alert-danger">{{ $errors->first('event_end_date') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="event_url" class="form-label">Event Url</label>
            <input type="text" name="event_url" value="{{ old('event_url') }}" class="form-control" id="event_url">
            @error('event_url')
            <div class="alert alert-danger">{{ $errors->first('event_url') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="registration_page_banner_url" class="form-label">Registration Page Banner Url</label>
            <input type="text" name="registration_page_banner_url" value="{{ old('registration_page_banner_url') }}" class="form-control" id="registration_page_banner_url">
            @error('registration_page_banner_url')
            <div class="alert alert-danger">{{ $errors->first('registration_page_banner_url') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="registration_page_header_main_text" class="form-label">Registration Page Header Main Text</label>
            <input type="text" name="registration_page_header_main_text" value="{{ old('registration_page_header_main_text') }}" class="form-control" id="registration_page_header_main_text">
            @error('registration_page_header_main_text')
            <div class="alert alert-danger">{{ $errors->first('registration_page_header_main_text') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="registration_page_header_important_notes" class="form-label">Registration Page Header Important Notes</label>
            <input type="text" name="registration_page_header_important_notes" value="{{ old('registration_page_header_important_notes') }}" class="form-control" id="registration_page_header_important_notes">
            @error('registration_page_header_important_notes')
            <div class="alert alert-danger">{{ $errors->first('registration_page_header_important_notes') }}</div>
            @enderror
        </div>



        <div class="col-md-12">
            <label for="whatsapp_notification_banner_image_dir_url" class="form-label">Whatsapp Notification Banner Image Dir Url</label>
            <input type="text" name="whatsapp_notification_banner_image_dir_url" value="{{ old('whatsapp_notification_banner_image_dir_url') }}" class="form-control" id="whatsapp_notification_banner_image_dir_url">
            @error('whatsapp_notification_banner_image_dir_url')
            <div class="alert alert-danger">{{ $errors->first('whatsapp_notification_banner_image_dir_url') }}</div>
            @enderror
        </div>




        <div class="col-md-12">
            <label for="whatsapp_notification_banner_image" class="form-label">Whatsapp Notification Banner Image</label>
            <input type="text" name="whatsapp_notification_banner_image" value="{{ old('whatsapp_notification_banner_image') }}" class="form-control" id="whatsapp_notification_banner_image">
            @error('whatsapp_notification_banner_image')
            <div class="alert alert-danger">{{ $errors->first('whatsapp_notification_banner_image') }}</div>
            @enderror
        </div>




        



        <div class="col-md-6 mt-5">
            <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk"></i>Add</button>
        </div>


    </form>

  


</x-default-layout>