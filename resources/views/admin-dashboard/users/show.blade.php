@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <div class="avatar avatar-xl mb-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                </div>
                                <div class="user-info text-center">
                                    <h4 class="mb-2">{{ $user->name }}</h4>
                                    <span class="badge bg-label-secondary mt-1">{{ ucfirst($user->type) }}</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="pb-2 border-bottom mb-4">{{ t_db('general', 'details') }}</h5>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-3">
                                    <span class="fw-bold me-2">{{ t_db('general', 'name') }}:</span>
                                    <span>{{ $user->name }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">{{ t_db('general', 'email') }}:</span>
                                    <span>{{ $user->email }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">{{ t_db('general', 'phone_number') }}:</span>
                                    <span>{{ $user->phone_number }}</span>
                                </li>
                                <li class="mb-3">
                                    <span class="fw-bold me-2">{{ t_db('general', 'status') }}:</span>
                                    <span class="badge bg-label-success">Active</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-center pt-3">
                                <a href="{{ route('users.edit', $user->uuid) }}" class="btn btn-primary me-3">{{ t_db('general', 'edit') }}</a>
                                <a href="{{ route('users.index') }}" class="btn btn-label-secondary">{{ t_db('general', 'back') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- User Pills -->
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> {{ t_db('general', 'overview') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bx bx-bell me-1"></i> {{ t_db('general', 'notifications') }}</a>
                    </li>
                </ul>
                <!--/ User Pills -->

                <!-- Project table -->
<div class="card mb-4">
    <h5 class="card-header">{{ t_db('general', 'user_announcements') }}</h5>
    <div class="table-responsive text-nowrap mb-3">
        <table class="table datatable-project border-top">
            <thead>
            <tr>
                <th>{{ t_db('general', 'title') }}</th>
                <th>{{ t_db('general', 'category') }}</th>
                <th>{{ t_db('general', 'price') }}</th>
                <th>{{ t_db('general', 'status') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($user->announcements as $announcement)
                <tr>
                    <td>
                        <a href="{{ route('announcements.edit', $announcement->uuid) }}" class="text-body">
                            {{ $announcement->title }}
                        </a>
                    </td>
                    <td>{{ $announcement->category->name ?? '-' }}</td>
                    <td>{{ $announcement->price }} AZN</td>
                    <td>
                        <span class="badge bg-label-{{ $announcement->status == 'active' ? 'success' : 'warning' }}">
                            {{ ucfirst($announcement->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">{{ t_db('general', 'no_data_found') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- /Project table -->

<!-- Credit Cards table -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ t_db('general', 'credit_cards') }}</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddCard">
            <i class="bx bx-plus me-1"></i> {{ t_db('general', 'add_card') }}
        </button>
    </div>
    <div class="table-responsive text-nowrap mb-3">
        <table class="table border-top">
            <thead>
            <tr>
                <th>{{ t_db('general', 'card_holder') }}</th>
                <th>{{ t_db('general', 'card_number') }}</th>
                <th>{{ t_db('general', 'expires') }}</th>
                <th>{{ t_db('general', 'default') }}</th>
                <th>{{ t_db('general', 'actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($user->creditCards as $card)
                <tr>
                    <td>{{ $card->card_holder_name }}</td>
                    <td>**** **** **** {{ substr($card->card_number, -4) }}</td>
                    <td>{{ $card->expiration_date }}</td>
                    <td>
                        @if($card->is_default)
                            <span class="badge bg-label-primary">Default</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('user-credit-cards.destroy', $card->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" onclick="return confirm('{{ t_db('general', 'are_you_sure') }}')">
                                <i class="bx bx-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">{{ t_db('general', 'no_data_found') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- /Credit Cards table -->

<!-- Payments table -->
<div class="card mb-4">
    <h5 class="card-header">{{ t_db('general', 'payments') }}</h5>
    <div class="table-responsive text-nowrap mb-3">
        <table class="table border-top">
            <thead>
            <tr>
                <th>ID</th>
                <th>{{ t_db('general', 'amount') }}</th>
                <th>{{ t_db('general', 'method') }}</th>
                <th>{{ t_db('general', 'status') }}</th>
                <th>{{ t_db('general', 'date') }}</th>
            </tr>
            </thead>
            <tbody>
                @forelse($user->payments as $payment)
                    <tr>
                        <td>#{{ $payment->id }}</td>
                        <td>{{ $payment->amount }} {{ $payment->currency }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>
                            <span class="badge bg-label-{{ $payment->status == 'completed' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>{{ $payment->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">{{ t_db('general', 'no_data_found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
                <!-- /Payments table -->
                
            </div>
            <!--/ User Content -->
        </div>

        <!-- Modal Add Card -->
        <div class="modal fade" id="modalAddCard" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">{{ t_db('general', 'add_new_card') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('user-credit-cards.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->uuid }}">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="card_holder_name" class="form-label">{{ t_db('general', 'card_holder') }}</label>
                                    <input type="text" id="card_holder_name" name="card_holder_name" class="form-control" required placeholder="John Doe">
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-0">
                                    <label for="card_number" class="form-label">{{ t_db('general', 'card_number') }}</label>
                                    <input type="text" id="card_number" name="card_number" class="form-control" required placeholder="0000 0000 0000 0000" maxlength="16">
                                </div>
                                <div class="col mb-0">
                                    <label for="expiration_date" class="form-label">{{ t_db('general', 'expires') }}</label>
                                    <input type="text" id="expiration_date" name="expiration_date" class="form-control" required placeholder="MM/YY" maxlength="5">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col mb-0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default">
                                        <label class="form-check-label" for="is_default">
                                            {{ t_db('general', 'set_as_default') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ t_db('general', 'close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ t_db('general', 'save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection