$('#add-news-button').click(function () {
    $.LoadingOverlay("show");
    var url = this.getAttribute('data-url');
    $.get(url, function(response) {
        $('#add-news-dialog').html(response).modal('show');
    }).always(function() {
        $.LoadingOverlay("hide");
    });
});