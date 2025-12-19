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

                    <a href="{{ route('languages.create') }}">
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
                                @if($lang->is_default)
                                    <span class="badge bg-label-primary">{{ t_db('general', 'current') }}</span>
                                @endif
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
                                <a href="javascript:;" class="btn btn-icon item-edit"
                                   title="{{t_db('general', 'translations')}}"><i
                                        class="icon-base bx bx-file icon-sm"></i></a>
                                <a href="javascript:;" class="btn btn-icon item-edit"
                                   title="{{t_db('general', 'edit')}}"><i class="icon-base bx bx-edit icon-sm"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div
            class="modal-onboarding modal fade animate__animated"
            id="onboardHorizontalImageModal"
            tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content text-center">
                    <div class="modal-header border-0">
                        <a class="text-body-secondary close-label" href="javascript:void(0);" data-bs-dismiss="modal"
                        >Skip Intro</a
                        >
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body onboarding-horizontal p-0">
                        <div class="onboarding-media">
                            <img
                                src="../../assets/img/illustrations/boy-verify-email-light.png"
                                alt="boy-verify-email-light"
                                width="273"
                                class="img-fluid"
                                data-app-light-img="illustrations/boy-verify-email-light.png"
                                data-app-dark-img="illustrations/boy-verify-email-dark.png"/>
                        </div>
                        <div class="onboarding-content mb-0">
                            <h4 class="onboarding-title text-body">Example Request Information</h4>
                            <div class="onboarding-info">
                                In this example you can see a form where you can request some additional information
                                from the
                                customer when they land on the app page.
                            </div>
                            <form>
                                <div class="row g-6">
                                    <div class="col-sm-6">
                                        <div class="mb-4">
                                            <label for="nameEx7" class="form-label">Your Full Name</label>
                                            <input
                                                class="form-control"
                                                placeholder="Enter your full name..."
                                                type="text"
                                                value=""
                                                tabindex="0"
                                                id="nameEx7"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-4">
                                            <label for="roleEx7" class="form-label">Your Role</label>
                                            <select class="form-select" tabindex="0" id="roleEx7">
                                                <option>Web Developer</option>
                                                <option>Business Owner</option>
                                                <option>Other</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js-code')
    <script>
        $(function () {
            let table = $('#languages').DataTable({
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
