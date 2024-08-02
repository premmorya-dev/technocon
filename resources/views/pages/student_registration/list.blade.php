<x-default-layout>

    @section('title')
    Student Registration
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('student_registration.list') }}
    @endsection


    <div class="d-flex justify-content-start mb-5">
          @include('pages/student_registration/actions/add_action')
    </div>

    <div class="table-responsive">

        <table class="table table-striped">
            <thead>
                <tr class="fw-bold fs-6 text-gray-800">
                    <th style=" width: 10px; "> <input class="form-check-input" name="selected" type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" value="" id="flexCheckDefault"></th>
                    <th>@sortablelink('registration_number','Reg No.')</th>                 
                    <th>@sortablelink('first_name','First Name')</th>
                    <th>@sortablelink('last_name','Last Name')</th>
                    <th>@sortablelink('registered_email','Registered Email')</th>
                    <th>Payment Status</th>                 
                  <th></th>
                   
                    <th>Action</th>
                </tr>

                <tr class="fw-bold fs-6 text-gray-800">
                    <th style=" width: 10px; "></th>

                    <form id="filterForm" class="mb-4">
                        
                       
                       <th><input type="text" style="width:100px;" name="filter_registration_number" value="{{ request('filter_registration_number') }}" class="form-control  filter-input" id="filter_registration_number"></th>

                       <th><input type="text" style="width:100px;" name="filter_first_name" value="{{ request('filter_first_name') }}" class="form-control  filter-input" id="filter_first_name"></th>

                       <th><input type="text" style="width:100px;" name="filter_last_name" value="{{ request('filter_last_name') }}" class="form-control  filter-input" id="filter_last_name"></th>

                       <th><input type="text" style="width:100px;" name="filter_registered_email" value="{{ request('filter_registered_email') }}" class="form-control  filter-input" id="filter_registered_email"></th>

                    <th>
                       <select style="width:100px;" name="filter_payment_status_id" class="form-select">
                                <option value="">Status</option>
                              @foreach ($data['payment_statuses'] as $payment_status )

                                <option value="{{ $payment_status->payment_status_id }}" {{ request('filter_payment_status_id') == $payment_status->payment_status ? 'selected' : '' }}>{{ $payment_status->payment_status }}</option>
                              
                                @endforeach
                            </select>
                        </th>

                         
                        <th></th>
                        <th> @include('pages/student_registration/actions/filter_action') </th>


                    </form>


                </tr>
            </thead>
            <tbody>
            
            @if ( $data['student_registrations'] )
                        @foreach ($data['student_registrations'] as $student_registration )

                            <tr>
                                <th style="width: 10px; "> <input class="form-check-input selected" name="selected[]" type="checkbox" value="{{ $student_registration->registration_id }}" id="flexCheckDefault"></th>
                                <td>{{ $student_registration->registration_number }}</td>
                                <td>{{ $student_registration->first_name }}</td>
                                <td>{{ $student_registration->last_name }}</td>
                                <td>{{ $student_registration->registered_email }}</td>

                                <td> <span class="badge <?php if ($student_registration->payment_status_id == '1') {
                                                echo 'badge-success';
                                            }else   if($student_registration->payment_status_id == '0' ){
                                                echo 'badge-danger';
                                            }else{
                                                echo 'badge-warning';
                                            } ?>">{{  $student_registration->payment_status }}</span> </td>

                                            
                                <td>
                            
                            
                                <td>
                                @include('pages/student_registration/actions/edit_action')
                                </td>

                            </tr>

                            @endforeach
        
            @endif
            
            @if(  empty($data['student_registrations']) )
               <tr><p>No Records</p></tr> 
            @endif

                        
            </tbody>
        </table>


        <!-- Pagination Links -->
        <div class="d-flex justify-content-left mt-5">
        {!! $data['student_registrations']->appends(\Request::except('page'))->render() !!}

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
                    <h4 class="modal-title" id="viewModalLabel">View Student Registration</h4>
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
                location = "{{ route('student_registration.list') }}?" + formData;
            });

        });
    </script>


    <script>
        $(document).ready(function() {


            $('.single-delete').on('click', function(e) {
                e.preventDefault();
                const student_registration_id = $(this).attr('student-registration-id');
                $('#delete-modal').modal('show');

                $('#confirm-delete').on('click', function(e) {
                    $('#delete-modal').modal('hide');
                    try {
                        $.ajax({
                            url: `/student-registration/destroy`,
                            data: {
                                student_registration_id: student_registration_id
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
                $('#view-modal').modal('show');   
                const student_registration_id = $(this).attr('student-registration-id');
                try {
                        $.ajax({
                            url: `/student-registration/view`,
                            data: {
                                student_registration_id: student_registration_id
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

                var student_registration_ids = [];
                $('.selected:checked').each(function() {
                    student_registration_ids.push(this.value);
                });


                $('#delete-modal').modal('show');

                $('#confirm-delete').on('click', function(e) {
                    $('#delete-modal').modal('hide');
                    try {
                        $.ajax({
                            url: `/student-registration/destroy`,
                            data: {
                                student_registration_ids: student_registration_ids
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