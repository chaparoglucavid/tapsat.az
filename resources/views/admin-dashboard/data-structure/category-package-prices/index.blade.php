@extends('admin-dashboard.layouts.admin-master')
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ t_db('general', 'category_package_prices') }}</h3>
            </div>

            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ t_db('general', 'parent_menu') }}</th>
                            <th>{{ t_db('general', 'name') }}</th>
                            <th>{{ t_db('general', 'actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td>{{ $category->parent?->getTranslation('name', app()->getLocale()) }}</td>
                                <td>{{ $category->getTranslation('name', app()->getLocale()) }}</td>
                                <td class="d-flex align-items-center">
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-package-prices-{{ $category->uuid }}">
                                        <i class="bx bx-dollar me-1"></i> {{ t_db('general', 'manage_prices') }}
                                    </button>

                                    {{-- MODAL --}}
                                    <div class="modal fade" id="modal-package-prices-{{ $category->uuid }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ t_db('general', 'manage_prices') }}: {{ $category->getTranslation('name', app()->getLocale()) }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form method="POST" action="{{ route('category-package-prices.update', $category->uuid) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="alert alert-info">
                                                            {{ t_db('general', 'set_price_for_each_package') }}
                                                        </div>
                                                        
                                                        <div class="table-responsive text-nowrap">
                                                            <table class="table table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th>{{ t_db('general', 'package') }}</th>
                                                                        <th>{{ t_db('general', 'price') }} (AZN)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($packages as $package)
                                                                        @php
                                                                            $pivotPrice = $category->packages->where('uuid', $package->uuid)->first()?->pivot->price;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>
                                                                                {{ $package->getTranslation('name', app()->getLocale()) }}
                                                                            </td>
                                                                            <td>
                                                                                <input 
                                                                                    type="number" 
                                                                                    step="0.01" 
                                                                                    name="package_prices[{{ $package->uuid }}]" 
                                                                                    class="form-control" 
                                                                                    value="{{ $pivotPrice }}" 
                                                                                    placeholder="0.00"
                                                                                >
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                            {{ t_db('general', 'close') }}
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            {{ t_db('general', 'save_changes') }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">
                                    {{ t_db('category_not_added') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4">
                                    {{ $categories->links('pagination::bootstrap-5') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
