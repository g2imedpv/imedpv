$(document).ready(function(){
    $("#section-65-field-176").find("input").each(function(){
        $(this).prop("disabled",true);
    });
    $("#section-44-field-149").find("input").each(function(){
        $(this).prop("disabled",true);
    });
    $(".js-example-responsive").select2({
        width: 'resolve'
    });
    $("select").select2({
        selectOnClose: true
    });
    $("span.select2").addClass("d-block w-100");

    // Logic for show or hide some field by SELECT options
    function selectShowORhide (target, optionA, optionB) {
        $(target).hide();
        $(optionA).click(function() {
            $(target).show();
        });
        $(optionB).click(function() {
            $(target).hide();
        });
    }

    // Logic for show or hide some field by RADIO options
    function checkboxShowORhide (target, option) {
        $(target).hide();
        $(option).change(function(){
            if($(this).prop('checked')){
                $(target).show();
            }
            if(!$(this).prop('checked')){
                $(target).hide();
            }
        });
    }


    //sort table content in every column
    $('.table').DataTable();

    //change general tab date format
    function dateConvert(target){
        var date=$(target).val();
        if(date){
            var dateInformat=date.substring(4,8)+"/"+date.substring(2,4)+"/"+date.substring(0,2);
            $(target).val(dateInformat);
            $(target).prop("disabled", true);
        }else{
            $(target).val("");
        }
    }

    $("[id^=unspecified-]").change(function(){
        let sectionId = $(this).attr('id').split('-')[2];
        let fieldId = $(this).attr('id').split('-')[4];
        $("#section-"+sectionId+"-unspecifieddate-"+fieldId).val($("#unspecified-day_section-"+sectionId+"-unspecifieddate-"+fieldId).val()+$("#unspecified-month_section-"+sectionId+"-unspecifieddate-"+fieldId).val()+$("#unspecified-year_section-"+sectionId+"-unspecifieddate-"+fieldId).val());
    });
    $("[id*=unspecifieddate][id^=section]").val()

    // General Tab:
        // Admin section
            // For Additional documents (A.1.8.1) select
            selectShowORhide ("#section-1-field-355, #section-1-field-14","#section-1-radio-13-option-1","#section-1-radio-13-option-2");
            // For Report Nullification (A.1.13) checkbox
            checkboxShowORhide ('#section-1-field-23',"#section-1-checkbox-22-option-1");
            // For Exist Other Case Identifiers? (A.1.11) checkbox
            checkboxShowORhide ('#section-1-field-19, #section-1-field-20', "#section-1-checkbox-18-option-1");
            dateConvert("#section-1-date-5");
            dateConvert("#section-1-date-10");
            dateConvert("#section-1-date-12");
            dateConvert("#section-1-date-225");
            dateConvert("#section-1-text-414");
            dateConvert("#section-1-text-415");
            dateConvert("#section-55-text-388");
            
            
           
    // Patient Tab:
        // Congenital Anomaly section
            // Congenital Anomaly field
            selectShowORhide ("#section-16-field-277","#section-16-radio-273-option-1","#section-16-radio-273-option-2");
        //Relevant medical history and concurrent conditions(B.1.7) section
            //add title to distinct meddraw browser
            $("#section-77-field-472").parent().prepend('<h5 class="col-md-12 mb-3">Drug Indication</h5>');
            $("#section-77-field-473").parent().prepend('<h5 class="col-md-12 mb-3">Drug Reaction</h5>');
            $("#section-5-field-401").parent().prepend('<h5 class="col-md-12 mb-3">Disease</h5>');
            $("#section-8-field-477").parent().prepend('<h5 class="col-md-12 mb-3">Parent Disease</h5>');
            $("#section-78-field-482").parent().prepend('<h5 class="col-md-12 mb-3">Drug Indication</h5>');
            $("#section-78-field-486").parent().prepend('<h5 class="col-md-12 mb-3">Drug Reaction</h5>');
            $("#section-19-field-490").parent().prepend('<h5 class="col-md-12 mb-3">Reported cause of death</h5>');
            $("#section-19-field-494").parent().prepend('<h5 class="col-md-12 mb-3">Autopsy-determined cause of death</h5>');
            $("#section-26-field-496").parent().prepend('<h5 class="col-md-12 mb-3">Reaction or event</h5>');
            $("#section-69-field-499").parent().prepend('<h5 class="col-md-12 mb-3">Sender\'s diagnosis or syndrome</h5>');
            $("#section-44-field-497").parent().prepend('<h5 class="col-md-12 mb-3">Recurred reaction</h5>');
            $("#section-45-field-498").parent().prepend('<h5 class="col-md-12 mb-3">Reaction assessed</h5>');
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
            //checkboxShowORhide ('#section-22-field-206, #section-22-field-207,#section-22-field-205', "#section-22-checkbox-434-option-1");
           
           
            
        function duration(startDay,startMonth,startYear,endDay,endMonth,endYear,dur,durUnit) {
            var endTime=($(endYear)*365+$(endMonth)*30+$(endDay))*24*60;
            var startTime=($(startYear)*365+$(startMonth)*30+$(startDay))*24*60;
            var diffTime=endTime-startTime;
            if(diffTime<60){
                $(dur).val(diffTime);
                $(durUnit).value(806).trigger('change');
            }else if((diffTime>=60)&&(diffTime<1440)){
                 $(dur).val(diffTime/60);
                 $(durUnit).value(805).trigger('change');
            }else if((diffTime>=1440)&&(diffTime<10080)){
                 $(dur).val(diffTime/60/24);
                 $(durUnit).value(804).trigger('change');
            }else if((diffTime>=10080)&&(diffTime<45360)){
                 $(dur).val(diffTime/60/24/7);
                 $(durUnit).value(803).trigger('change');
            }else if((diffTime>=45360)&&(diffTime<525600)){
                 $(dur).val(diffTime/24/60/31.5);
                 $(durUnit).value(802).trigger('change');
            }else if(diffTime>525600){
                 $(dur).val(diffTime/365/24/60);
                 $(durUnit).value(801).trigger('change');
            }     
        }
        duration("#unspecified-day_section-22-unspecifieddate-199", "#unspecified-month_section-22-unspecifieddate-199","#unspecified-year_section-22-unspecifieddate-199",
        "#unspecified-day_section-22-unspecifieddate-205","#unspecified-month_section-22-unspecifieddate-205","#unspecified-year_section-22-unspecifieddate-205",
        "#section-22-text-206","#section-22-select-207");
    // Causality Tab:
        // If Rechallenge (B.4.k.17.1) answer "Yes", B.4.k.17.2* Meddra should be answer as well
            // var target = $('#section-44-field-210').parent();
            // var target1 = $('#section-44-field-211').parent();
            // (target,target1).hide();
            // $("#section-44-field-209").change(function(){
            //     if($('#select2-section-44-select-209-container').text() == "Yes"){
            //         (target,target1).show();
            //     }
            //     else {
            //         (target,target1).hide();
            //     }
            // });
  
});
    
    
