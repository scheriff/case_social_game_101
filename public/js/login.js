$(document).ready(function () {
    $("#login-form").submit(function( event ) {
        const formData = new FormData(document.getElementById("login-form"));
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'api/login.php',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if(data.redirect) {
                    window.location.href = data.redirect;
                } else if(data.message) {
                    alert(data.message);
                } else {
                    alert('Unknown error occurred');
                }
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            }
        });
    });
});