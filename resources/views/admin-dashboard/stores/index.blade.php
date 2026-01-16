@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ t_db('general', 'stores') }}</h5>
                <a href="{{ route('stores.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> {{ t_db('general', 'add_store') }}
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ t_db('general', 'store_name') }}</th>
                                <th>{{ t_db('general', 'user') }}</th>
                                <th>{{ t_db('general', 'category') }}</th>
                                <th>{{ t_db('general', 'status') }}</th>
                                <th>{{ t_db('general', 'created_at') }}</th>
                                <th>{{ t_db('general', 'actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                                <tr>
                                    <td>{{ $store->store_name }}</td>
                                    <td>{{ $store->user->name ?? '-' }}</td>
                                    <td>{{ $store->category->name ?? '-' }}</td>
                                    <td>
                                        @if($store->status == 'pending')
                                            <span class="badge bg-warning">{{ t_db('general', 'pending') }}</span>
                                        @elseif($store->status == 'confirmed')
                                            <span class="badge bg-success">{{ t_db('general', 'confirmed') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ t_db('general', 'rejected') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $store->created_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('stores.show', $store->uuid) }}" class="btn btn-icon item-show" title="{{ t_db('general', 'show') }}">
                                                <i class="icon-base bx bx-show icon-sm"></i>
                                            </a>
                                            <a href="{{ route('stores.edit', $store->uuid) }}" class="btn btn-icon item-edit"
                                                 title="{{t_db('general', 'edit')}}"><i class="icon-base bx bx-edit icon-sm"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $stores->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
