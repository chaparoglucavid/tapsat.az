@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <div class="col-md-12">
                <div class="card">

                    {{-- HEADER --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ t_db('general', 'edit_attribute') }}</h3>
                        <a href="{{ route('attributes.index') }}" class="btn btn-outline-danger">
                            <i class="bx bx-left-arrow-alt"></i>
                            {{ t_db('general', 'back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('attributes.update', $attribute->uuid) }}">
                            @csrf
                            @method('PUT')

                            <ul class="nav nav-pills mb-4" role="tablist">
                                @foreach($languages as $lang)
                                    <li class="nav-item me-2">
                                        <button
                                            type="button"
                                            class="nav-link {{ $lang->code === app()->getLocale() ? 'active' : '' }}"
                                            data-bs-toggle="tab"
                                            data-bs-target="#lang-{{ $lang->code }}"
                                            role="tab">
                                            {{ $lang->name }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content mb-4">
                                @foreach($languages as $lang)
                                    <div
                                        class="tab-pane fade {{ $lang->code === app()->getLocale() ? 'show active' : '' }}"
                                        id="lang-{{ $lang->code }}"
                                        role="tabpanel">

                                        <div class="mb-3">
                                            <label class="form-label">
                                                {{ t_db('general', 'attribute_name') }} ({{ strtoupper($lang->code) }})
                                            </label>

                                            <input
                                                type="text"
                                                name="name[{{ $lang->code }}]"
                                                class="form-control @error('name.'.$lang->code) is-invalid @enderror"
                                                value="{{ $attribute->getTranslation('name', $lang->code) }}"
                                                placeholder="{{ t_db('general', 'attribute_name') }}"
                                                required
                                            >

                                            @error('name.'.$lang->code)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                @endforeach
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ t_db('general', 'type') }}</label>
                                            <select name="type" class="form-select" id="attribute-type">
                                                <option value="text" {{ $attribute->type === 'text' ? 'selected' : '' }}>Text</option>
                                                <option value="number" {{ $attribute->type === 'number' ? 'selected' : '' }}>Number</option>
                                                <option value="select" {{ $attribute->type === 'select' ? 'selected' : '' }}>Select</option>
                                                <option value="boolean" {{ $attribute->type === 'boolean' ? 'selected' : '' }}>Boolean (Yes/No)</option>
                                                <option value="date" {{ $attribute->type === 'date' ? 'selected' : '' }}>Date</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ t_db('general', 'status') }}</label>
                                            <select name="is_active" class="form-select">
                                                <option value="1" {{ old('is_active', $attribute->is_active) == 1 ? 'selected' : '' }}>
                                                    {{ t_db('general', 'active') }}
                                                </option>
                                                <option value="0" {{ old('is_active', $attribute->is_active) == 0 ? 'selected' : '' }}>
                                                    {{ t_db('general', 'inactive') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Options Container (Only for Select type) --}}
                                    <div id="options-container" class="{{ $attribute->type === 'select' ? '' : 'd-none' }}">
                                        <h5 class="mt-4">{{ t_db('general', 'options') }}</h5>
                                        <div id="options-list">
                                            @if($attribute->type === 'select')
                                                @foreach($attribute->options as $index => $option)
                                                    <div class="row mb-2 option-item" data-id="{{ $option->id }}">
                                                        @foreach($languages as $lang)
                                                            <div class="col-md-{{ floor(11 / count($languages)) }}">
                                                                <input type="text" name="temp_options[{{ $index }}][{{ $lang->code }}]" class="form-control" value="{{ $option->getTranslation('value', $lang->code) }}" placeholder="Option ({{ strtoupper($lang->code) }})" required>
                                                            </div>
                                                        @endforeach
                                                        <div class="col-md-1">
                                                            <button type="button" class="btn btn-icon btn-danger remove-option">
                                                                <i class="bx bx-trash"></i>
                                                            </button>
                                                        </div>
                                                        <input type="hidden" name="options[]" class="final-option-input">
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary mt-2" id="add-option">
                                            <i class="bx bx-plus"></i> {{ t_db('general', 'add_option') }}
                                        </button>
                                    </div>
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

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('attribute-type');
            const optionsContainer = document.getElementById('options-container');
            const optionsList = document.getElementById('options-list');
            const addOptionBtn = document.getElementById('add-option');
            let optionCount = {{ $attribute->options->count() }};

            function toggleOptions() {
                if (typeSelect.value === 'select') {
                    optionsContainer.classList.remove('d-none');
                } else {
                    optionsContainer.classList.add('d-none');
                }
            }

            typeSelect.addEventListener('change', toggleOptions);
            toggleOptions(); 

            addOptionBtn.addEventListener('click', function() {
                const optionHtml = `
                    <div class="row mb-2 option-item" data-id="">
                        @foreach($languages as $lang)
                            <div class="col-md-{{ floor(11 / count($languages)) }}">
                                <input type="text" name="temp_options[${optionCount}][{{ $lang->code }}]" class="form-control" placeholder="Option ({{ strtoupper($lang->code) }})" required>
                            </div>
                        @endforeach
                        <div class="col-md-1">
                            <button type="button" class="btn btn-icon btn-danger remove-option">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                        <input type="hidden" name="options[]" class="final-option-input">
                    </div>
                `;
                optionsList.insertAdjacentHTML('beforeend', optionHtml);
                optionCount++;
            });

            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-option')) {
                    e.target.closest('.option-item').remove();
                }
            });

            // Form submit handler to convert options to JSON
            document.querySelector('form').addEventListener('submit', function(e) {
                if (typeSelect.value === 'select') {
                    const optionItems = document.querySelectorAll('.option-item');
                    optionItems.forEach((item, index) => {
                        const inputs = item.querySelectorAll('input[name^="temp_options"]');
                        const finalInput = item.querySelector('.final-option-input');
                        const valueObj = {};
                        
                        inputs.forEach(input => {
                            const langCode = input.name.match(/\[([a-z]{2})\]/)[1];
                            valueObj[langCode] = input.value;
                        });
                        
                        const optionId = item.dataset.id || null;
                        const finalData = {
                            id: optionId,
                            value: valueObj,
                            order: index // Add order based on current position
                        };

                        finalInput.value = JSON.stringify(finalData);
                    });
                }
            });
        });
    </script>
    @endpush
@endsection
