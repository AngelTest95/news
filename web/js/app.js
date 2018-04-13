var APP = {
    /**
     *  Helper function to create a custom confirmation dialog, returns a promise object which can be further processed.
     *
     * @param text Text of the confirmation (can be html)
     * @param title Title of the confirmation dialog
     * @param yesText Localised "yes" text
     * @param noText Localised "no" text
     * @returns {object} Promise
     */
    confirm: function(text, title, yesText, noText, successCallback, failCallback) {
        var deferred = new $.Deferred(),
            prompt = $("<div class='modal fade'></div>"),
            dialog = $("<div class='modal-dialog'></div>"),
            content = $("<div class='modal-content'></div>"),
            body = "<div class='modal-body'>" + text + "</div>",
            header = "<div class='modal-header'><h4 class='modal-title'>" + (title || 'Please confirm') + "</h4></div>",
            footer = $("<div class='modal-footer'/>"),
            buttons = $(); // array for buttons

        prompt.bind('hidden.bs.modal', function() {
            prompt.remove();
        });

        prompt.on('shown.bs.modal', function() {
            prompt.find('.btn-primary').focus();
        });

        buttons = buttons.add($("<button/>", {
            'class': 'btn btn-default',
            'id': 'modal-cancel',
            'attr': {'data-dismiss': 'modal'},
            'text': (noText || 'Cancel'),
            click: function() {
                if (failCallback) {
                    failCallback();
                }
                deferred.reject();
            }
        }));
        buttons = buttons.add($("<button/>", {
            'class': 'btn btn-primary',
            'id': 'modal-confirm',
            'text': (yesText || 'Confirm'),
            click: function() {
                if (successCallback) {
                    successCallback();
                }
                deferred.resolve();
                prompt.modal('hide');
            }
        }));
        content.append(header);
        content.append(body);
        content.append(footer.append(buttons));
        dialog.html(content);
        prompt.html(dialog);

        prompt.modal('show');

        return deferred.promise();
    },
};