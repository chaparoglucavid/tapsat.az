@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>{{ t_db('general', 'language_informations') }}</h3>
                            <a href="{{ route('languages.index') }}" class="btn btn-outline-danger">
                                <span>
                                    <i class="bx bx-left-arrow-alt"></i>
                                </span>
                                {{ t_db('general', 'back') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('languages.update', $language->uuid) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="defaultFormControlInput"
                                           class="form-label">{{ t_db('general', 'language_code') }}</label>
                                    <input type="text" required name="code" value="{{ $language->code }}" class="form-control" id="defaultFormControlInput"
                                           placeholder="{{ t_db('general', 'add_language_code') }}"
                                           aria-describedby="defaultFormControlHelp"/>
                                </div>

                                <div class="col-md-6">
                                    <label for="defaultFormControlInput"
                                           class="form-label">{{ t_db('general', 'language_name') }}</label>
                                    <input type="text" required name="name" value="{{ $language->name }}" class="form-control" id="defaultFormControlInput"
                                           placeholder="{{ t_db('general', 'add_language_name') }}"
                                           aria-describedby="defaultFormControlHelp"/>
                                </div>

                                <div class="col-md-6">
                                    <label for="defaultFormControlInput"
                                           class="form-label">{{ t_db('general', 'status') }}</label>
                                    <select class="form-select" name="is_active" id="exampleFormControlSelect1" aria-label="Default select example">
                                        <option disabled>{{ t_db('general', 'select_status') }}</option>
                                        <option value="1" {{ $language->is_active === 1 ? 'selected' : '' }}>{{ t_db('general', 'active') }}</option>
                                        <option value="0" {{ $language->is_active === 0 ? 'selected' : '' }}>{{ t_db('general', 'inactive') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch mb-2 mt-4">
                                        <input class="form-check-input" name="is_default" type="checkbox" id="flexSwitchCheckChecked" checked="{{ $language->is_default ? 'true' : 'false' }}">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">{{t_db('general', 'select_as_default_language')}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-outline-primary" type="submit">
                                <span>
                                    <i class="bx bx-save"></i>
                                </span>
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
