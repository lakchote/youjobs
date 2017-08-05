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

$('.modal-load-postAcomment').click(function (e) {
    e.preventDefault();
    $('#postAcommentContent').html(loadModalHTML);
    $.ajax({
        url: $(this).data('url'),
        method: 'GET'
    }).done(function (content) {
        $('#postAcommentContent').html(content)
    })
});

$('.thankUserAstuce').click(function (e) {
    e.preventDefault();
    var that = this;
    var data = getProperURLForAstuceThankAction(this, 'thankUserAstuce');
    $.ajax({
        url: data['url'],
        method: 'GET'
    }).done(function () {
        (data['unThank'] === false) ?
            $(that).removeClass('thankUserAstuce').addClass('unThankUserAstuce')
            :
            $(that).removeClass('unThankUserAstuce').addClass('thankUserAstuce');
    });
});

$('.unThankUserAstuce').click(function (e) {
    e.preventDefault();
    var that = this;
    var data = getProperURLForAstuceThankAction(this, 'unThankUserAstuce');
    $.ajax({
        url: data['url'],
        method: 'GET'
    }).done(function () {
        (data['unThank'] === false) ?
            $(that).removeClass('thankUserAstuce').addClass('unThankUserAstuce')
            :
            $(that).removeClass('unThankUserAstuce').addClass('thankUserAstuce');
    });
});

$('.reportAstuce').click(function (e) {
    e.preventDefault();
    var that = this;
    var data = getProperUrlForAstuceReportAction(this, 'reportAstuce');
    $.ajax({
        url: data['url'],
        method: 'GET'
    }).done(function () {
        (data['unReportAstuce'] === false) ?
            $(that).removeClass('reportAstuce').addClass('unReportAstuce')
            :
            $(that).removeClass('unReportAstuce').addClass('reportAstuce');
    });
});

$('.unReportAstuce').click(function (e) {
    e.preventDefault();
    var that = this;
    var data = getProperUrlForAstuceReportAction(this, 'unReportAstuce');
    $.ajax({
        url: data['url'],
        method: 'GET'
    }).done(function () {
        (data['unReportAstuce'] === false) ?
            $(that).removeClass('reportAstuce').addClass('unReportAstuce')
            :
            $(that).removeClass('unReportAstuce').addClass('reportAstuce');
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

$('.bookmarkAstuce').click(function (e) {
    e.preventDefault();
    var that = this;
    var data = getProperUrlForAstuceBookmarkAction(this, 'bookmarkAstuce');
    $.ajax({
        url: data['url'],
        method: 'GET'
    }).done(function () {
        (data['unBookmarkAstuce'] === false) ?
            $(that).removeClass('bookmarkAstuce').addClass('unBookmarkAstuce')
            :
            $(that).removeClass('unBookmarkAstuce').addClass('bookmarkAstuce');
    });
});

$('.unBookmarkAstuce').click(function (e) {
    e.preventDefault();
    var that = this;
    var data = getProperUrlForAstuceBookmarkAction(this, 'unBookmarkAstuce');
    $.ajax({
        url: data['url'],
        method: 'GET'
    }).done(function () {
        (data['unBookmarkAstuce'] === false) ?
            $(that).removeClass('bookmarkAstuce').addClass('unBookmarkAstuce')
            :
            $(that).removeClass('unBookmarkAstuce').addClass('bookmarkAstuce');
    });
});
