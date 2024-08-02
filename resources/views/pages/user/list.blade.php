<x-default-layout>

    @section('title')
    Users
    @endsection

    @section('breadcrumbs')
    {{ Breadcrumbs::render('user.list') }}
    @endsection


    <div class="d-flex justify-content-start mb-5">
          @include('pages/user/actions/add_action')
    </div>


    <div class="table-responsive">

        <table class="table table-striped">
            <thead>
                <tr class="fw-bold fs-6 text-gray-800">
                    <th style=" width: 10px; "> <input class="form-check-input" name="selected" type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" value="" id="flexCheckDefault"></th>
                    <th>@sortablelink('first_name','First Name')</th>
                    <th>@sortablelink('last_name','Last Name')</th>
                    <th>@sortablelink('email','Email')</th>
                    <th>@sortablelink('status','Status')</th>
                    <th>@sortablelink('user_name','User Name')</th>
                    <th>@sortablelink('user_type','User Type')</th>
                    <th>@sortablelink('created_at','Create At')</th>
                    <th>@sortablelink('updated_at','Updated At')</th>
                    <th>Action</th>
                </tr>

                <tr class="fw-bold fs-6 text-gray-800">
                    <th style=" width: 10px; "></th>

                    <form id="filterForm" class="mb-4">
                        <th><input type="text" style="width:100px;" name="filter_first_name" value="{{ request('filter_first_name') }}" class="form-control filter-input" id="filter_first_name"></th>

                        <th><input type="text" style="width:100px;" name="filter_last_name" value="{{ request('filter_last_name') }}" class="form-control" id="filter_last_name"></th>

                        <th><input type="text" style="width:100px;" name="filter_email" value="{{ request('filter_email') }}" class="form-control" id="filter_email"></th>

                        <th>
                            <select style="width:100px;" name="filter_status" class="form-select">
                                <option value="">Status</option>
                                <option value="active" {{ request('filter_status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="suspend" {{ request('filter_status') == 'suspend' ? 'selected' : '' }}>Suspend</option>
                            </select>

                        </th>


                        <th><input type="text" style="width:100px;" name="filter_user_name" value="{{ request('filter_user_name') }}" class="form-control" id="filter_user_name"></th>

                        <th>
                            <select style="width:100px;" name="filter_user_type" class="form-select">
                                <option value="">Status</option>
                                <option value="user" {{ request('filter_user_type') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ request('filter_user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>

                        </th>



                        <th></th>
                        <th></th>
                        <th> @include('pages/user/actions/filter_action') </th>


                    </form>


                </tr>
            </thead>
            <tbody>

                @foreach ($users as $user)

                <tr>
                    <th style=" width: 10px; "> <input class="form-check-input selected" name="selected[]" type="checkbox" value="{{ $user->user_id }}" id="flexCheckDefault"></th>
                    <td>{{ $user->first_name }}</td>
                    <td>{{ $user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td> <span class="badge <?php if ($user->status == 'active') {
                                                echo 'badge-success';
                                            } else {
                                                echo 'badge-danger';
                                            } ?>">{{ $user->status }}</span> </td>
                    <td>{{ $user->user_name }}</td>
                    <td>{{ $user->user_type }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>{{ $user->updated_at }}</td>



                    <!-- <td>
                        <a href="#" class="btn btn-primary btn-sm"> <i class="fa-solid fa-eye"></i></a>
                        <a href="javascript:void(0);" user-id="{{ $user->user_id }}" class="btn btn-danger btn-sm single-delete"> <i class="fa-solid fa-trash"></i></a>
                    </td> -->

                    <td>
                      @include('pages/user/actions/edit_action')
                    </td>
                </tr>

                @endforeach

            </tbody>
        </table>


        <!-- Pagination Links -->
        <div class="d-flex justify-content-left mt-5">
            {!! $users->appends(\Request::except('page'))->render() !!}
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
                location = "{{ route('user.list') }}?" + formData;
            });

        });
    </script>


    <script>
        $(document).ready(function() {


            $('.single-delete').on('click', function(event) {
                event.preventDefault();
                const user_id = $(this).attr('user-id');
                $('#delete-modal').modal('show');

                $('#confirm-delete').on('click', function(event) {
                    $('#delete-modal').modal('hide');
                    try {
                        $.ajax({
                            url: `/user/destroy`,
                            data: {
                                user_id: user_id
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

                var user_ids = [];
                $('.selected:checked').each(function() {
                    user_ids.push(this.value);
                });


                $('#delete-modal').modal('show');

                $('#confirm-delete').on('click', function(event) {
                    $('#delete-modal').modal('hide');
                    try {
                        $.ajax({
                            url: `/user/destroy`,
                            data: {
                                user_ids: user_ids
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