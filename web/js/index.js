$.LoadingOverlaySetup({
    color           : "rgba(0, 0, 0, 0.4)",
    maxSize         : "80px",
    minSize         : "20px",
    resizeInterval  : 0,
    size            : "50%"
});

$('.dialog-button').click(function () {
    $.LoadingOverlay("show");
    var url = this.getAttribute('data-url');
    $.get(url, function(response) {
        $('#main-dialog').html(response).modal('show');
    }).always(function() {
        $.LoadingOverlay("hide");
    });
});

$('.delete-news-button').click(function () {
    var url = this.getAttribute('data-url');
    APP.confirm('Are you sure you want to delete this article?').then(function () {
        $.get(url, function() {
            window.location.reload();
        });
    })
});