@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ t_db('general', 'analytics') }} /</span> {{ t_db('general', 'income_analytics') }}</h4>

        <div class="row">
            <!-- Total Income -->
            <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">{{ t_db('general', 'total_income') }}</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($totalIncome, 2) }} AZN</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-success rounded p-2">
                                    <i class="bx bx-wallet bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last 30 Days -->
            <div class="col-lg-6 col-md-6 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="card-info">
                                <p class="card-text">{{ t_db('general', 'income_last_30_days') }}</p>
                                <div class="d-flex align-items-end mb-2">
                                    <h4 class="card-title mb-0 me-2">{{ number_format($incomeLast30Days, 2) }} AZN</h4>
                                </div>
                            </div>
                            <div class="card-icon">
                                <span class="badge bg-label-info rounded p-2">
                                    <i class="bx bx-calendar bx-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Income Trend Chart -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ t_db('general', 'income_trend') }}</h5>
                    </div>
                    <div class="card-body">
                        <div id="incomeTrendChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card">
            <h5 class="card-header">{{ t_db('general', 'recent_transactions') }}</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ t_db('general', 'user') }}</th>
                            <th>{{ t_db('general', 'amount') }}</th>
                            <th>{{ t_db('general', 'status') }}</th>
                            <th>{{ t_db('general', 'date') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse($recentTransactions as $transaction)
                            <tr>
                                <td>
                                    @if($transaction->user)
                                        <div class="d-flex justify-content-start align-items-center user-name">
                                            <div class="avatar-wrapper">
                                                <div class="avatar me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-secondary">{{ strtoupper(substr($transaction->user->name, 0, 2)) }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <span class="emp_name text-truncate">{{ $transaction->user->name }}</span>
                                                <small class="emp_post text-truncate text-muted">{{ $transaction->user->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ number_format($transaction->amount, 2) }} AZN</td>
                                <td><span class="badge bg-label-{{ $transaction->status == 'paid' ? 'success' : 'warning' }}">{{ $transaction->status }}</span></td>
                                <td>{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ t_db('general', 'no_data_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var incomeTrendData = @json($incomeTrend);
        
        var options = {
            series: [{
                name: "{{ t_db('general', 'income') }}",
                data: incomeTrendData.map(item => item.total_amount)
            }],
            chart: {
                height: 350,
                type: 'bar',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: incomeTrendData.map(item => item.month_name),
            },
        };

        var chart = new ApexCharts(document.querySelector("#incomeTrendChart"), options);
        chart.render();
    });
</script>
@endsection
