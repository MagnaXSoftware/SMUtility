String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

/**
 * Builds an HTML field from given object
 */
function buildHTMLForm(info, id) {
    if ((info.label == null && info.type != 'hidden') || info.name == null)
        return false;

    var html = $('<p>');
    var label, input;
    info.label = info.label.capitalize();
    switch (info.type) {
        case 'string':
            label = $('<label>', {
                'for': id + '_' + info.name
            }).html(info.label);
            input = $('<input>', {
                'type': 'text',
                'id': id + '_' + info.name,
                'name': id + '_' + info.name
            });
            if (info.disabled === true)
                input.attr('disabled', 'disabled');
            if (info.readonly === true)
                input.attr('readonly', 'readonly');
            if (info.maxlength != null)
                input.attr('maxlength', info.maxlength);
            if (info.value != null)
                input.attr('value', info.value);
            break;
        case 'integer':
        case 'int':
            label = $('<label>', {
                'for': id + '_' + info.name
            }).html(info.label);
            input = $('<input>', {
                'type': 'number',
                'id': id + '_' + info.name,
                'name': id + '_' + info.name
            });
            if (info.disabled === true)
                input.attr('disabled', 'disabled');
            if (info.readonly === true)
                input.attr('readonly', 'readonly');
            if (info.maxlength != null)
                input.attr('maxlength', info.maxlength);
            if (info.value != null)
                input.attr('value', info.value);
            break;
        case 'hidden':
            if (info.value == null)
                return false;
            input = $('<input>', {
                'type': 'hidden',
                'id': id + '_' + info.name,
                'name': id + '_' + info.name
            });
            break;
        case 'text':
            label = $('<label>', {
                'for': id + '_' + info.name
            }).html(info.label);
            input = $('<textarea>', {
                'id': id + '_' + info.name,
                'name': id + '_' + info.name
            });
            if (info.disabled === true)
                input.attr('disabled', 'disabled');
            if (info.readonly === true)
                input.attr('readonly', 'readonly');
            if (info.cols != null)
                input.attr('cols', info.cols);
            if (info.rows != null)
                input.attr('rows', info.rows);
            if (info.value != null)
                input.html(info.value);
            break;
        case 'radio':
            if (info.value == null)
                return false;
            label = $('<span>').html(info.label);
            input = $('<div>');
            $(info.value).each(function(i, e) {
                var opt = $('<input>', {
                    'type': 'radio',
                    'id': id + '_' + info.name + '[' + i + ']',
                    'name': id + '_' + info.name,
                    'value': e.value
                });
                if (e.disabled === true)
                    opt.attr('disabled', 'disabled');
                if (e.checked === true)
                    opt.attr('checked', 'checked');
                var optlabel = $('<label>', {
                    'for': id + '_' + info.name,
                    'class': 'radio'
                }).html(e.label);
                input.append('<br>').append(opt).append(optlabel);
            })
            input = input.html();
            break;
        case 'list':
        case 'select':
            if (info.value == null)
                return false;
            label = $('<label>', {
                'for': id + '_' + info.name
            }).html(info.label);
            input = $('<select>', {
                'id': id + '_' + info.name,
                'name': id + '_' + info.name
            });
            $(info.value).each(function (i, e) {
                var opt = $('<option>', {
                    'value': e.value
                }).html(e.label);
                if (e.disabled === true)
                    opt.attr('disabled', 'disabled');
                if (e.selected === true)
                    opt.attr('selected', 'selected');
                input.append(opt);
            })
            if (info.disabled === true)
                input.attr('disabled', 'disabled');
            if (info.size != null)
                input.attr('size', info.size);
            break;
        case 'check':
        case 'checkbox':
        case 'box':
            if (info.value == null)
                return false;
            label = $('<span>').html(info.label);
            input = $('<div>');
            $(info.value).each(function(i, e) {
                var opt = $('<input>', {
                    'type': 'checkbox',
                    'id': id + '_' + info.name + '[' + i + ']',
                    'name': id + '_' + info.name,
                    'value': e.value
                });
                if (e.disabled === true)
                    opt.attr('disabled', 'disabled');
                if (e.checked === true)
                    opt.attr('checked', 'checked');
                var optlabel = $('<label>', {
                    'for': id + '_' + info.name,
                    'class': 'radio'
                }).html(e.label);
                input.append('<br>').append(opt).append(optlabel);
            })
            input = input.html();
            break;
        default:
            return false;
    }
    return html.append(label).append(input);
}
