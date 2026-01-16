@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-pulse me-2"></i> {{ t_db('general', 'system_status') }}
                    </h5>
                    <div>
                        <span class="badge bg-{{ $health['healthy'] ? 'success' : 'danger' }} fs-6">
                            <i class="bx {{ $health['healthy'] ? 'bx-check-circle' : 'bx-x-circle' }} me-1"></i>
                            {{ $health['healthy'] ? t_db('general', 'healthy') : t_db('general', 'issues_found') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        @foreach($health['resources'] as $key => $resource)
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="card h-100 border {{ $resource['health']['healthy'] ? 'border-success' : 'border-danger' }} shadow-none">
                                    <div class="card-body d-flex justify-content-between align-items-start">
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-truncate" title="{{ $resource['name'] }}">{{ $resource['name'] }}</h6>
                                            <small class="text-muted mb-2">{{ $resource['slug'] ?? $key }}</small>
                                            
                                            @if(!$resource['health']['healthy'])
                                                <small class="text-danger mt-auto">
                                                    <i class="bx bx-error me-1"></i> {{ $resource['health']['message'] ?? 'Unknown Error' }}
                                                </small>
                                            @else
                                                <small class="text-success mt-auto">
                                                    <i class="bx bx-check me-1"></i> {{ t_db('general', 'operational') }}
                                                </small>
                                            @endif
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-label-{{ $resource['health']['healthy'] ? 'success' : 'danger' }}">
                                                <i class="bx {{ $resource['health']['healthy'] ? 'bx-check' : 'bx-x' }} fs-4"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
