$('#setAstucesMessageAsRead').click(function (e) {
   e.preventDefault();
   $.ajax({
      url: $(this).data('url'),
      method: 'GET'
   });
});

$('#modal-load-postAtip').click(function (e) {
    e.preventDefault();
    $('#postAtipContent').html(loadModalHTML);
    $.ajax({
       url: $(this).data('url'),
       method: 'GET'
    }).done(function (content) {
        $('#postAtipContent').html(content)
    })
});

$('.thankUserAstuces').click(function (e) {
    e.preventDefault();
    var that = this;
    $.ajax({
        url: $(this).data('url'),
        method: 'GET'
    }).done(function () {
        that.textContent = 'Remercié!';
        that.style = 'color:#536DFE;';
    });
});

$('.reportAstuce').click(function (e) {
    e.preventDefault();
    var that = this;
    $.ajax({
        url: $(this).data('url'),
        method: 'GET'
    }).done(function () {
        that.textContent = 'Signalé!';
        that.style = 'color:#536DFE;';
    });
});

$('.shareAstuce').click(function (e) {
    e.preventDefault();
    FB.ui({
        method: 'share',
        mobile_iframe: true,
        href: 'http://www.you.jobs' + $(this).data('url')
    }, function(response){});
});
