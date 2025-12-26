@extends('admin-dashboard.layouts.admin-master')
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">{{ t_db('general', 'languages') }}</h3>
                <div class="d-flex gap-2">
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
                            #
                        </th>
                        <th>{{ t_db('general', 'code') }}</th>
                        <th>{{ t_db('general', 'name') }}</th>
                        <th>{{ t_db('general', 'is_active') }}</th>
                        <th>{{ t_db('general', 'is_default') }}</th>
                        <th>{{ t_db('general', 'actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($languages as $lang)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                            </td>
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
                                    <ul class="dropdown-menu dropdown-menu-end m-0">
                                        <li><a href="javascript:;"
                                               class="dropdown-item select-as-current"
                                               data-url="{{ route('languages.set-default', $lang) }}"
                                               data-id="{{ $lang->id }}"
                                               data-code="{{ $lang->code }}"
                                               data-name="{{ $lang->name }}">{{ t_db('general', 'select_as_current') }}</a></li>
                                        <div class="dropdown-divider"></div>
                                        <li><a href="javascript:;"
                                               class="dropdown-item text-danger delete-record" data-uuid="{{ $lang->uuid }}">{{ t_db('general', 'delete') }}</a>
                                        </li>
                                    </ul>
                                </div>
                                <a href="{{ route('languages.translations', $lang->uuid) }}" class="btn btn-icon item-edit"
                                   title="{{t_db('general', 'translations')}}"><i
                                        class="icon-base bx bx-file icon-sm"></i></a>
                                <a href="{{ route('languages.edit', $lang->uuid) }}" class="btn btn-icon item-edit"
                                   title="{{t_db('general', 'edit')}}"><i class="icon-base bx bx-edit icon-sm"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                {{ t_db('general', 'language_not_added') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6">
                            {{ $languages->links('pagination::bootstrap-5') }}
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
    <script>
            $(document).on('click', '.select-as-current', function (e) {
                e.preventDefault();
                const $el = $(this);
                const langName = ($el.data('name') || '').toString();
                const url = $el.data('url');

                Swal.fire({
                    title: "{{ t_db('general','are_you_sure_set_as_default?') }}",
                    text: langName ? `Language: ${langName}` : '',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "{{ t_db('general','yes_set_as_default') }}"
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $.post(url, { _token: "{{ csrf_token() }}" })
                        .done(() => {
                            $('#languages tbody tr td:nth-child(5) .badge.bg-label-primary').remove();
                            const $cell = $el.closest('tr').find('td:nth-child(5)');
                            $cell.empty().append(`<span class="badge bg-label-primary">{{ t_db('general','current') }}</span>`);
                            Swal.fire("{{ t_db('general', 'success_set_as_default') }}", "", "success");
                        })
                        .fail((xhr) => {
                            const msg = xhr?.responseJSON?.message || 'Request failed';
                            Swal.fire({ title: 'Error', text: msg, icon: 'error' });
                        });
                });
            });

            $(document).on('click', '.delete-record', function (e) {
                e.preventDefault();

                const $el = $(this);
                const uid = ($el.data('uuid') || '').toString();

                if (!uid) return;

                const url = "{{ route('languages.destroy', ':uid') }}".replace(':uid', uid);

                Swal.fire({
                    title: "{{ t_db('general','are_you_sure_want_to_delete_this_language?') }}",
                    text: "{{ t_db('general','note_that_when_deleting_a_language_its_translations_will_also_be_deleted') }}",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "{{ t_db('general','delete') }}"
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: { _token: "{{ csrf_token() }}", _method: 'DELETE' },
                        success: function(response) {
                            $el.closest('tr').remove();

                            Swal.fire(
                                "{{ t_db('general','language_deleted_successfully') }}",
                                "",
                                "success"
                            );
                        },
                        error: function(xhr) {
                            const msg = xhr?.responseJSON?.message || "{{ t_db('general','something_went_wrong') }}";
                            Swal.fire({
                                title: "{{ t_db('general','error') }}",
                                text: msg,
                                icon: "error"
                            });
                        }
                    });
                });
            });

    </script>
@endsection
