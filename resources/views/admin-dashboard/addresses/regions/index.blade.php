@extends('admin-dashboard.layouts.admin-master')
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ t_db('general', 'regions') }}</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('regions.create') }}">
                        <button class="btn btn-primary">
                            <i class="bx bx-plus"></i> {{ t_db('general', 'add_new') }}
                        </button>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <table id="languages" class="table align-middle">
                    <thead>
                    <tr>
                        <th>
                            #
                        </th>
                        <th>{{ t_db('general', 'city') }}</th>
                        <th>{{ t_db('general', 'name') }}</th>
                        <th>{{ t_db('general', 'is_active') }}</th>
                        <th>{{ t_db('general', 'actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($regions as $region)
                        <tr>
                            <td> {{ $loop->iteration }} </td>
                            <td>{{ $region->city?->name }}</td>
                            <td>{{ $region->getTranslation('name', app()->getLocale()) }}</td>
                            <td>
                                <span
                                    class="badge bg-label-{{ $region->is_active ? 'success' : 'danger' }}">{{ $region->is_active ? t_db('general', 'active') : t_db('general', 'inactive') }}</span>
                            </td>
                            <td class="d-flex align-items-center">
                                <a href="{{ route('regions.edit', $region->uuid) }}" class="btn btn-icon item-edit"
                                   title="{{t_db('general', 'edit')}}"><i class="icon-base bx bx-edit icon-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                {{ t_db('region_not_added') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                {{ $regions->links('pagination::bootstrap-5') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
