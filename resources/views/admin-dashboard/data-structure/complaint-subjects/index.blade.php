@extends('admin-dashboard.layouts.admin-master')
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ t_db('general', 'complaint_subjects') }}</h3>
                <div class="d-flex gap-2">
                    <a href="{{ route('complaint-subjects.create') }}">
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
                            <th>{{ t_db('general', 'name') }}</th>
                            <th>{{ t_db('general', 'actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($subjects as $subject)
                            <tr>
                                <td>{{ $loop->iteration + ($subjects->currentPage() - 1) * $subjects->perPage() }}</td>
                                <td>{{ $subject->name }}</td>
                                <td class="d-flex align-items-center gap-1">
                                    <a href="{{ route('complaint-subjects.edit', $subject) }}" class="btn btn-icon item-edit"
                                       title="{{ t_db('general', 'edit') }}"><i class="icon-base bx bx-edit icon-sm"></i>
                                    </a>
                                    <form action="{{ route('complaint-subjects.destroy', $subject) }}" method="POST"
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
                                <td colspan="3" class="text-center">
                                    {{ t_db('general', 'no_records_found') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $subjects->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

