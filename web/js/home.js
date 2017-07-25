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

$('.loadAdvertFullText').click(function (e) {
    e.preventDefault();
    this.innerHTML = '<span style="color:#536DFE;">Chargement...</span>';
    var that = this;
    $.ajax({
        url: $(this).data('url'),
        method: 'GET'
    }).done(function (fullText) {
        that.parentNode.innerText = fullText;
    });
});

$('.thankUser').click(function (e) {
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

$('.reportAdvert').click(function (e) {
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

$('.shareAdvert').click(function (e) {
   e.preventDefault();
    FB.ui({
        method: 'share',
        mobile_iframe: true,
        href: 'http://www.you.jobs' + $(this).data('url')
    }, function(response){});
});
