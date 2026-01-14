@extends('admin-dashboard.layouts.admin-master')
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ t_db('general', 'ip_management') }}</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('ip-rules.create') }}">
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
                            <th>{{ t_db('general', 'ip_address') }}</th>
                            <th>{{ t_db('general', 'type') }}</th>
                            <th>{{ t_db('general', 'reason') }}</th>
                            <th>{{ t_db('general', 'is_active') }}</th>
                            <th>{{ t_db('general', 'actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($rules as $rule)
                            <tr>
                                <td>{{ $loop->iteration + ($rules->currentPage() - 1) * $rules->perPage() }}</td>
                                <td>{{ $rule->ip_address }}</td>
                                <td>{{ $rule->type }}</td>
                                <td class="text-truncate" style="max-width: 260px;">
                                    {{ $rule->reason ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-label-{{ $rule->is_active ? 'success' : 'secondary' }}">
                                        {{ $rule->is_active ? t_db('general', 'active') : t_db('general', 'inactive') }}
                                    </span>
                                </td>
                                <td class="d-flex align-items-center gap-1">
                                    <a href="{{ route('ip-rules.edit', $rule) }}" class="btn btn-icon item-edit"
                                       title="{{ t_db('general', 'edit') }}"><i class="icon-base bx bx-edit icon-sm"></i>
                                    </a>
                                    <form action="{{ route('ip-rules.destroy', $rule) }}" method="POST"
                                          onsubmit="return confirm('{{ t_db('general', 'are_you_sure') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon item-trash text-danger"
                                                title="{{ t_db('general', 'delete') }}">
                                            <i class="icon-base bx bx-trash icon-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    {{ t_db('general', 'no_records_found') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $rules->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

