@extends('admin-dashboard.layouts.admin-master')
@section('main-content')
    <div class="container-xxl flex-grow-1 container-p-y">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">{{ t_db('language', 'translations') }}</h3>
                            <a href="{{ route('languages.index') }}" class="btn btn-outline-danger">
                                <span>
                                    <i class="bx bx-left-arrow-alt"></i>
                                </span>
                                {{ t_db('general', 'back') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="saveAllForm">
                            @csrf
                            <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ t_db('general', 'code') }}</th>
                                    <th>{{ t_db('generat', 'key') }}</th>
                                    <th>{{ t_db('general', 'value') }}</th>
                                    <th>{{ t_db('general', 'operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($translations as $translation)
                                    <tr>
                                        <td>{{ $translation->language->code }}</td>
                                        <td>{{ $translation->key }}</td>
                                        <td>
                                            <input type="text" required
                                            class="form-control"
                                            name="translations[{{ $translation->uuid }}]"
                                            value="{{ $translation->value }}">
                                        </td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-primary save-translation"
                                                    data-id="{{ $translation->uuid }}">
                                                {{ t_db('general', 'save') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4">
                                        <div class="d-flex justify-content-end">
                                            <button type="button" id="saveAllBtn" class="btn btn-primary">
                                                {{ t_db('general', 'save_all') }}
                                            </button> 
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        {{ $translations->links('pagination::bootstrap-5') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        </form>
                    </div>
                </div>
    </div>
@endsection

@section('js-code')
<script>
    $(document).ready(function () {
        $('.save-translation').click(function () {
            let translationId = $(this).data('id');
            let value = $(this).closest('tr').find('input').val();
            $.ajax({
                url: '/translations/' + translationId,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    value: value
                },
                success: function (response) {
                    if (response.success) {
                        swal.fire({
                            title: "{{ t_db('general', 'success') }}",
                            text: response.message,
                            icon: 'success'
                        });
                    } else {
                        swal.fire({
                            title: "{{ t_db('general', 'error') }}",
                            text: response.message,
                            icon: 'error'
                        });
                    }
                }
            });
        });

        $('#saveAllBtn').on('click', function () {
            const language = "{{ $language->uuid }}";
            $.ajax({
                url: "/translations/update-all/" + language,
                method: "POST",
                data: $('#saveAllForm').serialize(),
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: "{{ t_db('general','success') }}",
                        text: response.message
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: "{{ t_db('general','error') }}",
                        text: xhr.responseJSON?.message ?? 'Error'
                    });
                }
            });
        });
    });
</script>
@endsection