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
                                    @if($user->isBanned())
                                        <span class="badge bg-label-danger" data-bs-toggle="tooltip" title="{{ t_db('general', 'banned_until') }}: {{ $user->banned_until->format('d.m.Y H:i') }}">
                                            {{ t_db('general', 'banned') }}
                                        </span>
                                    @else
                                        <span class="badge bg-label-success">{{ t_db('general', 'active') }}</span>
                                    @endif
                                </li>
                            </ul>
                            <div class="d-flex justify-content-center pt-3 gap-2">
                                <a href="{{ route('users.edit', $user->uuid) }}" class="btn btn-primary">{{ t_db('general', 'edit') }}</a>
                                
                                @if(!$user->isBanned())
                                    <button type="button" class="btn btn-danger ban-user" data-uuid="{{ $user->uuid }}">
                                        {{ t_db('general', 'ban') }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success unban-user" data-uuid="{{ $user->uuid }}">
                                        {{ t_db('general', 'unban') }}
                                    </button>
                                @endif

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
                        <span class="badge bg-label-{{ $announcement->status->color() }}">
                            {{ $announcement->status->label() }}
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

<!-- Credit Cards List -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ t_db('general', 'credit_cards') }}</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddCard">
            <i class="bx bx-plus me-1"></i> {{ t_db('general', 'add_card') }}
        </button>
    </div>
    <div class="card-body">
        <div class="row g-4">
            @forelse($user->creditCards as $card)
                <div class="col-xl-6 col-md-6 col-sm-12">
                    <div class="card text-white {{ $card->is_active ? ($card->is_default ? 'bg-primary' : 'bg-secondary') : 'bg-danger' }}" style="background: linear-gradient(135deg, {{ $card->is_active ? ($card->is_default ? '#696cff 0%, #4346e4' : '#8592a3 0%, #5a6a85') : '#ff3e1d 0%, #ff6b6b' }} 100%); position: relative; overflow: hidden; min-height: 200px; {{ $card->is_active ? '' : 'opacity: 0.8;' }}">
                        <div style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; background: url('https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/map.png') no-repeat center center; background-size: cover; opacity: 0.1;"></div>
                        
                        {{-- Action Buttons --}}
                        <div class="position-absolute top-0 end-0 p-3 zindex-2 d-flex gap-2">
                            <form action="{{ route('user-credit-cards.update', $card->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_active" value="{{ $card->is_active ? '0' : '1' }}">
                                <button type="submit" class="btn btn-icon btn-sm {{ $card->is_active ? 'btn-white text-warning' : 'btn-white text-success' }} rounded-pill shadow-sm" 
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ $card->is_active ? t_db('general', 'deactivate') : t_db('general', 'activate') }}">
                                    <i class="bx {{ $card->is_active ? 'bx-block' : 'bx-check-circle' }}"></i>
                                </button>
                            </form>
                            <form action="{{ route('user-credit-cards.destroy', $card->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-white text-danger rounded-pill shadow-sm" 
                                        onclick="return confirm('{{ t_db('general', 'are_you_sure') }}')" 
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="{{ t_db('general', 'delete') }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>

                        <div class="card-body position-relative zindex-1 d-flex flex-column justify-content-between h-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <img src="https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/chip.png" alt="chip" height="35">
                                <div class="d-flex align-items-center gap-2">
                                    @if(!$card->is_active)
                                        <span class="badge bg-danger bg-opacity-75 text-white border border-danger shadow-sm">{{ t_db('general', 'inactive') }}</span>
                                    @elseif($card->is_default)
                                        <span class="badge bg-success bg-opacity-75 text-white border border-success shadow-sm">
                                            <i class="bx bx-check me-1"></i> {{ t_db('general', 'default') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mt-4 mb-2 text-center">
                                <h3 class="text-white mb-0 font-monospace" style="letter-spacing: 3px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                    **** **** **** {{ substr($card->card_number, -4) }}
                                </h3>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-end">
                                <div>
                                    <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">{{ t_db('general', 'card_holder') }}</small>
                                    <h6 class="mb-0 text-white text-uppercase tracking-wide">{{ $card->card_holder_name }}</h6>
                                </div>
                                <div class="text-end">
                                    <img src="https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/{{ str_starts_with($card->card_number, '4') ? 'visa' : 'mastercard' }}.png" alt="card-logo" height="30" class="mb-2 d-block ms-auto">
                                    <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">{{ t_db('general', 'expires') }}</small>
                                    <h6 class="mb-0 text-white">{{ $card->expiration_date }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center text-muted py-4">
                        <i class="bx bx-credit-card fs-1 mb-2"></i>
                        <p>{{ t_db('general', 'no_credit_cards_found') }}</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
<!-- /Credit Cards List -->

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
                    <form action="{{ route('user-credit-cards.store') }}" method="POST" id="addCardForm">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->uuid }}">
                        <div class="modal-body">
                            <!-- Card Preview -->
                            <div class="card-preview mb-4 p-4 rounded-3 text-white" style="background: linear-gradient(135deg, #696cff 0%, #4346e4 100%); min-height: 200px; position: relative; overflow: hidden;">
                                <div style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; background: url('https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/map.png') no-repeat center center; background-size: cover; opacity: 0.1;"></div>
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="chip-img">
                                        <img src="https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/chip.png" alt="chip" height="40">
                                    </div>
                                    <div class="visa-img">
                                        <img src="https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/visa.png" alt="visa" height="40" id="cardLogo">
                                    </div>
                                </div>
                                <div class="card-number-display mb-3">
                                    <h3 class="mb-0 font-monospace" style="letter-spacing: 2px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);" id="displayCardNumber">#### #### #### ####</h3>
                                </div>
                                <div class="d-flex justify-content-between align-items-end mt-4">
                                    <div class="card-holder-display">
                                        <small class="text-white-50 text-uppercase" style="font-size: 0.7rem;">{{ t_db('general', 'card_holder') }}</small>
                                        <h6 class="mb-0 text-white text-uppercase" id="displayCardHolder">FULL NAME</h6>
                                    </div>
                                    <div class="card-expires-display">
                                        <small class="text-white-50 text-uppercase" style="font-size: 0.7rem;">{{ t_db('general', 'expires') }}</small>
                                        <h6 class="mb-0 text-white" id="displayExpires">MM/YY</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="card_number" class="form-label">{{ t_db('general', 'card_number') }}</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-credit-card"></i></span>
                                        <input type="text" id="card_number" name="card_number" class="form-control" required placeholder="0000 0000 0000 0000" maxlength="19">
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="card_holder_name" class="form-label">{{ t_db('general', 'card_holder') }}</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                        <input type="text" id="card_holder_name" name="card_holder_name" class="form-control" required placeholder="JOHN DOE" oninput="this.value = this.value.toUpperCase()">
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6 mb-0">
                                    <label for="expiration_date" class="form-label">{{ t_db('general', 'expires') }}</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                        <input type="text" id="expiration_date" name="expiration_date" class="form-control" required placeholder="MM/YY" maxlength="5">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-0">
                                    <label for="cvv" class="form-label">CVV</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                        <input type="text" id="cvv" name="cvv" class="form-control" required placeholder="123" maxlength="4">
                                    </div>
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

        // Credit Card Formatting
        const cardNumberInput = document.getElementById('card_number');
        const cardHolderInput = document.getElementById('card_holder_name');
        const cardExpiresInput = document.getElementById('expiration_date');
        const displayCardNumber = document.getElementById('displayCardNumber');
        const displayCardHolder = document.getElementById('displayCardHolder');
        const displayExpires = document.getElementById('displayExpires');
        const cardLogo = document.getElementById('cardLogo');

        // Card Number Formatting
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            let formattedValue = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }
            
            e.target.value = formattedValue;
            displayCardNumber.textContent = formattedValue || '#### #### #### ####';

            // Detect Card Type (Simple detection)
            if (value.startsWith('4')) {
                cardLogo.src = 'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/visa.png';
            } else if (value.startsWith('5')) {
                cardLogo.src = 'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/mastercard.png';
            }
        });

        // Card Holder Formatting
        cardHolderInput.addEventListener('input', function(e) {
            displayCardHolder.textContent = e.target.value.toUpperCase() || 'FULL NAME';
        });

        // Expiration Date Formatting
        cardExpiresInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
            displayExpires.textContent = value || 'MM/YY';
        });

        // CVV Restriction
        document.getElementById('cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    });
</script>
@endsection