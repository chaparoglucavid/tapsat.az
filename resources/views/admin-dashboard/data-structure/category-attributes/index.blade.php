@extends('admin-dashboard.layouts.admin-master')
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ t_db('general', 'category_attributes') }}</h3>
            </div>

            <div class="card-body">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ t_db('general', 'parent_menu') }}</th>
                        <th>{{ t_db('general', 'name') }}</th>
                        <th>{{ t_db('general', 'attributes_count') }}</th>
                        <th>{{ t_db('general', 'actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td> {{ $loop->iteration }} </td>
                            <td>{{ $category->parent?->getTranslation('name', app()->getLocale()) }}</td>
                            <td>{{ $category->getTranslation('name', app()->getLocale()) }}</td>
                            <td><span class="badge bg-label-primary">{{ $category->attributes_count }}</span></td>
                            <td class="d-flex align-items-center">
                                <a href="{{ route('category-attributes.edit', $category->uuid) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-list-check me-1"></i> {{ t_db('general', 'manage_attributes') }}
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
                                {{ $categories->links('pagination::bootstrap-5') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
