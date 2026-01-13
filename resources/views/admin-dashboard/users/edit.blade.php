@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-6">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>{{ t_db('general', 'edit_user') }}</h3>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-danger">
                            <i class="bx bx-left-arrow-alt"></i>
                            {{ t_db('general', 'back') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">{{ t_db('general', 'name') }}</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ t_db('general', 'email') }}</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ t_db('general', 'phone_number') }}</label>
                                <input type="text" name="phone_number" class="form-control" value="{{ $user->phone_number }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">{{ t_db('general', 'update') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection