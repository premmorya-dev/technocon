<x-default-layout>

    @section('title')
    Program
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('program.list') }}
    @endsection


    <div class="d-flex justify-content-start mb-5">
          @include('pages/program/actions/add_action')
    </div>


    <div class="table-responsive">

        <table class="table table-striped">
            <thead>
                <tr class="fw-bold fs-6 text-gray-800">
                    <th style=" width: 10px; "> <input class="form-check-input" name="selected" type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" value="" id="flexCheckDefault"></th>
                    <th>@sortablelink('program_id','Id')</th>                 
                    <th>@sortablelink('program_name','Name')</th>
                    <th>@sortablelink('status','Status')</th>
                    <th>@sortablelink('program_type','Program Type')</th>
                    <th>@sortablelink('start_dates','Start Date')</th>
                    <th>@sortablelink('end_dates','End Date')</th>
                    <th>Duration</th>
                   
                  
                   
                    <th>Action</th>
                </tr>

                <tr class="fw-bold fs-6 text-gray-800">
                    <th style=" width: 10px; "></th>

                    <form id="filterForm" class="mb-4">
                        
                       <th><input type="text" style="width:100px;" name="filter_program_id" value="{{ request('filter_program_id') }}" class="form-control  filter-input" id="filter_program_id"></th>

                       <th><input type="text" style="width:100px;" name="filter_program_name" value="{{ request('filter_program_name') }}" class="form-control  filter-input" id="filter_program_name"></th>

                       
                       <th>
                            <select style="width:100px;" name="filter_status" class="form-select">
                                <option value="">Status</option>
                                <option value="1" {{ request('filter_status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('filter_status') == 'suspend' ? 'selected' : '' }}>Suspend</option>
                            </select>

                        </th>

                       <th><input type="text" style="width:100px;" name="filter_program_type" value="{{ request('filter_program_type') }}" class="form-control  filter-input" id="filter_program_type"></th>

                       <th></th>
                       <th></th>
                       <th></th>





                        <th> @include('pages/program/actions/filter_action') </th>


                    </form>


                </tr>
            </thead>
            <tbody>
            
                @foreach ($programs as $program )

                <tr>
                    <th style="width: 10px; "> <input class="form-check-input selected" name="selected[]" type="checkbox" value="{{ $program->program_id }}" id="flexCheckDefault"></th>
                    <td>{{ $program->program_id }}</td>
                    <td>{{ $program->program_name }}
                        <div><span class="workshop-location-list" >{{ $program->workshop_location }}</span></div>
                    </td>

                    <td> <span class="badge <?php if ($program->status == '1') {
                                                echo 'badge-success';
                                            } else {
                                                echo 'badge-danger';
                                            } ?>"> <?php if ($program->status == '1') {
                                                echo 'Active';
                                            } else {
                                                echo 'Deactive';
                                            } ?></span> </td>


                    <td>{{ $program->event_program_title }}</td>
                    <td>{{ $program->start_dates }}</td>
                    <td>{{ $program->end_dates }}</td>
                    <td style="min-width: 100px; ">{{ $program->program_duration }} {{ $program->program_duration_time_unit }}</td>
                   
                 
                  
                   
                    <td>
                      @include('pages/program/actions/edit_action')
                    </td>

                </tr>

                @endforeach

            </tbody>
        </table>


        <!-- Pagination Links -->
        <div class="d-flex justify-content-left mt-5">
        {!! $programs->appends(\Request::except('page'))->render() !!}

        </div>

    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
                </div>
            </div>
        </div>
    </div>

     <!-- View Modal -->
     <div class="modal fade" id="view-modal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="viewModalLabel">View Workshop</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="view-modal-body py-5 px-5">
                   
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {

            $('#filter_btn').on('click', function(e) {
                e.preventDefault();
                var formData = $('#filterForm').serialize();
                location = "{{ route('program.list') }}?" + formData;
            });

        });
    </script>


    <script>
        $(document).ready(function() {


            $('.single-delete').on('click', function(e) {
                e.preventDefault();
                const program_id = $(this).attr('program-id');
                $('#delete-modal').modal('show');

                $('#confirm-delete').on('click', function(e) {
                    $('#delete-modal').modal('hide');
                    try {
                        $.ajax({
                            url: `/program/destroy`,
                            data: {
                                program_id: program_id
                            },
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                location.reload();
                            }

                        });
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });


            });

            //view modal


            $('.view-button').on('click', function(e) {
                e.preventDefault();
                // $('#view-modal').modal('show');   
                const program_id = $(this).attr('program-id');
                try {
                        $.ajax({
                            url: `/program/view`,
                            data: {
                                program_id: program_id
                            },
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                console.log(response)
                                $('.view-modal-body').html(response);
                                $('#view-modal').modal('show'); 
                            }

                        });
                    } catch (error) {
                        console.error('Error:', error);
                    }


            });



            $('.multiple-delete').on('click', function(e) {

                e.preventDefault();

                var program_ids = [];
                $('.selected:checked').each(function() {
                    program_ids.push(this.value);
                });


                $('#delete-modal').modal('show');

                $('#confirm-delete').on('click', function(e) {
                    $('#delete-modal').modal('hide');
                    try {
                        $.ajax({
                            url: `/program/destroy`,
                            data: {
                                program_ids: program_ids
                            },
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                 location.reload();
                            }

                        });
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });






            });



        });
    </script>
</x-default-layout>