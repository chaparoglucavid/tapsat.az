@extends('admin-dashboard.layouts.admin-master')
@push('css-code')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <style>
        .avatar-initial {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        table.dataTable thead th {
            font-size: 12px;
            color: #6c757d;
        }
    </style>
@endpush
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ t_db('general', 'languages') }}</h5>

                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bx bx-export me-1"></i> {{ t_db('general', 'export') }}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" id="exportCopy">{{ t_db('general', 'copy') }}</a></li>
                            <li><a class="dropdown-item" href="#" id="exportCsv">{{ t_db('general', 'csv') }}</a></li>
                            <li><a class="dropdown-item" href="#" id="exportExcel">{{ t_db('general', 'excel') }}</a>
                            </li>
                            <li><a class="dropdown-item" href="#" id="exportPdf">{{ t_db('general', 'pdf') }}</a></li>
                        </ul>
                    </div>

                    <button class="btn btn-primary">
                        <i class="bx bx-plus"></i> {{ t_db('general', 'add_new') }}
                    </button>
                </div>
            </div>

            <div class="card-body">
                <table id="modernTable" class="table align-middle">
                    <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>{{ t_db('general', 'code') }}</th>
                        <th>{{ t_db('general', 'name') }}</th>
                        <th>{{ t_db('general', 'is_active') }}</th>
                        <th>{{ t_db('general', 'is_default') }}</th>
                        <th>{{ t_db('general', 'actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($languages as $lang)
                        <tr>
                            <td><input type="checkbox" class="row-check"></td>
                            <td>
                                {{ $lang->code }}
                            </td>
                            <td>
                                {{ $lang->name }}
                            </td>
                            <td>
                                <span
                                    class="badge bg-label-{{ $lang->is_active ? 'success' : 'danger' }}">{{ $lang->is_active ? t_db('general', 'active') : t_db('general', 'inactive') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-label-primary">{{ t_db('general', 'current') }}</span>
                            </td>
                            <td class="d-flex align-items-center">
                                <div class="d-inline-block"><a href="javascript:;"
                                                               class="btn btn-icon dropdown-toggle hide-arrow"
                                                               data-bs-toggle="dropdown" aria-expanded="false"><i
                                            class="icon-base bx bx-dots-vertical-rounded"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end m-0" style="">
                                        <li><a href="javascript:;"
                                               class="dropdown-item">{{ t_db('general', 'details') }}</a></li>
                                        <li><a href="javascript:;"
                                               class="dropdown-item">{{ t_db('general', 'select_as_current') }}</a></li>
                                        <div class="dropdown-divider"></div>
                                        <li><a href="javascript:;"
                                               class="dropdown-item text-danger delete-record">{{ t_db('general', 'delete') }}</a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="javascript:;" class="btn btn-icon item-edit" title="{{t_db('general', 'translations')}}"><i class="icon-base bx bx-file icon-sm"></i></a>
                                <a href="javascript:;" class="btn btn-icon item-edit" title="{{t_db('general', 'edit')}}"><i class="icon-base bx bx-edit icon-sm"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('js-code')
    <script>
        $(function () {
            let table = $('#modernTable').DataTable({
                pageLength: 10,
                dom: 'rt<"d-flex justify-content-between mt-3"ip>',
                ordering: false,
                buttons: [
                    {
                        extend: 'copy',
                        className: 'd-none'
                    },
                    {
                        extend: 'csv',
                        className: 'd-none'
                    },
                    {
                        extend: 'excel',
                        className: 'd-none'
                    },
                    {
                        extend: 'pdf',
                        className: 'd-none'
                    }
                ]
            });

            $('#selectAll').on('click', function () {
                $('.row-check').prop('checked', this.checked);
            });

            $('#exportCopy').click(() => table.button('.buttons-copy').trigger());
            $('#exportCsv').click(() => table.button('.buttons-csv').trigger());
            $('#exportExcel').click(() => table.button('.buttons-excel').trigger());
            $('#exportPdf').click(() => table.button('.buttons-pdf').trigger());
        });
    </script>
@endpush
