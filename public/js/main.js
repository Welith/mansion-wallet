$(document).ready(function () {
    $('#createWallet').on('click', function () {
        $('#walletName').val('');
    });

    /**
     * Create user wallet ajax
     */
    $('#nameConfirm').on('click', function () {
        $('#error').remove();
        let wallet_name_input = $('#walletName'), button = $(this), url = button.data('url'), redirect_url = button.data('redirect');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: url,
            method: 'post',
            data: {
                name: wallet_name_input.val()
            },
            success: function (data) {
                if (data['success']) {
                    wallet_name_input.remove();
                    button.remove();
                    $('.main-box').append("<div class='container' id='success'><span class='alert-success'>" + data['success'] + ". Redirecting to dashboard... </span></div>");
                    setTimeout(function () {
                        location.href = redirect_url
                    }, 3000);
                }
            },
            error: function (response) {
                var errors = response.responseJSON.errors;
                $.each( errors, function( key, value ) {
                    $('.main-box').append("<div class='container' id='error'><span class='alert-danger'>" + value[0] + "</span></div>");
                });
            }
        });
    });

    $(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
        $(".alert-success").slideUp(500);
    });
});
