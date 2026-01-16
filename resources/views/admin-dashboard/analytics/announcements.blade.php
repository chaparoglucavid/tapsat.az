@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ t_db('general', 'analytics') }} /</span> {{ t_db('general', 'announcement_analytics') }}</h4>

        <div class="row">
            <!-- Total -->
            <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">{{ t_db('general', 'total') }}</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $totalAnnouncements }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-primary rounded p-2">
                                    <i class="bx bx-detail bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active -->
            <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">{{ t_db('general', 'active') }}</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $activeAnnouncements }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="bx bx-check-circle bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">{{ t_db('general', 'pending') }}</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $pendingAnnouncements }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-warning rounded p-2">
                                    <i class="bx bx-time bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rejected -->
            <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">{{ t_db('general', 'rejected') }}</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $rejectedAnnouncements }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-danger rounded p-2">
                                    <i class="bx bx-x-circle bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Expired -->
            <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">{{ t_db('general', 'expired') }}</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ $expiredAnnouncements }}</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-secondary rounded p-2">
                                    <i class="bx bx-calendar-x bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Announcements by Category -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">{{ t_db('general', 'top_categories') }}</h5>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            @foreach($announcementsByCategory as $item)
                                <li class="d-flex mb-4 pb-1">
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">{{ $item->category_name }}</h6>
                                        </div>
                                        <div class="user-progress">
                                            <small class="fw-semibold">{{ $item->total }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Chart Placeholder (Optional) -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ t_db('general', 'status_distribution') }}</h5>
                    </div>
                    <div class="card-body">
                        <div id="statusChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var options = {
            series: [{{ $activeAnnouncements }}, {{ $pendingAnnouncements }}, {{ $rejectedAnnouncements }}, {{ $expiredAnnouncements }}],
            chart: {
                width: 380,
                type: 'pie',
            },
            labels: ['{{ t_db("general", "active") }}', '{{ t_db("general", "pending") }}', '{{ t_db("general", "rejected") }}', '{{ t_db("general", "expired") }}'],
            colors: ['#71dd37', '#ffab00', '#ff3e1d', '#8592a3'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#statusChart"), options);
        chart.render();
    });
</script>
@endsection
