@extends('admin-dashboard.layouts.admin-master')

@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="mb-0">{{ t_db('general', 'users') }}</h3>
</div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{ t_db('general', 'name') }}</th>
                            <th>{{ t_db('general', 'email') }}</th>
                            <th>{{ t_db('general', 'phone_number') }}</th>
                            <th>{{ t_db('general', 'created_at') }}</th>
                            <th>{{ t_db('general', 'actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone_number }}</td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                <td class="d-flex align-items-center">
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-icon item-edit" title="{{ t_db('general', 'show') }}">
                                        <i class="bx bx-show icon-sm"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-icon item-edit" title="{{ t_db('general', 'edit') }}">
                                        <i class="bx bx-edit icon-sm"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ t_db('general', 'are_you_sure') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon item-trash text-danger" title="{{ t_db('general', 'delete') }}">
                                            <i class="bx bx-trash icon-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="6">
                                    {{ $users->links('pagination::bootstrap-5') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection