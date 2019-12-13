$(document).ready(function () {
    $('#btn-expire-all').click(function(e) {
        $.ajax({
            type: 'POST',
            url: 'api/expire_all_gifts.php',
            success: function (data) {
                alert(data.message);
            },
            error: function (xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });
    $("#reset-daily-form").submit(function( event ) {
        const formData = new FormData(document.getElementById("reset-daily-form"));
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'api/reset_daily_limit.php',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                alert(data.message);
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });
});