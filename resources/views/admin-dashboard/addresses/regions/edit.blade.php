
@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <div class="col-md-12">
                <div class="card">

                    {{-- HEADER --}}
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ t_db('general', 'edit_region') }}</h3>
                        <a href="{{ route('regions.index') }}" class="btn btn-outline-danger">
                            <i class="bx bx-left-arrow-alt"></i>
                            {{ t_db('general', 'back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('regions.update', $region->uuid) }}">
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
                                                {{ t_db('general', 'region_name') }} ({{ strtoupper($lang->code) }})
                                            </label>

                                            <input
                                                type="text"
                                                name="name[{{ $lang->code }}]"
                                                class="form-control @error('name.'.$lang->code) is-invalid @enderror"
                                                value="{{ $region->getTranslation('name', $lang->code) }}"

                                                placeholder="{{ t_db('general', 'add_region_name') }}"
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
                                            <label class="form-label">{{ t_db('general', 'city') }}</label>
                                            <select name="city_uuid" class="form-select">
                                                @foreach($cities as $city)
                                                    <option value="{{ $city->uuid }}" {{ $region->city_uuid == $city->uuid ? 'selected' : '' }}>
                                                        {{ $city->getTranslation('name', app()->getLocale()) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">{{ t_db('general', 'status') }}</label>
                                            <select name="is_active" class="form-select">
                                                <option value="1" {{ $region->is_active === 1 ? 'selected' : '' }}>
                                                    {{ t_db('general', 'active') }}
                                                </option>
                                                <option value="0" {{ $region->is_active === 0 ? 'selected' : '' }}>
                                                    {{ t_db('general', 'inactive') }}
                                                </option>
                                            </select>
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
