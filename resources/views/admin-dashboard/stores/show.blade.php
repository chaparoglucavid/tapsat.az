@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        {{-- Store Details --}}
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $store->store_name }}</h5>
                    <a href="{{ route('stores.index') }}" class="btn btn-outline-danger">
                        <i class="bx bx-left-arrow-alt"></i> {{ t_db('general', 'back') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ t_db('general', 'category') }}:</strong>
                            <p>{{ $store->category->name ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ t_db('general', 'status') }}:</strong>
                            <p>
                                @if($store->status == 'pending')
                                    <span class="badge bg-warning">{{ t_db('general', 'pending') }}</span>
                                @elseif($store->status == 'confirmed')
                                    <span class="badge bg-success">{{ t_db('general', 'confirmed') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ t_db('general', 'rejected') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ t_db('general', 'phone_number') }}:</strong>
                            <p>{{ $store->phone_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ t_db('general', 'email') }}:</strong>
                            <p>{{ $store->email }}</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>{{ t_db('general', 'address') }}:</strong>
                        <p>{{ $store->address }}</p>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ t_db('general', 'working_days') }}:</strong>
                            <p>
                                @if(is_array($store->working_days))
                                    {{ implode(', ', $store->working_days) }}
                                @else
                                    {{ $store->working_days }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>{{ t_db('general', 'working_hours') }}:</strong>
                            <p>{{ $store->working_hours }}</p>
                        </div>
                    </div>

                    @if($store->status == 'rejected' && $store->rejection_reason)
                        <div class="alert alert-danger">
                            <strong>{{ t_db('general', 'rejection_reason') }}:</strong>
                            <p class="mb-0">{{ $store->rejection_reason }}</p>
                        </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <strong>{{ t_db('general', 'logo') }}:</strong>
                            @if($store->logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $store->logo) }}" alt="Logo" class="img-fluid rounded" style="max-height: 150px;">
                                </div>
                            @else
                                <p class="text-muted">{{ t_db('general', 'no_image') }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>{{ t_db('general', 'banner_image') }}:</strong>
                            @if($store->banner_image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $store->banner_image) }}" alt="Banner" class="img-fluid rounded" style="max-height: 150px;">
                                </div>
                            @else
                                <p class="text-muted">{{ t_db('general', 'no_image') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer border-top">
                    <div class="d-flex justify-content-end gap-2">
                        @if($store->status != 'confirmed')
                            <button type="button" class="btn btn-outline-success change-status" 
                                    data-uuid="{{ $store->uuid }}" data-status="confirmed">
                                <i class="bx bx-check"></i> {{ t_db('general', 'confirm') }}
                            </button>
                        @endif
                        
                        @if($store->status != 'rejected')
                            <button type="button" class="btn btn-outline-danger change-status-reject" 
                                    data-uuid="{{ $store->uuid }}">
                                <i class="bx bx-x"></i> {{ t_db('general', 'reject') }}
                            </button>
                        @endif

                        <a href="{{ route('stores.edit', $store->uuid) }}" class="btn btn-warning">
                            <i class="bx bx-edit"></i> {{ t_db('general', 'edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- User Info --}}
        <div class="col-md-4 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ t_db('general', 'user_info') }}</h5>
                </div>
                <div class="card-body">
                    @if($store->user)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-md me-2">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    {{ strtoupper(substr($store->user->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $store->user->name }}</h6>
                                <small class="text-muted">{{ $store->user->email }}</small>
                            </div>
                        </div>
                        <div class="mb-2">
                            <i class="bx bx-phone me-1"></i> {{ $store->user->phone_number ?? '-' }}
                        </div>
                        <div class="mb-2">
                            <i class="bx bx-calendar me-1"></i> {{ t_db('general', 'registered') }}: {{ $store->user->created_at->format('d.m.Y') }}
                        </div>
                        <div>
                            <a href="{{ route('users.edit', $store->user->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                {{ t_db('general', 'view_profile') }}
                            </a>
                        </div>
                    @else
                        <p class="text-muted">{{ t_db('general', 'user_not_found') }}</p>
                    @endif
                </div>
            </div>

            {{-- Store Announcements --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ t_db('general', 'announcements') }}</h5>
                    <span class="badge bg-label-primary">{{ $store->announcements->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($store->announcements as $announcement)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @if($announcement->images->first())
                                        <img src="{{ asset('storage/' . $announcement->images->first()->path) }}" class="rounded me-3" width="50" height="50" style="object-fit: cover">
                                    @else
                                        <div class="rounded me-3 d-flex align-items-center justify-content-center bg-label-secondary" style="width: 50px; height: 50px;">
                                            <i class="bx bx-image"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('announcements.show', $announcement->uuid) }}" class="text-body fw-semibold d-block mb-1 text-truncate" style="max-width: 150px;">
                                            {{ $announcement->title }}
                                        </a>
                                        <small class="text-muted">{{ $announcement->price }} {{ $announcement->currency }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-label-{{ $announcement->status->color() }}">
                                    {{ $announcement->status->label() }}
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted py-4">
                                {{ t_db('general', 'no_records_found') }}
                            </li>
                        @endforelse
                    </ul>
                </div>
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

@push('js')
<script>
    $(document).ready(function() {
        // Confirm Status
        $('.change-status').click(function() {
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
        $('.change-status-reject').click(function() {
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
@endpush
