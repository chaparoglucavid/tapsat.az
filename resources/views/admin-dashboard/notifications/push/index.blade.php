@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ t_db('general', 'push_notifications') }}</h5>
                <a href="{{ route('push-notifications.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> {{ t_db('general', 'add_notification') }}
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ t_db('general', 'title') }}</th>
                                <th>{{ t_db('general', 'target') }}</th>
                                <th>{{ t_db('general', 'status') }}</th>
                                <th>{{ t_db('general', 'sent_at') }}</th>
                                <th>{{ t_db('general', 'created_at') }}</th>
                                <th>{{ t_db('general', 'actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $notification)
                                <tr>
                                    <td>{{ $notification->title }}</td>
                                    <td>
                                        <span class="badge bg-label-info">{{ $notification->target_label }}</span>
                                    </td>
                                    <td>
                                        @if($notification->status == 'sent')
                                            <span class="badge bg-success">{{ t_db('general', 'sent') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ t_db('general', 'draft') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $notification->sent_at ? $notification->sent_at->format('d.m.Y H:i') : '-' }}</td>
                                    <td>{{ $notification->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex">
                                            @if($notification->status == 'draft')
                                                <form action="{{ route('push-notifications.send', $notification->uuid) }}" method="POST" class="me-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="{{ t_db('general', 'send') }}">
                                                        <i class="bx bx-send"></i>
                                                    </button>
                                                </form>
                                                <a href="{{ route('push-notifications.edit', $notification->uuid) }}" class="btn btn-sm btn-outline-warning me-1" title="{{ t_db('general', 'edit') }}">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('push-notifications.destroy', $notification->uuid) }}" method="POST" onsubmit="return confirm('{{ t_db('general', 'are_you_sure') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ t_db('general', 'delete') }}">
                                                    <i class="bx bx-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $notifications->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
