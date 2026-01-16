@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <div class="col-md-12">
                <div class="card">

                    {{-- HEADER --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ t_db('general', 'add_announcement') }}</h3>
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline-danger">
                            <i class="bx bx-left-arrow-alt"></i>
                            {{ t_db('general', 'back') }}
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
                        <form method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold d-block mb-3">{{ t_db('general', 'announcement_owner') }}</label>
                                    
                                    <div class="row g-3 mb-3">
                                        <div class="col-6">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content" for="owner_user">
                                                    <span class="custom-option-body">
                                                        <i class="bx bx-user mb-2 fs-3"></i>
                                                        <span class="custom-option-title">{{ t_db('general', 'user') }}</span>
                                                        <small>{{ t_db('general', 'select_existing_user') }}</small>
                                                    </span>
                                                    <input name="owner_type" class="form-check-input" type="radio" value="user" id="owner_user" checked />
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-check custom-option custom-option-icon">
                                                <label class="form-check-label custom-option-content" for="owner_store">
                                                    <span class="custom-option-body">
                                                        <i class="bx bx-store mb-2 fs-3"></i>
                                                        <span class="custom-option-title">{{ t_db('general', 'store') }}</span>
                                                        <small>{{ t_db('general', 'select_existing_store') }}</small>
                                                    </span>
                                                    <input name="owner_type" class="form-check-input" type="radio" value="store" id="owner_store" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="user_select_container" class="animate__animated animate__fadeIn">
                                        <label class="form-label">{{ t_db('general', 'select_user') }}</label>
                                        <select name="user_id" class="form-select select2">
                                            <option value="">{{ t_db('general', 'select_user') }}</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="store_select_container" class="d-none animate__animated animate__fadeIn">
                                        <label class="form-label">{{ t_db('general', 'select_store') }}</label>
                                        <select name="store_id" class="form-select select2">
                                            <option value="">{{ t_db('general', 'select_store') }}</option>
                                            @foreach($stores as $store)
                                                <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                                    {{ $store->store_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ t_db('general', 'category') }}</label>
                                    <select name="category_id" class="form-select select2" id="category-select" required>
                                        <option value="">{{ t_db('general', 'select_category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->getTranslation('name', app()->getLocale()) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">{{ t_db('general', 'city') }}</label>
                                    <select name="city_id" class="form-select select2" required>
                                        <option value="">{{ t_db('general', 'select_city') }}</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->getTranslation('name', app()->getLocale()) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ t_db('general', 'status') }}</label>
                                    <select name="status" class="form-select" required>
                                        @foreach(\App\Enums\AnnouncementStatus::cases() as $status)
                                            <option value="{{ $status->value }}" {{ old('status') == $status->value ? 'selected' : '' }}>
                                                {{ $status->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">{{ t_db('general', 'packages') }}</label>
                                <div class="d-flex gap-3 flex-wrap">
                                    @foreach($packages as $package)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="packages[]" value="{{ $package->id }}" id="package_{{ $package->id }}" {{ is_array(old('packages')) && in_array($package->id, old('packages')) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="package_{{ $package->id }}">
                                                {{ $package->getTranslation('name', app()->getLocale()) }} ({{ $package->duration_days }} {{ t_db('general', 'days') }})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ t_db('general', 'description') }}</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">{{ t_db('general', 'price') }} (AZN)</label>
                                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                                </div>
                                <div class="col-md-4 pt-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_new" id="is_new" {{ old('is_new') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_new">{{ t_db('general', 'is_new') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-4 pt-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="has_delivery" id="has_delivery" {{ old('has_delivery') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_delivery">{{ t_db('general', 'has_delivery') }}</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Dropzone Images --}}
                            <div class="mb-4">
                                <label class="form-label">{{ t_db('general', 'images') }}</label>
                                <div class="dropzone" id="document-dropzone"></div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bx bx-save"></i>
                                    {{ t_db('general', 'save') }}
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
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
        }
    </style>
@endsection

@section('js-code')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle Owner Selection
            const userRadio = document.getElementById('owner_user');
            const storeRadio = document.getElementById('owner_store');
            const userContainer = document.getElementById('user_select_container');
            const storeContainer = document.getElementById('store_select_container');

            function toggleOwnerSection() {
                if (userRadio.checked) {
                    userContainer.classList.remove('d-none');
                    storeContainer.classList.add('d-none');
                } else {
                    userContainer.classList.add('d-none');
                    storeContainer.classList.remove('d-none');
                }
            }

            userRadio.addEventListener('change', toggleOwnerSection);
            storeRadio.addEventListener('change', toggleOwnerSection);

            Dropzone.autoDiscover = false;
        
            var uploadedDocumentMap = {}
        var myDropzone = new Dropzone("#document-dropzone", {
            url: "{{ route('media.upload') }}",
            maxFilesize: 5, // MB
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="images[]" value="' + response.name + '">')
                uploadedDocumentMap[file.name] = response.name
            },
            removedfile: function (file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('form').find('input[name="images[]"][value="' + name + '"]').remove()
                
                // Optional: Send request to delete from server
                 $.ajax({
                     headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                     type: 'DELETE',
                     url: '{{ route('media.revert') }}',
                     data: {filename: name},
                     dataType: 'json'
                 });
            },
            init: function () {
                @if(isset($announcement) && $announcement->images)
                    var files = {!! json_encode($announcement->images) !!}
                    for (var i in files) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        this.options.thumbnail.call(this, file, file.original_url)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="images[]" value="' + file.file_name + '">')
                    }
                @endif
            }
        });
    });
    </script>
@endsection
