<x-default-layout>
<div class="container mt-5">
    @section('title')
    Users
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('user.list') }}
    @endsection

        <h1 class="mb-4">Settings</h1>

      
        <ul class="nav nav-tabs" id="settingsTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
            </li>
            <!-- Add more tabs as needed -->
        </ul>

        <form action="{{ route('settings.store') }}" method="POST">
            @csrf
            @method('post')
            <div class="tab-content mt-3" id="settingsTabContent">
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <div class="row mb-3">
                            <label for="pagination_limit" class="col-sm-4 col-form-label">Pagination Limit</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pagination_limit" name="pagination_limit" value="{{ old('pagination_limit',setting('pagination_limit') ) }}">
                            </div>
                            @error('pagination_limit')
                            <div class="alert alert-danger">{{ $errors->first('pagination_limit') }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <label for="smtp_id_for_forget_registration_number" class="col-sm-4 col-form-label">Smtp Id Setting For Forget Registration Number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="smtp_id_for_forget_registration_number" name="smtp_id_for_forget_registration_number" value="{{ old('smtp_id_for_forget_registration_number',setting('smtp_id_for_forget_registration_number') ) }}">
                            </div>
                            @error('smtp_id_for_forget_registration_number')
                            <div class="alert alert-danger">{{ $errors->first('smtp_id_for_forget_registration_number') }}</div>
                            @enderror
                        </div>


                        <div class="row mb-3">
                            <label for="template_id_for_forget_registration_number" class="col-sm-4 col-form-label">Template Id For Forget Registration Number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="template_id_for_forget_registration_number" name="template_id_for_forget_registration_number" value="{{ old('template_id_for_forget_registration_number',setting('template_id_for_forget_registration_number') ) }}">
                            </div>
                            @error('template_id_for_forget_registration_number')
                            <div class="alert alert-danger">{{ $errors->first('template_id_for_forget_registration_number') }}</div>
                            @enderror
                        </div>


                        <!-- <div class="row mb-3">
                            <label for="site_email" class="col-sm-2 col-form-label">Site Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="site_email" name="site_email" value="{{ old('site_email', $settings['site_email'] ?? '') }}">
                            </div>
                        </div> -->
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                       <!-- <div class="row mb-3">
                            <label for="site_name" class="col-sm-2 col-form-label">Test</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="site_email" class="col-sm-2 col-form-label">Test</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="site_email" name="site_email" value="{{ old('site_email', $settings['site_email'] ?? '') }}">
                            </div>
                        </div> -->
                </div>
                <!-- Add more tab content as needed -->
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk"></i>Save</button>
        </form>
    </div>

</x-default-layout>