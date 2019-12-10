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
                    $('.main-box').append("<div class='container alert-danger' id='error'><span class='alert-danger'>" + value[0] + "</span></div>");
                });
            }
        });
    });
    /**
     * Edit user wallet ajax
     */
    $('#nameEdit').on('click', function () {
        $('#error').remove();
        let wallet_name_input = $('#newWalletName'), button = $(this), url = button.data('url'), redirect_url = button.data('redirect');
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
                    $('.main-box').before("<div class='container mb-3 text-center alert-success' id='success'><span class='alert-success'>" + data['success'] + ". Redirecting to dashboard... </span></div>");
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

    $(".alert-dismissible").fadeTo(3000, 500).slideUp(500, function(){
        $(".alert-dismissible").slideUp(500);
    });
});
