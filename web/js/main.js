var loadModalHTML = '<h4 class="modal-title" style="padding:15px;color:#3F51B5;">Chargement en cours...</h4>';

function getProperURLForAnnonceThankAction(obj, classSelector)
{
    var dataThank, dataUnthank = undefined;
    var data = [];
    if(classSelector === 'thankUser') {
        dataThank = $(obj).data('url');
        dataUnthank = $(obj).data('unthank');
    } else {
        dataThank = $(obj).data('thank');
        dataUnthank = $(obj).data('url');
    }
    if($(obj).hasClass('unThankUser')) {
        data['url'] = dataUnthank;
        data['unThank'] = true;
    } else {
        data['url'] = dataThank;
        data['unThank'] = false;
    }
    return data;
}

function getProperUrlForAnnonceReportAction(obj, classSelector)
{
    var dataReport, dataUnreport = undefined;
    var data = [];
    if(classSelector === 'reportAdvert') {
        dataReport = $(obj).data('url');
        dataUnreport = $(obj).data('unreport');
    } else {
        dataReport = $(obj).data('report');
        dataUnreport = $(obj).data('url');
    }
    if($(obj).hasClass('unReportAdvert')) {
        data['url'] = dataUnreport;
        data['unReportAdvert'] = true;
    } else {
        data['url'] = dataReport;
        data['unReportAdvert'] = false;
    }
    return data;
}

function getProperURLForAstuceThankAction(obj, classSelector)
{
    var dataThank, dataUnthank = undefined;
    var data = [];
    if(classSelector === 'thankUserAstuce') {
        dataThank = $(obj).data('url');
        dataUnthank = $(obj).data('unthank');
    } else {
        dataThank = $(obj).data('thank');
        dataUnthank = $(obj).data('url');
    }
    if($(obj).hasClass('unThankUserAstuce')) {
        data['url'] = dataUnthank;
        data['unThank'] = true;
    } else {
        data['url'] = dataThank;
        data['unThank'] = false;
    }
    return data;
}

function getProperUrlForAstuceReportAction(obj, classSelector)
{
    var dataReport, dataUnreport = undefined;
    var data = [];
    if(classSelector === 'reportAstuce') {
        dataReport = $(obj).data('url');
        dataUnreport = $(obj).data('unreport');
    } else {
        dataReport = $(obj).data('report');
        dataUnreport = $(obj).data('url');
    }
    if($(obj).hasClass('unReportAstuce')) {
        data['url'] = dataUnreport;
        data['unReportAstuce'] = true;
    } else {
        data['url'] = dataReport;
        data['unReportAstuce'] = false;
    }
    return data;
}

function getProperUrlForAstuceBookmarkAction(obj, classSelector)
{
    var dataBookmark, dataUnbookmark = undefined;
    var data = [];
    if(classSelector === 'bookmarkAstuce') {
        dataBookmark = $(obj).data('url');
        dataUnbookmark = $(obj).data('unbookmarkastuce');
    } else {
        dataBookmark = $(obj).data('bookmarkastuce');
        dataUnbookmark = $(obj).data('url');
    }
    if($(obj).hasClass('unBookmarkAstuce')) {
        data['url'] = dataUnbookmark;
        data['unBookmarkAstuce'] = true;
    } else {
        data['url'] = dataBookmark;
        data['unBookmarkAstuce'] = false;
    }
    return data;
}
