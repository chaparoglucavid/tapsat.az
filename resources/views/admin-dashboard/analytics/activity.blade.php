@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ t_db('general', 'analytics') }} /</span> {{ t_db('general', 'activity_analytics') }}</h4>

        <div class="card">
            <h5 class="card-header">{{ t_db('general', 'all_activities') }}</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ t_db('general', 'user') }}</th>
                            <th>{{ t_db('general', 'action') }}</th>
                            <th>{{ t_db('general', 'subject') }}</th>
                            <th>{{ t_db('general', 'description') }}</th>
                            <th>{{ t_db('general', 'date') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($activities as $activity)
                            <tr>
                                <td>
                                    @if($activity->causer)
                                        <div class="d-flex justify-content-start align-items-center user-name">
                                            <div class="avatar-wrapper">
                                                <div class="avatar me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-secondary">{{ strtoupper(substr($activity->causer->name, 0, 2)) }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="emp_name text-truncate">{{ $activity->causer->name }}</span>
                                                <small class="emp_post text-truncate text-muted">{{ $activity->causer->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge bg-label-secondary">{{ t_db('general', 'system') }}</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-label-primary">{{ $activity->event }}</span></td>
                                <td>
                                    @if($activity->subject_type)
                                        <span class="badge bg-label-info">{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $activity->description }}</td>
                                <td>{{ $activity->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ t_db('general', 'no_data_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $activities->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
