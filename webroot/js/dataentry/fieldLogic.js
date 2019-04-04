$(document).ready(function(){

    $(".js-example-responsive").select2({
        width: 'resolve'
    });
    $("select").select2({
        selectOnClose: true
    });

    // Logic for show or hide some field by options
    function showORhide (target, optionA, optionB) {
        $(target).hide();
        $(optionA).click(function() {
            $(target).show();
        });
        $(optionB).click(function() {
            $(target).hide();
        });
    }
    // General Tab:
        // Admin section
            // For Additional documents (A.1.8.1) select
            showORhide ("#section-1-field-355","#section-1-radio-13-option-1","#section-1-radio-13-option-2");

    // Patient Tab:
        // Congenital Anomaly section
            // Congenital Anomaly field
            showORhide ("#section-16-field-277","#section-16-radio-273-option-1","#section-16-radio-273-option-2");

    // Product Tab:
        // If "Ongoing field checked", then Therapy End date (B.4.k.14b) DISABLED
            $("#section-22-checkbox-434-option-1").change(function(){
                if($(this).prop('checked')){
                    $('#section-22-date-205').prop('disabled',true);
                }

                if(!$(this).prop('checked')){
                    $('#section-22-date-205').prop('disabled',false);
                }
            });

    // Causality Tab:
        // If Rechallenge (B.4.k.17.1) answer "Yes", B.4.k.17.2* Meddra should be answer as well
            var target = $('#section-44-field-211').parent();
            target.hide();
            $("#section-44-field-209").change(function(){
                if($('#select2-section-44-select-209-container').text() == "Yes"){
                    target.show();
                }
                else {
                    target.hide();
                }
            });




});