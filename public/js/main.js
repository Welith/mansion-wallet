$(document).ready(function () {
    $('#createWallet').on('click', function () {
        $('#walletName').val('');
    });
    $('#nameConfirm').on('click', function () {
        $('#error').remove();
        let wallet_name_input = $('#walletName'), button = $(this), url = button.data('url');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: url,
            method: 'post',
            data:{
                name: wallet_name_input.val()
            },
            success: function (data) {
                console.log(data);
                if (data['success']) {
                    $('#walletName').remove();
                    button.remove();
                    $('.main-box').append("<div class='container' id='success'><span class='alert-success'>" + data['success'] +  " Redirecting... </span></div>");
                    setTimeout(function(){ location.href = '/wallet' }, 3000);
                } else {
                    $('.main-box').append("<div class='container' id='error'><span class='alert-danger'>"+ data['error'] + "</span></div>");
                }
            }
        });
    });
});
