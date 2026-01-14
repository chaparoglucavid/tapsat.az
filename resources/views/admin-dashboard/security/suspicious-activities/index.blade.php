@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ t_db('general', 'suspicious_activities') }}</h3>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('security.suspicious-activities.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ t_db('general', 'email') }}</label>
                        <input type="text" name="email" value="{{ request('email') }}" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ t_db('general', 'ip_address') }}</label>
                        <input type="text" name="ip" value="{{ request('ip') }}" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-search"></i> {{ t_db('general', 'filter') }}
                        </button>
                        <a href="{{ route('security.suspicious-activities.index') }}" class="btn btn-outline-secondary">
                            {{ t_db('general', 'reset') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ t_db('general', 'email') }}</th>
                            <th>{{ t_db('general', 'ip_address') }}</th>
                            <th>{{ t_db('general', 'reason') }}</th>
                            <th>{{ t_db('general', 'description') }}</th>
                            <th>{{ t_db('general', 'created_at') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($activities as $activity)
                            @php
                                $props = $activity->properties ?? [];
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration + ($activities->currentPage() - 1) * $activities->perPage() }}</td>
                                <td>{{ $props['email'] ?? '-' }}</td>
                                <td>{{ $props['ip'] ?? '-' }}</td>
                                <td>{{ $props['reason'] ?? '-' }}</td>
                                <td>{{ $activity->description }}</td>
                                <td>{{ $activity->created_at?->format('d.m.Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    {{ t_db('general', 'no_records_found') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $activities->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

