$(document).ready(function() {
    $('a#home').click(function() {
        loadPage(buildScriptListPage);
    }).attr('href', '#');
    $('a#sys_info').click(function() {
        loadPage(buildInfoPage, {'info':true, 'script':'core'});
        window.scriptInfo = {'name': 'SMUtility', 'ID': 'core'}
    }).attr('href', '#info&script=core');
    loadPage(buildScriptListPage);
})

window.scriptInfo = {
    'name': '',
    'ID': ''
}

function loadPage(callback, params) {
    showLoader();
    if (!params) {
        params = {};
    }
    $.getJSON('json.php', params).success(callback).error(showError);
}

function showLoader() {
    document.title = 'Loading :: SMUtility';
    $('h1#branding').text('Loading');
    $('div#content').slideUp();
    $('div#options').slideUp();
    $('div#results').slideUp();
    $('div#loader').slideDown();
    
    $('div#content').text('');
    $('div#options').text('');
    $('div#results').text('');
}

function hideLoader(pageTitle) {
    document.title = pageTitle + ' :: SMUtility';
    $('h1#branding').text(pageTitle);
    $('div#loader').slideUp();
    $('div#content').slideDown();
}

function showError(jqXHR, errorName) {
    document.title = 'Error :: SMUtility';
    $('h1#branding').text('Error');
    
    if (errorName == 'parsererror') {
        $('div#content').append($('<p>').text('We could not parse the results. Here is what we got:'));
    } else {
        $('div#content').append($('<p>').text('There was an error loading your request. Here are the details: '))
    }
    $('div#content').append($('<pre>').text(jqXHR.responseText))
    
    hideLoader('Error');
}

function buildScriptListPage(json) {
    var ul = $('div#content').append('<ul>').find('ul');
    $(json).each(function(index, element) {
        var li = $('<li>')
            .append(function() {return $('<a>', {
                    "href": '#script=' + element.ID
                })
                .click(function() {loadPage(buildScriptPage, {'script':element.ID});window.scriptInfo = {'name': element.name, 'ID': element.ID};})
                .text(element.name)
            })
            .append(" - ")
            .append(function() {return $('<a>', {
                    "href": '#info&script=' + element.ID
                }).click(function() {loadPage(buildInfoPage, {'info':true,'script':element.ID});window.scriptInfo = {'name': element.name, 'ID': element.ID}})
                .text("Info")
            })
        ul.append(li)
    });
    
    hideLoader('Script List');
}

function buildInfoPage(json) {
    scriptName = json['name'];
    var dl = $('div#content').append('<dl>').find('dl');
    dl.append($('<dt>').text('ID'));
    dl.append($('<dd>').text(json['ID']));
    delete json['ID'];
    dl.append($('<dt>').text('Name'));
    dl.append($('<dd>').text(json['name']));
    delete json['name'];
    dl.append($('<dt>').text('Description'));
    dl.append($('<dd>').text(json['description']));
    delete json['description'];
    dl.append($('<dt>').text('Version'));
    dl.append($('<dd>').text(json['version']));
    delete json['version'];
    dl.append($('<dt>').text('Author'));
    dl.append($('<dd>').text(json['author']));
    delete json['author'];
    dl.append($('<dt>').text('Category'));
    dl.append($('<dd>').text(json['category']));
    delete json['category'];
    for (prop in json) {
        if (!json.hasOwnProperty(prop)) {
            continue;
        }
        dl.append($('<dt>').text(prop))
        dl.append($('<dd>').text(json[prop]))
    }
    
    hideLoader(scriptName + ' Info')
}
function buildScriptPage(json) {
    var form = $('div#content').append($('<form>', {
        'id': 'form_' + window.scriptInfo.ID,
        'action': 'html.php?do&script=' + window.scriptInfo.ID,
        'method': 'post',
        'class': 'prefix_2 grid_8 suffix_2 alpha omega block'
    })).find('form');
    var ffs = form.append($('<fieldset>', {
        'id': 'fieldset_' + window.scriptInfo.ID
    })).find('fieldset#fieldset_'+window.scriptInfo.ID).append($('<legend>').text('Configuration Options'));
    $(json).each(function(index, element) {
        ffs.append(buildHTMLForm(element, window.scriptInfo.ID));
    })
    form.append($('<input>', {
        'type': 'reset',
        'value': 'Reset'
    }))
    .append($('<input>', {
        'type': 'submit',
        'value': 'Submit',
        'name': 'submitbtn',
        'id': 'submitbtn'
    }));
    form.submit(function() {
        document.title = 'Loading :: SMUtility';
        $('h1#branding').text('Loading');
        $('div#loader').slideDown();
        window.scriptConfig = $('form#form_' + window.scriptInfo.ID).serializeArray();
        $.ajax({
            type: 'POST',
            url: 'json.php?do&script=' + window.scriptInfo.ID,
            data: window.scriptConfig,
            success: buildResults,
            dataType: 'json'
        });
        return false;
    });
    hideLoader(window.scriptInfo.name + ' Configuration')
}

function buildResults(json) {
    console.log(json);
    $('div#options').text('');
    $('div#results').text('');
    var opts = $('div#options')
    .append($('<div>', {'class': 'box'})).find('div')
    .append($('<h2>').text('Configuration Options'))
    .append($('<div>', {'class':'block'})).find('div')
    .append('<dl>').find('dl');
    $(window.scriptConfig).each(function (i, e) {
        opts.append($('<dt>').text(e.name.replace(window.scriptInfo.ID + '_', '')))
        opts.append($('<dd>').text(e.value))
    })
    var results = $('div#results')
    .append($('<div>', {'class': 'box'})).find('div')
    .append($('<h2>').text('Results'))
    .append($('<div>', {'class':'block'})).find('div')
    .append('<dl>').find('dl');
    $(json).each(function (i, e) {
        results.append($('<dt>').text(e.label))
        results.append($('<dd>').text(e.value))
    })
    
    document.title = window.scriptInfo.name + ' :: SMUtility';
    $('h1#branding').text(window.scriptInfo.name);
    $('div#loader').slideUp();
    $('div#content').slideUp();
    $('div#options').slideDown();
    $('div#results').slideDown();
}
