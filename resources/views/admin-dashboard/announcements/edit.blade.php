@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <div class="col-md-12">
                <div class="card">

                    {{-- HEADER --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ t_db('general', 'edit_announcement') }}</h3>
                        <a href="{{ route('announcements.index') }}" class="btn btn-outline-danger">
                            <i class="bx bx-left-arrow-alt"></i>
                            {{ t_db('general', 'back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('announcements.update', $announcement->uuid) }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">{{ t_db('general', 'user') }}</label>
                                    <input type="text" class="form-control" value="{{ $announcement->user->name }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ t_db('general', 'category') }}</label>
                                    <select name="category_id" class="form-select select2" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $announcement->category_id == $category->id ? 'selected' : '' }}>
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
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ $announcement->city_id == $city->id ? 'selected' : '' }}>
                                                {{ $city->getTranslation('name', app()->getLocale()) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ t_db('general', 'status') }}</label>
                                    <select name="status" class="form-select" required>
                                        <option value="pending" {{ $announcement->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="active" {{ $announcement->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="rejected" {{ $announcement->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="sold" {{ $announcement->status == 'sold' ? 'selected' : '' }}>Sold</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ t_db('general', 'title') }}</label>
                                <input type="text" name="title" class="form-control" value="{{ $announcement->title }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ t_db('general', 'description') }}</label>
                                <textarea name="description" class="form-control" rows="4">{{ $announcement->description }}</textarea>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label">{{ t_db('general', 'price') }} (AZN)</label>
                                    <input type="number" step="0.01" name="price" class="form-control" value="{{ $announcement->price }}" required>
                                </div>
                                <div class="col-md-4 pt-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_new" id="is_new" {{ $announcement->is_new ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_new">{{ t_db('general', 'is_new') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-4 pt-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="has_delivery" id="has_delivery" {{ $announcement->has_delivery ? 'checked' : '' }}>
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
                                    {{ t_db('general', 'update') }}
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet" />
    <style>
        .dropzone {
            border: 2px dashed #d9dee3;
            border-radius: 0.5rem;
            background: #fdfdfd;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;
        
        var uploadedDocumentMap = {}
        var myDropzone = new Dropzone("#document-dropzone", {
            url: '{{ route('media.upload') }}',
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
                     data: name,
                     dataType: 'html'
                 });
            },
            init: function () {
                @if(isset($announcement) && $announcement->images)
                    var files = [
                        @foreach($announcement->images as $image)
                        {
                            name: "{{ basename($image->path) }}",
                            size: 12345, 
                            type: "image/jpeg", 
                            original_url: "{{ asset('storage/' . $image->path) }}",
                            file_name: "{{ basename($image->path) }}" 
                        },
                        @endforeach
                    ];
                    
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
    </script>
@endpush
