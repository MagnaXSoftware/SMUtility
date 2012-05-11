$(document).ready(function() {
    $('a#home').click(function() {
        loadPage(buildScriptListPage);
    }).attr('href', '#');
    $('a#sys_info').click(function() {
        loadPage(buildInfoPage, {'info':true, 'script':'core'});
    }).attr('href', '#info&script=core');
    loadPage(buildScriptListPage);
})

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
    $('div#loader').slideDown();
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
    
    $('div#content').text('')
    if (errorName == 'parsererror') {
        $('div#content').append($('<p>').text('We could not parse the results. Here is what we got:'));
    } else {
        $('div#content').append($('<p>').text('There was an error loading your request. Here are the details: '))
    }
    $('div#content').append($('<pre>').text(jqXHR.responseText))
    
    hideLoader('Error');
}

function buildScriptListPage(json) {
    $('div#content').text('');
    var ul = $('div#content').append('<ul>').find('ul');
    $(json).each(function(index, element) {
        var li = $('<li>')
            .append(function() {return $('<a>', {
                    "href": '#script=' + element.ID
                })
                .click(function() {loadPage(buildScriptPage, {'script':element.ID})})
                .text(element.name)
            })
            .append(" - ")
            .append(function() {return $('<a>', {
                    "href": '#info&script=' + element.ID
                }).click(function() {loadPage(buildInfoPage, {'info':true,'script':element.ID})})
                .text("Info")
            })
        ul.append(li)
    });
    
    hideLoader('Script List');
}

function buildInfoPage(json) {
    scriptName = json['name'];
    $('div#content').text('');
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
    console.log(json);
    
    $('div#content').text('');
    var form = $('div#content').append('<form>', {
        'id': 'form_' + json.ID,
        'action': 'html.php?do&amp;script=' + json.ID,
        'method': 'post',
        'class': 'prefix_2 grid_8 suffix_2 alpha omega block'
    }).find('form');
    var ffs = form.append('<fieldset>', {
        'id': 'fieldset_' + json.ID
    }).find('fieldset#fieldset_'+json.ID).append($('<legend>').text('Configuration Options'));
    $(json.form).each(function(index, element) {
        ffs.append()
    })
    hideLoader(json.name + ' Configuration')
}