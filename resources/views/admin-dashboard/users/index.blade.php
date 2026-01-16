@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="mb-0">{{ t_db('general', 'users') }}</h3>
</div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ t_db('general', 'name') }}</th>
                            <th>{{ t_db('general', 'email') }}</th>
                            <th>{{ t_db('general', 'phone_number') }}</th>
                            <th>{{ t_db('general', 'status') }}</th>
                            <th>{{ t_db('general', 'created_at') }}</th>
                            <th>{{ t_db('general', 'actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_number }}</td>
                                <td>
                                    @if($user->isBanned())
                                        <span class="badge bg-danger" data-bs-toggle="tooltip" title="{{ t_db('general', 'banned_until') }}: {{ $user->banned_until->format('d.m.Y H:i') }}">
                                            {{ t_db('general', 'banned') }}
                                        </span>
                                    @else
                                        <span class="badge bg-success">{{ t_db('general', 'active') }}</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                <td class="d-flex align-items-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @if($user->isBanned())
                                                <a class="dropdown-item unban-user" href="javascript:void(0);" data-uuid="{{ $user->uuid }}">
                                                    <i class="bx bx-check-circle me-1"></i> {{ t_db('general', 'unban') }}
                                                </a>
                                            @else
                                                <a class="dropdown-item ban-user" href="javascript:void(0);" data-uuid="{{ $user->uuid }}">
                                                    <i class="bx bx-block me-1"></i> {{ t_db('general', 'ban') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('users.show', $user->uuid) }}" class="btn btn-icon item-edit" title="{{ t_db('general', 'show') }}">
                                        <i class="bx bx-show icon-sm"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user->uuid) }}" class="btn btn-icon item-edit" title="{{ t_db('general', 'edit') }}">
                                        <i class="bx bx-edit icon-sm"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->uuid) }}" method="POST" onsubmit="return confirm('{{ t_db('general', 'are_you_sure') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon item-trash text-danger" title="{{ t_db('general', 'delete') }}">
                                            <i class="bx bx-trash icon-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6">
                                    {{ $users->links('pagination::bootstrap-5') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Ban Modal --}}
    <div class="modal fade" id="banModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ t_db('general', 'ban_user') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="ban-user-uuid">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="ban-duration" class="form-label">{{ t_db('general', 'ban_duration') }}</label>
                            <select id="ban-duration" class="form-select">
                                <option value="1">{{ t_db('general', '1_day') }}</option>
                                <option value="7">{{ t_db('general', '1_week') }}</option>
                                <option value="30">{{ t_db('general', '1_month') }}</option>
                                <option value="365">{{ t_db('general', '1_year') }}</option>
                                <option value="permanent">{{ t_db('general', 'permanent') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ t_db('general', 'close') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm-ban">{{ t_db('general', 'ban') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
<script>
    $(document).ready(function() {
        // Show Ban Modal
        $(document).on('click', '.ban-user', function() {
            let uuid = $(this).data('uuid');
            $('#ban-user-uuid').val(uuid);
            $('#banModal').modal('show');
        });

        // Confirm Ban
        $('#confirm-ban').click(function() {
            let uuid = $('#ban-user-uuid').val();
            let duration = $('#ban-duration').val();

            $.ajax({
                url: '/users/' + uuid + '/ban',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    duration: duration
                },
                success: function(response) {
                    $('#banModal').modal('hide');
                    Swal.fire("{{ t_db('general', 'success') }}", response.message, "success")
                        .then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire("{{ t_db('general', 'error') }}", xhr.responseJSON.message, "error");
                }
            });
        });

        // Unban User
        $(document).on('click', '.unban-user', function() {
            let uuid = $(this).data('uuid');
            
            Swal.fire({
                title: "{{ t_db('general', 'are_you_sure') }}",
                text: "{{ t_db('general', 'confirm_unban') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ t_db('general', 'yes') }}",
                cancelButtonText: "{{ t_db('general', 'cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/users/' + uuid + '/unban',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire("{{ t_db('general', 'success') }}", response.message, "success")
                                .then(() => location.reload());
                        },
                        error: function(xhr) {
                            Swal.fire("{{ t_db('general', 'error') }}", xhr.responseJSON.message, "error");
                        }
                    });
                }
            });
        });
    });
</script>
@endsection