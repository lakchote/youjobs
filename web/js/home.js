$('#modal-load-postAdvert').click(function (e) {
    e.preventDefault();
    $('#postAdvertContent').html(loadModalHTML);
    $.ajax({
        url: $(this).data('url'),
        method: 'GET'
    }).done(function (content) {
        $('#postAdvertContent').html(content);
    });
});
