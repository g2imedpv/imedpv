jQuery(function($) {
    $(document).ready(onQueryClicked());
});


$(document).ready(function(){
    $("#fullSearchBtn").click(function () {
        $('#searchProductNameMin').val("");
        $('#fullSearch').show();
        $('#basicSearch').hide();
    });

    // Press "Enter" Key for searching after input
    $('.searchmodal input').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){ //“ENTER” key is represented by ascii code “13”
            onQueryClicked();
        }
    });
});