<x-default-layout>

    @section('title')
    Add User
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('user.add') }}
    @endsection



    <form action="{{ route('student_selection.store') }}" class="row g-3" method="post" novalidate enctype="multipart/form-data" autocomplete="on" novalidate name="add-user">
        @csrf
        @method('post')
       
        <div class="col-md-12">
            <label for="selection_status" class="form-label">Selection Status</label>
            <input type="selection_status" name="selection_status" value="{{ old('selection_status') }}" class="form-control" id="selection_status">
            @error('selection_status')
            <div class="alert alert-danger">{{ $errors->first('selection_status') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="bootstrap_class" class="form-label">Bootstrap Class</label>
            <input type="bootstrap_class" name="bootstrap_class" value="{{ old('bootstrap_class') }}" class="form-control" id="bootstrap_class">
            @error('bootstrap_class')
            <div class="alert alert-danger">{{ $errors->first('bootstrap_class') }}</div>
            @enderror
        </div>


        <div class="col-md-12">
            <label for="selection_status_backend" class="form-label">Selection Status Backend</label>
            <input type="text" name="selection_status_backend" value="{{ old('selection_status_backend') }}" class="form-control" id="selection_status_backend">
            @error('selection_status_backend')
            <div class="alert alert-danger">{{ $errors->first('selection_status_backend') }}</div>
            @enderror
        </div>

        <div class="col-md-12">
            <label for="selection_status_description" class="form-label">Selection Status Description</label>
            <textarea name="selection_status_description" rows="4" class="form-control" id="selection_status_description" >{{ old('selection_status_description',$sd_selection->selection_status_description) }}</textarea>
            <div class="alert alert-danger">{{ $errors->first('selection_status_description') }}</div>
            @enderror
        </div>


    

        



        <div class="col-md-6 mt-5">
            <button type="submit" class="btn btn-primary"><i class="fa-regular fa-floppy-disk"></i>Add</button>
        </div>


    </form>

  


</x-default-layout>