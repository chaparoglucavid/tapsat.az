@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <div class="col-md-12">
                <div class="card">

                    {{-- HEADER --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ t_db('general', 'edit_category') }}</h3>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-danger">
                            <i class="bx bx-left-arrow-alt"></i>
                            {{ t_db('general', 'back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('categories.update', $category->uuid) }}">
                            @csrf
                            @method('PUT')

                            <div class="tab-content mb-4">
                                {{-- Basic Info Tab --}}
                                <div class="tab-pane fade show active" id="basic-info" role="tabpanel">
                                    <ul class="nav nav-pills mb-3" role="tablist">
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

                                    <div class="tab-content mb-3">
                                        @foreach($languages as $lang)
                                            <div
                                                class="tab-pane fade {{ $lang->code === app()->getLocale() ? 'show active' : '' }}"
                                                id="lang-{{ $lang->code }}"
                                                role="tabpanel">

                                                <div class="mb-3">
                                                    <label class="form-label">
                                                        {{ t_db('general', 'category_name') }} ({{ strtoupper($lang->code) }})
                                                    </label>

                                                    <input
                                                        type="text"
                                                        name="name[{{ $lang->code }}]"
                                                        class="form-control @error('name.'.$lang->code) is-invalid @enderror"
                                                        value="{{ $category->getTranslation('name', $lang->code) }}"
                                                        placeholder="{{ t_db('general', 'category_name') }}"
                                                        required
                                                    >

                                                    @error('name.'.$lang->code)
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">{{ t_db('general', 'parent_category') }}</label>
                                            <select name="parent_uuid" class="form-select">
                                                <option disabled>{{ t_db('general', 'select') }}</option>
                                                <option value="parent" {{ $category->parent_uuid === 'parent' || is_null($category->parent_uuid) ? 'selected' : '' }}>------</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{$cat->uuid}}" {{ old('parent_uuid', $category->parent_uuid) === $cat->uuid ? 'selected' : '' }}>{{ $cat->getTranslation('name', app()->getLocale()) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">{{ t_db('general', 'status') }}</label>
                                            <select name="is_active" class="form-select">
                                                <option value="1" {{ old('is_active', $category->is_active) == 1 ? 'selected' : '' }}>
                                                    {{ t_db('general', 'active') }}
                                                </option>
                                                <option value="0" {{ old('is_active', $category->is_active) == 0 ? 'selected' : '' }}>
                                                    {{ t_db('general', 'inactive') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
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
@endsection
