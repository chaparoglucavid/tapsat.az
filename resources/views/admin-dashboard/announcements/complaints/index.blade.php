@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ t_db('general', 'complaints') }}</h3>
            </div>

            <div class="card-body">
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">{{ t_db('general', 'complaint_subject') }}</label>
                        <select name="subject_id" class="form-select" onchange="this.form.submit()">
                            <option value="">{{ t_db('general', 'all') }}</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected(request('subject_id') == $subject->id)>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ t_db('general', 'search') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ t_db('general', 'search_by_title') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">{{ t_db('general', 'filter') }}</button>
                        <a href="{{ route('announcements.complaints.index') }}" class="btn btn-outline-secondary">{{ t_db('general', 'reset') }}</a>
                    </div>
                </form>

                <div class="table-responsive text-nowrap">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ t_db('general', 'announcement') }}</th>
                            <th>{{ t_db('general', 'owner') }}</th>
                            <th>{{ t_db('general', 'complaint_subject') }}</th>
                            <th>{{ t_db('general', 'complaint_message') }}</th>
                            <th>{{ t_db('general', 'created_at') }}</th>
                            <th>{{ t_db('general', 'actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($complaints as $complaint)
                            <tr>
                                <td>{{ $loop->iteration + ($complaints->currentPage() - 1) * $complaints->perPage() }}</td>
                                <td>
                                    @if($complaint->announcement)
                                        <a href="{{ route('announcements.show', $complaint->announcement->uuid) }}">
                                            {{ $complaint->announcement->title }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $complaint->announcement?->user?->name ?? '-' }}</td>
                                <td>{{ $complaint->subject->name ?? '-' }}</td>
                                <td class="text-truncate" style="max-width: 260px;">
                                    {{ $complaint->message ?? '-' }}
                                </td>
                                <td>{{ $complaint->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    @if($complaint->announcement)
                                        <a href="{{ route('announcements.show', $complaint->announcement->uuid) }}" class="btn btn-sm btn-outline-secondary">
                                            {{ t_db('general', 'view_announcement') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    {{ t_db('general', 'no_records_found') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $complaints->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

