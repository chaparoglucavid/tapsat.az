@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ t_db('general', 'stores') }}</h5>
                <a href="{{ route('stores.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> {{ t_db('general', 'add_store') }}
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ t_db('general', 'store_name') }}</th>
                                <th>{{ t_db('general', 'user') }}</th>
                                <th>{{ t_db('general', 'category') }}</th>
                                <th>{{ t_db('general', 'status') }}</th>
                                <th>{{ t_db('general', 'created_at') }}</th>
                                <th>{{ t_db('general', 'actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                                <tr>
                                    <td>{{ $store->store_name }}</td>
                                    <td>{{ $store->user->name ?? '-' }}</td>
                                    <td>{{ $store->category->name ?? '-' }}</td>
                                    <td>
                                        @if($store->status == 'pending')
                                            <span class="badge bg-warning">{{ t_db('general', 'pending') }}</span>
                                        @elseif($store->status == 'confirmed')
                                            <span class="badge bg-success">{{ t_db('general', 'confirmed') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ t_db('general', 'rejected') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $store->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('stores.show', $store->uuid) }}" class="btn btn-icon item-show" title="{{ t_db('general', 'show') }}">
                                                <i class="icon-base bx bx-show icon-sm"></i>
                                            </a>
                                            <a href="{{ route('stores.edit', $store->uuid) }}" class="btn btn-icon item-edit"
                                                 title="{{t_db('general', 'edit')}}"><i class="icon-base bx bx-edit icon-sm"></i>
                                            </a>
                                            
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if($store->status != 'confirmed')
                                                        <a class="dropdown-item change-status" href="javascript:void(0);" data-uuid="{{ $store->uuid }}" data-status="confirmed">
                                                            <i class="bx bx-check me-1"></i> {{ t_db('general', 'confirm') }}
                                                        </a>
                                                    @endif
                                                    @if($store->status != 'rejected')
                                                        <a class="dropdown-item change-status-reject" href="javascript:void(0);" data-uuid="{{ $store->uuid }}">
                                                            <i class="bx bx-x me-1"></i> {{ t_db('general', 'reject') }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $stores->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ t_db('general', 'reject_store') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="reject-store-uuid">
                    <div class="row">
                        <div class="col mb-3">
                            <label for="rejection-reason" class="form-label">{{ t_db('general', 'reason') }}</label>
                            <textarea id="rejection-reason" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ t_db('general', 'close') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm-reject">{{ t_db('general', 'reject') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
<script>
    $(document).ready(function() {
        // Confirm Status
        $(document).on('click', '.change-status', function() {
            let uuid = $(this).data('uuid');
            let status = $(this).data('status');
            
            Swal.fire({
                title: "{{ t_db('general', 'are_you_sure') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "{{ t_db('general', 'yes') }}",
                cancelButtonText: "{{ t_db('general', 'cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    updateStatus(uuid, status);
                }
            });
        });

        // Reject Status
        $(document).on('click', '.change-status-reject', function() {
            let uuid = $(this).data('uuid');
            $('#reject-store-uuid').val(uuid);
            $('#rejection-reason').val('');
            $('#rejectModal').modal('show');
        });

        $('#confirm-reject').click(function() {
            let uuid = $('#reject-store-uuid').val();
            let reason = $('#rejection-reason').val();

            if (!reason) {
                Swal.fire("{{ t_db('general', 'error') }}", "{{ t_db('general', 'enter_reason') }}", "error");
                return;
            }

            updateStatus(uuid, 'rejected', reason);
            $('#rejectModal').modal('hide');
        });

        function updateStatus(uuid, status, reason = null) {
            $.ajax({
                url: '/stores/' + uuid + '/status',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status,
                    rejection_reason: reason
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
</script>
@endsection
