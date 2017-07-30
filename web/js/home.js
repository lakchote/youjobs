$('#modal-load-postAdvert').click(function (e) {
    e.preventDefault();
    $('#postAdvertContent').html(loadModalHTML);
    $.ajax({
        url: $(this).data('url'),
        method: 'GET'
    }).done(function (content) {
        $('#postAdvertContent').html(content);
        initLocalisationAutocomplete();
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
    var data = getProperURLForAnnonceThankAction(this, 'thankUser');
    $.ajax({
        url: data['url'],
        method: 'GET'
    }).done(function () {
        (data['unThank'] === false) ?
            $(that).removeClass('thankUser').addClass('unThankUser')
            :
            $(that).removeClass('unThankUser').addClass('thankUser');
    });
});

$('.unThankUser').click(function (e) {
    e.preventDefault();
    var that = this;
    var data = getProperURLForAnnonceThankAction(this, 'unThankUser');
    $.ajax({
        url: data['url'],
        method: 'GET'
    }).done(function () {
        (data['unThank'] === false) ?
            $(that).removeClass('thankUser').addClass('unThankUser')
            :
            $(that).removeClass('unThankUser').addClass('thankUser');
    });
});

$('.reportAdvert').click(function (e) {
    e.preventDefault();
    var that = this;
    var data = getProperUrlForAnnonceReportAction(this, 'reportAdvert');
    $.ajax({
        url: data['url'],
        method: 'GET'
    }).done(function () {
        (data['unReportAdvert'] === false) ?
            $(that).removeClass('reportAdvert').addClass('unReportAdvert')
            :
            $(that).removeClass('unReportAdvert').addClass('reportAdvert');
    });
});

$('.unReportAdvert').click(function (e) {
    e.preventDefault();
    var that = this;
    var data = getProperUrlForAnnonceReportAction(this, 'unReportAdvert');
    $.ajax({
        url: data['url'],
        method: 'GET'
    }).done(function () {
        (data['unReportAdvert'] === false) ?
            $(that).removeClass('reportAdvert').addClass('unReportAdvert')
            :
            $(that).removeClass('unReportAdvert').addClass('reportAdvert');
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

$('#setAnnoncesMessageAsRead').click(function (e) {
    e.preventDefault();
    $.ajax({
        url: $(this).data('url'),
        method: 'GET'
    });
});

function initLocalisationAutocomplete()
{
    var localisation = new google.maps.places.Autocomplete(document.getElementById('annonce_form_localisation'), {types: ['(cities)'], componentRestrictions: {'country': 'fr'}});
    localisation.addListener('place_changed', function () {
       var place = localisation.getPlace();
        document.getElementById('annonce_form_localisation').value = place.address_components[0].short_name;
    });
}
