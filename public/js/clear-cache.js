$(document).on('click', '.clear-cache', function () {
    let btn = $(this);
    let spinner = btn.find('.spinner-border');
    let btnText = btn.find('.btn-text');

    Swal.fire({
        title: "{{ t_db('general','are_you_sure') }}",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "{{ t_db('general','yes') }}",
        cancelButtonText: "{{ t_db('general','cancel') }}"
    }).then((result) => {
        if (result.isConfirmed) {
            spinner.removeClass('d-none');
            btn.prop('disabled', true);
            btnText.text("{{ t_db('general','processing') }}");

            $.ajax({
                url: "{{ route('clear-cache') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}" },
                success: function (response) {
                    Swal.fire({
                        title: response.message ?? "{{ t_db('general','success') }}",
                        icon: "success"
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        title: "{{ t_db('general','error') }}",
                        text: xhr?.responseJSON?.message ?? "{{ t_db('general','something_went_wrong') }}",
                        icon: "error"
                    });
                },
                complete: function() {
                    spinner.addClass('d-none');
                    btn.prop('disabled', false);
                    btnText.text("{{ t_db('general', 'clear_cache') }}");
                }
            });
        }
    });
});
