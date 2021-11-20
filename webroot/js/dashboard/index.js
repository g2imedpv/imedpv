jQuery(function($) {
    $(document).ready(onQueryClicked());
});


$(document).ready(function(){
    $("#fullSearchBtn").click(function () {
        $('#searchProductNameMin').val("");
        $('#fullSearch').show();
        $('#basicSearch').hide();
    });
    $("#BasicSearchBtn").click(function () {
        $('#searchProductNameMin').val("");
        $('#fullSearch').hide();
        $('#fullSearch').each().val("");
        $('#basicSearch').show();
    });
    $('#searchProductNameMin').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){ //“ENTER” key is represented by ascii code “13”
            onQueryClicked();
        }
    });
    // Press "Enter" Key for searching after input
    $('#fullSearch').find('input').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){ //“ENTER” key is represented by ascii code “13”
            onQueryClicked();
        }
    });
    $('.js-example-basic-single').select2();
    /**
     * dashboard advanced search:activity due date and submission due date calendar
     */
    $(`
        #activity_due_date_start,
        #activity_due_date_end,
        #submission_due_date_start,
        #submission_due_date_end,
        #case_received_date_start,
        #case_received_date_end,
        #patient_dob
    `).datepicker({dateFormat: 'yy/mm/dd'});
});
