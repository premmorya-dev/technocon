<x-default-layout>

    @section('title')
    User
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('user.edit') }}
    @endsection
 


    <form action="{{ route('user.update',['user_id'=>$user->user_id]) }}" class="row g-3" method="post" novalidate enctype="multipart/form-data" autocomplete="on" novalidate name="add-user">
        @csrf
        @method('put')
        <div class="col-md-12">
            <label for="first_name" class="form-label">First Name</label>
            <input type="first_name"  name="first_name" value="{{ old('first_name',$user->first_name) }}" class="form-control" id="first_name">
            @error('first_name')
                <div class="alert alert-danger">{{ $errors->first('first_name') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="last_name" name="last_name" value="{{ old('last_name',$user->last_name) }}" class="form-control" id="last_name">
            @error('last_name')
                <div class="alert alert-danger">{{ $errors->first('last_name') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="user_name" class="form-label">User Name</label>
            <input type="user_name" name="user_name" value="{{ old('last_name',$user->last_name) }}" class="form-control" id="user_name">
            @error('user_name')
                <div class="alert alert-danger">{{ $errors->first('user_name') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email',$user->email) }}" class="form-control" id="email">
            @error('email')
                <div class="alert alert-danger">{{ $errors->first('email') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="user_type" class="form-label">User Type</label>
            <select id="user_type" value="{{ old('user_type',$user->user_type) }}" name="user_type" class="form-select">
                     <option value=""><-- Please Select --> </option>
                    <option value="user"  {{ old('user_type',$user->user_type) == 'user' ? 'selected' : '' }} >User</option>
                    <option  value="admin"  {{ old('user_type',$user->user_type) == 'admin' ? 'selected' : '' }}>Admin</option>                

            </select>

            @error('user_type')
                        <div class="alert alert-danger">{{ $errors->first('user_type') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="status" class="form-label">Status</label>
            <select id="status" value="{{ old('status',$user->status) }}" name="status" class="form-select">
                     <option value=""><-- Please Select --> </option>
                    <option value="active"  {{ old('status',$user->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="suspend"  {{ old('status',$user->status) == 'suspend' ? 'selected' : '' }}>Suspend</option>
            </select>
                  @error('status')  
                        <div class="alert alert-danger">{{ $errors->first('status') }}</div>
                    @enderror
        </div>



        <div class="col-md-12">
            <label for="password" class="form-label">Password</label>
            <input type="password"  name="password"   class="form-control" id="password">
                   @error('password')
                        <div class="alert alert-danger">{{ $errors->first('password') }}</div>
                    @enderror
        </div>

        <div class="col-md-12">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password_confirmation"  name="password_confirmation"   class="form-control" id="password_confirmation">
                   @error('password_confirmation')
                        <div class="alert alert-danger">{{ $errors->first('password_confirmation') }}</div>
                    @enderror
        </div>


        <div class="col-md-3">
            <label for="country_code" class="form-label">Country Code</label>
            <select id="country_code" value="{{ old('country_code',$user->country_code) }}" name="country_code" class="form-select">
                     <option value=""><-- Select Country Code --> </option>
                     @foreach ($countries as $country)
                <option value="{{ $country->country_code }}" {{ old('country_code',$user->country_code) == $country->country_code ? 'selected' : '' }}>+{{ $country->country_code }} ( {{ $country->country_name }} )</option>
                @endforeach
                   
                  
            </select>
            @error('country_code')
                        <div class="alert alert-danger">{{ $errors->first('country_code') }}</div>
            @enderror
        </div>

        <div class="col-md-9">
            <label for="mobile_number" class="form-label">Mobile Number</label>
            <input type="mobile_number"   name="mobile_number" value="{{ old('mobile_number',$user->mobile_number) }}" class="form-control" id="mobile_number">
            @error('mobile_number')
                        <div class="alert alert-danger">{{ $errors->first('mobile_number') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="image" class="form-label">Profile Image</label>
            <input type="file" class="form-control col-md-8" name="image" id="image" onchange="previewImage(event)">
            @error('image')
            <div class="alert alert-danger">{{ $errors->first('image') }}</div>
            @enderror
        </div>



      
         <div class="col-md-12">
          <img id="preview" src="{{ $user->profile_photo_path ? asset('/uploads/user_profile_image/' . $user->profile_photo_path) : asset('no-image.png') }}" alt="Image Preview" width="150" style="max-width: 300px;">
        </div>

        <div class="col-md-6 mt-5">
            <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk"></i>Update</button>
        </div>


    </form>
</x-default-layout>