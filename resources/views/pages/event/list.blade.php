<x-default-layout>

    @section('title')
    Event
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('user.list') }}
    @endsection


    <div class="d-flex justify-content-start mb-5">
          @include('pages/event/actions/add_action')
    </div>


    <div class="table-responsive">

        <table class="table table-striped">
            <thead>
                <tr class="fw-bold fs-6 text-gray-800">
                    <th style=" width: 10px; "> <input class="form-check-input" name="selected" type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" value="" id="flexCheckDefault"></th>
                    <th>@sortablelink('event_id','Event Id')</th>
                    <th>@sortablelink('event_code','Code')</th>
                    <th>@sortablelink('event_prefix','Event Prefix')</th>
                    <th>@sortablelink('event_name','Event Name')</th>
                    <th>@sortablelink('event_from_date','Start Date')</th>
                    <th>@sortablelink('event_end_date','End Date')</th>
                   
                    <th>Action</th>
                </tr>

                <tr class="fw-bold fs-6 text-gray-800">
                    <th style=" width: 10px; "></th>

                    <form id="filterForm" class="mb-4">
                        
                       <th><input type="text" style="width:100px;" name="filter_event_id" value="{{ request('filter_event_id') }}" class="form-control filter-input" id="filter_event_id"></th>

                        <th><input type="text" style="width:100px;" name="filter_event_code" value="{{ request('filter_event_code') }}" class="form-control" id="filter_event_code"></th>

                        <th><input type="text" style="width:100px;" name="filter_event_prefix" value="{{ request('filter_event_prefix') }}" class="form-control" id="filter_event_prefix"></th>

                        <th><input type="text" style="width:100px;" name="filter_event_name" value="{{ request('filter_prefix') }}" class="form-control" id="filter_prefix"></th>

                        <th></th>
                        <th></th>

                        <th> @include('pages/event/actions/filter_action') </th>


                    </form>


                </tr>
            </thead>
            <tbody>

                @foreach ($events as $event)

                <tr>
                    <th style="width: 10px; "> <input class="form-check-input selected" name="selected[]" type="checkbox" value="{{ $event->event_id }}" id="flexCheckDefault"></th>
                    <td>{{ $event->event_id }}</td>
                    <td>{{ $event->event_code }}</td>
                    <td>{{ $event->event_name_prefix }}</td>
                   
                    <td>{{ $event->event_name }}</td>
                    <td>{{ $event->event_from_date }}</td>
                    <td>{{ $event->event_end_date }}</td>
                   
                    <td>
                      @include('pages/event/actions/edit_action')
                    </td>

                </tr>

                @endforeach

            </tbody>
        </table>


        <!-- Pagination Links -->
        <div class="d-flex justify-content-left mt-5">
        {!! $events->appends(\Request::except('page'))->render() !!}

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

            $('#filter_btn').on('click', function(event) {
                event.preventDefault();
                var formData = $('#filterForm').serialize();
                location = "{{ route('event.list') }}?" + formData;
            });

        });
    </script>


    <script>
        $(document).ready(function() {


            $('.single-delete').on('click', function(event) {
                event.preventDefault();
                const event_id = $(this).attr('event-id');
                $('#delete-modal').modal('show');

                $('#confirm-delete').on('click', function(event) {
                    $('#delete-modal').modal('hide');
                    try {
                        $.ajax({
                            url: `/event/destroy`,
                            data: {
                                event_id: event_id
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



            $('.multiple-delete').on('click', function(event) {

                event.preventDefault();

                var event_ids = [];
                $('.selected:checked').each(function() {
                    event_ids.push(this.value);
                });


                $('#delete-modal').modal('show');

                $('#confirm-delete').on('click', function(event) {
                    $('#delete-modal').modal('hide');
                    try {
                        $.ajax({
                            url: `/event/destroy`,
                            data: {
                                event_ids: event_ids
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