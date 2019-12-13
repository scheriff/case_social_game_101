$(document).ready(function () {
    $('#btn-members, #btn-admin').click(function(e) {
        window.location.href = $(this).data('dest');
    });
    $('#btn-logout').click(function(e) {
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

    $('.send-gift').click(function(e) {
        const $this = $(this);
        const $selection = $this.parent().siblings('th').children('select');
        const gift = $selection.val();
        if(gift == 0) {
            alert('You need to select a gift first!');
            return false;
        }
        const username = $this.parents('.user-row').attr('data-username');

        $.ajax({
            type: 'POST',
            url: 'api/send_gift.php',
            beforeSend: function(xhr) {
                $this.text('Sending...');
            },
            data: { 'receiver': username, 'gift': gift },
            success: function(data) {
                $this.text(data.message);
            },
            error: function(xhr) {
                $this.text(xhr.responseJSON.message);
            },
            complete: function() {
                $selection.val(0);
            }
        });
    });

    $('.claim-gift').click(function(e) {
        const $this = $(this);
        const giftTransactionId = $this.parents('.gift-row').attr('data-id');
        $.ajax({
            type: 'POST',
            url: 'api/claim_gift.php',
            beforeSend: function(xhr) {
                $this.text('Claiming...');
            },
            data: { 'giftTransactionId': giftTransactionId },
            success: function(data) {
                $this.text(data.message);
            },
            error: function(xhr) {
                $this.text(xhr.responseJSON.message);
            }
        });
    })
});