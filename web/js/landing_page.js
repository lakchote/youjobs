$(function() {
    $('.modal-load__register').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).data('url'),
            method: 'GET'
        }).done(function (content) {
            $('#registerContent').html(content);
        });
    });
    $('.modal-load__connect').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).data('url'),
            method: 'GET'
        }).done(function (content) {
            $('#connectContent').html(content);
        });
    });
});
