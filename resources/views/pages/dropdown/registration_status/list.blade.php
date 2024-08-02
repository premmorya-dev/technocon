<x-default-layout>

    @section('title')
    Event
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('user.list') }}
    @endsection


    <div class="d-flex justify-content-start mb-5">
          @include('pages/dropdown/registration_status/actions/add_action')
    </div>


    <div class="table-responsive">

        <table class="table">
            <thead>
                <tr class="fw-bold fs-6 text-gray-800">
                    <th style=" width: 10px; "> <input class="form-check-input" name="selected" type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" value="" id="flexCheckDefault"></th>
                    <th>@sortablelink('registration_status','Registration Status')</th>
                    <th>@sortablelink('bootstrap_class','Bootstrap Class')</th>
                    <th>@sortablelink('registration_status_backend','Registration Status Backend')</th>
                    <th>@sortablelink('registration_status_description','Registration Status description')</th>
                    <th>@sortablelink('added_datetime','Added Date)</th>
                   
                    <th>Action</th>
                </tr>

                <tr class="fw-bold fs-6 text-gray-800">
                    <th style=" width: 10px; "></th>

                    <form id="filterForm" class="mb-4">
                        
                       <th><input type="text" style="width:100px;" name="filter_registration_status" value="{{ request('filter_registration_status') }}" class="form-control filter-input" id="filter_registration_status"></th>

                        <th><input type="text" style="width:100px;" name="filter_bootstrap_class" value="{{ request('filter_bootstrap_class') }}" class="form-control" id="filter_bootstrap_class"></th>

                        <th><input type="text" style="width:100px;" name="filter_registration_status_backend" value="{{ request('filter_registration_status_backend') }}" class="form-control" id="filter_registration_status_backend"></th>

                        <th><input type="text" style="width:100px;" name="registration_status_description" value="{{ request('registration_status_description') }}" class="form-control" id="registration_status_description"></th>

                        <th></th>

                        <th></th>


                        <th> @include('pages/dropdown/registration_status/actions/filter_action') </th>


                    </form>


                </tr>
            </thead>
            <tbody>

                @foreach ($registration_statuses as $registration_status)

                <tr>
                    <th style="width: 10px; "> <input class="form-check-input selected" name="selected[]" type="checkbox" value="{{ $sd_selection->registration_status_status_id }}" id="flexCheckDefault"></th>
                    <td>{{ $registration_status->registration_status }}</td>
                    <td>{{ $registration_status->bootstrap_class }}</td>
                    <td>{{ $registration_status->registration_status_backend }}</td>
                   
                    <td style="max-width:100px;">{{ $registration_status->registration_status_description }}</td>
                    <td>{{ $registration_status->added_datetime }}</td>
                   
                    <td>
                      @include('pages/dropdown/registration_status/actions/edit_action')
                    </td>

                </tr>

                @endforeach

            </tbody>
        </table>


        <!-- Pagination Links -->
        <div class="d-flex justify-content-left mt-5">
        {!! $registration_statuses->appends(\Request::except('page'))->render() !!}

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


    <script>
        $(document).ready(function() {

            $('#filter_btn').on('click', function(e) {
                e.preventDefault();
                var formData = $('#filterForm').serialize();
                location = "{{ route('registration_status.list') }}?" + formData;
            });

        });
    </script>


    <script>
        $(document).ready(function() {


            $('.single-delete').on('click', function(e) {
                e.preventDefault();
                const registration_status_id = $(this).attr('sd-selection-id');
                $('#delete-modal').modal('show');

                $('#confirm-delete').on('click', function(e) {
                    $('#delete-modal').modal('hide');
                    try {
                        $.ajax({
                            url: `/RegistrationStatus/destroy`,
                            data: {
                                registration_status_id: registration_status_id
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



            $('.multiple-delete').on('click', function(e) {

                e.preventDefault();

                var registration_status_ids = [];
                $('.selected:checked').each(function() {
                    registration_status_ids.push(this.value);
                });


                $('#delete-modal').modal('show');

                $('#confirm-delete').on('click', function(e) {
                    $('#delete-modal').modal('hide');
                    try {
                        $.ajax({
                            url: `/RegistrationStatus/destroy`,
                            data: {
                                registration_status_ids: registration_status_ids
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