$('#modal-load-answerMessage').click(function (e) {
    e.preventDefault();
    $('#answerMessageContent').html(loadModalHTML);
    $.ajax({
        url: $(this).data('url'),
        method: 'GET'
    }).done(function (content) {
        $('#answerMessageContent').html(content);
    })
});
