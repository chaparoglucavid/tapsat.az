@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ t_db('general', 'edit_ip_rule') }}</h3>
                        <a href="{{ route('ip-rules.index') }}" class="btn btn-outline-danger">
                            <i class="bx bx-left-arrow-alt"></i>
                            {{ t_db('general', 'back') }}
                        </a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('ip-rules.update', $ipRule) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">{{ t_db('general', 'ip_address') }}</label>
                                <input type="text" name="ip_address" class="form-control" value="{{ old('ip_address', $ipRule->ip_address) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ t_db('general', 'type') }}</label>
                                <select name="type" class="form-select" required>
                                    <option value="blocked" @selected(old('type', $ipRule->type) === 'blocked')>blocked</option>
                                    <option value="allowed" @selected(old('type', $ipRule->type) === 'allowed')>allowed</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ t_db('general', 'reason') }}</label>
                                <textarea name="reason" class="form-control" rows="3">{{ old('reason', $ipRule->reason) }}</textarea>
                            </div>

                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                       @checked(old('is_active', $ipRule->is_active))>
                                <label class="form-check-label" for="is_active">{{ t_db('general', 'is_active') }}</label>
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

