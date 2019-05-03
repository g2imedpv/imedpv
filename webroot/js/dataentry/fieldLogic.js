$(document).ready(function(){

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
    // highlighted selected table row
    // $(".table tbody tr").click(function() {
    //     var selected = $(this).hasClass("highlight");
    //     $(".table tr").removeClass("highlight");
    //     if(!selected)
    //     $(this).addClass("highlight");
    // });

    //sort table content in every column
    $('.table').DataTable();

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
            
           
    // Patient Tab:
        // Congenital Anomaly section
            // Congenital Anomaly field
            selectShowORhide ("#section-16-field-277","#section-16-radio-273-option-1","#section-16-radio-273-option-2");

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
            checkboxShowORhide ('#section-22-field-206, #section-22-field-207,#section-22-field-205', "#section-22-checkbox-434-option-1");
        //     $("#section-22-checkbox-434-option-1").change(function (){
        //         duration("#unspecified-day_section-22-unspecifieddate-199", "#unspecified-month_section-22-unspecifieddate-199","#unspecified-year_section-22-unspecifieddate-199",
        //         "#unspecified-day_section-22-unspecifieddate-205","#unspecified-month_section-22-unspecifieddate-205","#unspecified-year_section-22-unspecifieddate-205",
        //         "#section-22-text-206","#section-22-select-207");
        //     });
        // function duration(startDay,startMonth,startYear,endDay,endMonth,endYear,dur,durUnit) {
        //     var endTime=($(endYear)*365+$(endMonth)*30+$(endDay))*24*60;
        //     var startTime=($(startYear)*365+$(startMonth)*30+$(startDay))*24*60;
        //     var diffTime=endTime-startTime;
        //     if(diffTime<60){
        //         $(dur).val(diffTime);
        //         $(durUnit).value(806).trigger('change');
        //     }else if((diffTime>=60)&&(diffTime<1440)){
        //          $(dur).val(diffTime/60);
        //          $(durUnit).value(805).trigger('change');
        //     }else if((diffTime>=1440)&&(diffTime<10080)){
        //          $(dur).val(diffTime/60/24);
        //          $(durUnit).value(804).trigger('change');
        //     }else if((diffTime>=10080)&&(diffTime<45360)){
        //          $(dur).val(diffTime/60/24/7);
        //          $(durUnit).value(803).trigger('change');
        //     }else if((diffTime>=45360)&&(diffTime<525600)){
        //          $(dur).val(diffTime/24/60/31.5);
        //          $(durUnit).value(802).trigger('change');
        //     }else if(diffTime>525600){
        //          $(dur).val(diffTime/365/24/60);
        //          $(durUnit).value(801).trigger('change');
        //     }  
            
        // }
        
    

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
    
    
