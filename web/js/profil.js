$('#modal-load-sendMessage').click(function (e) {
    e.preventDefault();
    $('#sendMessageContent').html(loadModalHTML);
    $.ajax({
        url: $(this).data('url'),
        method: 'GET'
    }).done(function (content) {
        $('#sendMessageContent').html(content)
    })
});
