$(document).ready(function () {
    $('#btn-members, #btn-admin').click(function (e) {
        window.location.href = $(this).data('dest');
    });
    $('#btn-logout').click(function (e) {
        $.ajax({
            type: 'POST',
            url: 'api/logout.php',
            success: function (data) {
                window.location.href = data.redirect;
            },
            error: function (xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });
});