@extends('admin-dashboard.layouts.admin-master')
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ t_db('general', 'announcements') }}</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('announcements.create') }}">
                        <button class="btn btn-primary">
                            <i class="bx bx-plus"></i> {{ t_db('general', 'add_new') }}
                        </button>
                    </a>
                </div>
            </div>

            <div class="card-body">
    <div class="table-responsive text-nowrap">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ t_db('general', 'owner') }}</th>
                <th>{{ t_db('general', 'type') }}</th>
                <th>{{ t_db('general', 'title') }}</th>
                <th>{{ t_db('general', 'category') }}</th>
                <th>{{ t_db('general', 'price') }}</th>
                <th>{{ t_db('general', 'status') }}</th>
                <th>{{ t_db('general', 'actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($announcements as $announcement)
                <tr>
                    <td> {{ $loop->iteration }} </td>
                    <td>
                        @if($announcement->store)
                            {{ $announcement->store->store_name }}
                        @else
                            {{ $announcement->user->name ?? '-' }}
                        @endif
                    </td>
                    <td>
                        @if($announcement->store)
                            <span class="badge bg-label-info">{{ t_db('general', 'store_product') }}</span>
                        @else
                            <span class="badge bg-label-primary">{{ t_db('general', 'user_product') }}</span>
                        @endif
                    </td>
                    <td>{{ $announcement->title }}</td>
                    <td>{{ $announcement->category->getTranslation('name', app()->getLocale()) }}</td>
                    <td>{{ $announcement->price }} {{ $announcement->currency }}</td>
                    <td>
                        <span class="badge bg-label-{{ $announcement->status->color() }}">
                            {{ $announcement->status->label() }}
                        </span>
                    </td>
                    <td class="d-flex align-items-center gap-1">
                        <a href="{{ route('announcements.show', $announcement->uuid) }}" class="btn btn-icon"
                           title="{{ t_db('general', 'show') }}">
                            <i class="icon-base bx bx-show icon-sm"></i>
                        </a>
                        <a href="{{ route('announcements.edit', $announcement->uuid) }}" class="btn btn-icon item-edit"
                           title="{{ t_db('general', 'edit') }}"><i class="icon-base bx bx-edit icon-sm"></i>
                        </a>
                        <form action="{{ route('announcements.destroy', $announcement->uuid) }}" method="POST" onsubmit="return confirm('{{ t_db('general', 'are_you_sure') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-icon item-trash text-danger" title="{{ t_db('general', 'delete') }}">
                                <i class="icon-base bx bx-trash icon-sm"></i>
                            </button>
                        </form>
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
            @if(count($announcements) > 20)
            <tfoot>
                <tr>
                    <td colspan="7">
                        {{ $announcements->links('pagination::bootstrap-5') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
        </div>
    </div>
@endsection
