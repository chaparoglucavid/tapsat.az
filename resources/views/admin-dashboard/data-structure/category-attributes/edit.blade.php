@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <div class="col-md-12">
                <div class="card">

                    {{-- HEADER --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ t_db('general', 'manage_attributes') }}: {{ $category->getTranslation('name', app()->getLocale()) }}</h3>
                        <a href="{{ route('category-attributes.index') }}" class="btn btn-outline-danger">
                            <i class="bx bx-left-arrow-alt"></i>
                            {{ t_db('general', 'back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('category-attributes.update', $category->uuid) }}">
                            @csrf
                            @method('PUT')

                            <div class="alert alert-info">
                                {{ t_db('general', 'select_attributes_for_category') }}
                            </div>
                            
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 50px;">
                                                <input type="checkbox" class="form-check-input" id="select-all">
                                            </th>
                                            <th>{{ t_db('general', 'attribute') }}</th>
                                            <th>{{ t_db('general', 'type') }}</th>
                                            <th>{{ t_db('general', 'is_required') }}</th>
                                            <th>{{ t_db('general', 'is_filterable') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attributes as $attribute)
                                            @php
                                                $catAttr = $categoryAttributes->where('attribute_id', $attribute->id)->first();
                                                $isChecked = (bool) $catAttr;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input 
                                                        type="checkbox" 
                                                        name="attributes[]" 
                                                        value="{{ $attribute->id }}" 
                                                        class="form-check-input attribute-checkbox"
                                                        {{ $isChecked ? 'checked' : '' }}
                                                    >
                                                </td>
                                                <td>
                                                    {{ $attribute->getTranslation('name', app()->getLocale()) }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-label-info">{{ $attribute->type }}</span>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input 
                                                            class="form-check-input" 
                                                            type="checkbox" 
                                                            name="is_required[{{ $attribute->id }}]" 
                                                            {{ $catAttr && $catAttr->is_required ? 'checked' : '' }}
                                                        >
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input 
                                                            class="form-check-input" 
                                                            type="checkbox" 
                                                            name="is_filterable[{{ $attribute->id }}]" 
                                                            {{ $catAttr && $catAttr->is_filterable ? 'checked' : '' }}
                                                        >
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-save"></i>
                                    {{ t_db('general', 'save_changes') }}
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
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.attribute-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
    @endpush
@endsection
