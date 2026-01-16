@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t_db('general', 'create_notification') }}</h5>
            <a href="{{ route('push-notifications.index') }}" class="btn btn-outline-danger">
                <i class="bx bx-left-arrow-alt"></i> {{ t_db('general', 'back') }}
            </a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('push-notifications.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">{{ t_db('general', 'title') }}</label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ t_db('general', 'message') }}</label>
                    <textarea name="message" class="form-control" rows="3" required>{{ old('message') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ t_db('general', 'deep_link') }} ({{ t_db('general', 'optional') }})</label>
                    <input type="url" name="deep_link" class="form-control" value="{{ old('deep_link') }}" placeholder="https://example.com/page">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold d-block mb-3">{{ t_db('general', 'target') }}</label>
                    
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="target_all">
                                    <span class="custom-option-body">
                                        <i class="bx bx-globe mb-2 fs-3"></i>
                                        <span class="custom-option-title">{{ t_db('general', 'all_users') }}</span>
                                    </span>
                                    <input name="target_type" class="form-check-input" type="radio" value="all" id="target_all" checked />
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="target_users">
                                    <span class="custom-option-body">
                                        <i class="bx bx-user mb-2 fs-3"></i>
                                        <span class="custom-option-title">{{ t_db('general', 'selected_users') }}</span>
                                    </span>
                                    <input name="target_type" class="form-check-input" type="radio" value="users" id="target_users" />
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check custom-option custom-option-icon">
                                <label class="form-check-label custom-option-content" for="target_category">
                                    <span class="custom-option-body">
                                        <i class="bx bx-category mb-2 fs-3"></i>
                                        <span class="custom-option-title">{{ t_db('general', 'category') }}</span>
                                    </span>
                                    <input name="target_type" class="form-check-input" type="radio" value="category" id="target_category" />
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Target: Users --}}
                    <div id="target_users_container" class="d-none animate__animated animate__fadeIn">
                        <label class="form-label">{{ t_db('general', 'select_users') }}</label>
                        <select name="target_users[]" class="form-select select2" multiple>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <div class="form-text">Select one or more users</div>
                    </div>

                    {{-- Target: Category --}}
                    <div id="target_category_container" class="d-none animate__animated animate__fadeIn">
                        <label class="form-label">{{ t_db('general', 'select_category') }}</label>
                        <select name="target_category" class="form-select select2">
                            <option value="">{{ t_db('general', 'select_category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save"></i> {{ t_db('general', 'save_draft') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js-code')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="target_type"]');
        const usersContainer = document.getElementById('target_users_container');
        const categoryContainer = document.getElementById('target_category_container');

        function toggleTargets() {
            usersContainer.classList.add('d-none');
            categoryContainer.classList.add('d-none');

            const selected = document.querySelector('input[name="target_type"]:checked').value;
            
            if (selected === 'users') {
                usersContainer.classList.remove('d-none');
            } else if (selected === 'category') {
                categoryContainer.classList.remove('d-none');
            }
        }

        radios.forEach(radio => {
            radio.addEventListener('change', toggleTargets);
        });

        // Initial check
        toggleTargets();
    });
</script>
@endsection
