@extends('admin-dashboard.layouts.admin-master')
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ t_db('general', 'attributes') }}</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('attributes.create') }}">
                        <button class="btn btn-primary">
                            <i class="bx bx-plus"></i> {{ t_db('general', 'add_new') }}
                        </button>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ t_db('general', 'name') }}</th>
                        <th>{{ t_db('general', 'type') }}</th>
                        <th>{{ t_db('general', 'status') }}</th>
                        <th>{{ t_db('general', 'actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($attributes as $attribute)
                        <tr>
                            <td> {{ $loop->iteration }} </td>
                            <td>{{ $attribute->getTranslation('name', app()->getLocale()) }}</td>
                            <td><span class="badge bg-label-info">{{ $attribute->type }}</span></td>
                            <td>
                                <span class="badge bg-label-{{ $attribute->is_active ? 'success' : 'danger' }}">
                                    {{ $attribute->is_active ? t_db('general', 'active') : t_db('general', 'inactive') }}
                                </span>
                            </td>
                            <td class="d-flex align-items-center">
                                <a href="{{ route('attributes.edit', $attribute->uuid) }}" class="btn btn-icon item-edit"
                                   title="{{t_db('general', 'edit')}}"><i class="icon-base bx bx-edit icon-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                {{ t_db('general', 'no_records_found') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                {{ $attributes->links('pagination::bootstrap-5') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
