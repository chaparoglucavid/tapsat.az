@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t_db('general', 'add_store') }}</h5>
            <a href="{{ route('stores.index') }}" class="btn btn-outline-danger">
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

            <form method="POST" action="{{ route('stores.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- User Selection Section --}}
                <div class="mb-4 p-3 border rounded bg-light">
                    <label class="form-label fw-bold">{{ t_db('general', 'store_owner') }}</label>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="user_selection_type" id="user_existing" value="existing" checked>
                            <label class="form-check-label" for="user_existing">{{ t_db('general', 'existing_user') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="user_selection_type" id="user_new" value="new">
                            <label class="form-check-label" for="user_new">{{ t_db('general', 'new_user') }}</label>
                        </div>
                    </div>

                    {{-- Existing User Select --}}
                    <div id="existing_user_section">
                        <select name="user_id" class="form-select select2">
                            <option value="">{{ t_db('general', 'select_user') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- New User Inputs --}}
                    <div id="new_user_section" class="d-none">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">{{ t_db('general', 'name') }}</label>
                                <input type="text" name="new_user_name" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ t_db('general', 'email') }}</label>
                                <input type="email" name="new_user_email" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ t_db('general', 'password') }}</label>
                                <input type="password" name="new_user_password" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Store Details --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ t_db('general', 'store_name') }}</label>
                        <input type="text" name="store_name" class="form-control" required value="{{ old('store_name') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ t_db('general', 'category') }}</label>
                        <select name="category_id" class="form-select select2" required>
                            <option value="">{{ t_db('general', 'select_category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ t_db('general', 'phone_number') }}</label>
                        <input type="text" name="phone_number" class="form-control" required value="{{ old('phone_number') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ t_db('general', 'email') }}</label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ t_db('general', 'address') }}</label>
                    <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">{{ t_db('general', 'working_days') }}</label>
                        <div class="d-flex flex-wrap gap-3">
                            @php $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']; @endphp
                            @foreach($days as $day)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="working_days_arr[]" value="{{ $day }}" id="day_{{ $day }}">
                                    <label class="form-check-label" for="day_{{ $day }}">{{ $day }}</label>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="working_days" id="working_days_input">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ t_db('general', 'working_hours') }}</label>
                    <input type="text" name="working_hours" class="form-control" placeholder="e.g. 09:00 - 18:00" required value="{{ old('working_hours') }}">
                </div>

                {{-- Images --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">{{ t_db('general', 'logo') }}</label>
                        <div class="dropzone" id="logo-dropzone"></div>
                        <input type="hidden" name="logo" id="logo_input">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ t_db('general', 'banner_image') }}</label>
                        <div class="dropzone" id="banner-dropzone"></div>
                        <input type="hidden" name="banner_image" id="banner_input">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" id="submit-btn">
                        <i class="bx bx-save"></i> {{ t_db('general', 'save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css-code')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet" />
<style>
    .dropzone {
        border: 2px dashed #d9dee3;
        border-radius: 0.5rem;
        background: #fdfdfd;
        min-height: 150px;
        padding: 20px;
    }
</style>
@endsection

@section('js-code')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle User Selection
        const existingRadio = document.getElementById('user_existing');
        const newRadio = document.getElementById('user_new');
        const existingSection = document.getElementById('existing_user_section');
        const newSection = document.getElementById('new_user_section');

        function toggleUserSection() {
            if (existingRadio.checked) {
                existingSection.classList.remove('d-none');
                newSection.classList.add('d-none');
            } else {
                existingSection.classList.add('d-none');
                newSection.classList.remove('d-none');
            }
        }

        existingRadio.addEventListener('change', toggleUserSection);
        newRadio.addEventListener('change', toggleUserSection);

        // Working Days Handling
        const checkboxes = document.querySelectorAll('input[name="working_days_arr[]"]');
        const hiddenInput = document.getElementById('working_days_input');
        
        document.getElementById('submit-btn').addEventListener('click', function() {
            let selected = [];
            checkboxes.forEach(cb => {
                if(cb.checked) selected.push(cb.value);
            });
            hiddenInput.value = selected.join(',');
        });

        // Dropzone Config
        Dropzone.autoDiscover = false;

        function initDropzone(id, inputId) {
            new Dropzone(id, {
                url: "{{ route('media.upload') }}",
                maxFiles: 1,
                maxFilesize: 5,
                acceptedFiles: 'image/*',
                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
                success: function(file, response) {
                    document.getElementById(inputId).value = response.name;
                },
                removedfile: function(file) {
                    file.previewElement.remove();
                    document.getElementById(inputId).value = '';
                }
            });
        }

        initDropzone("#logo-dropzone", "logo_input");
        initDropzone("#banner-dropzone", "banner_input");
    });
</script>
@endsection
