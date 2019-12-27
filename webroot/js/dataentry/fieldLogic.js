$(document).ready(function(){
    $("#section-44-field-149 :input").attr("disabled",true);
    $("#section-47-field-176 :input").attr("disabled",true);
    var field549 = "";
    var field501 ="";
    if(tabId == 9){
        field501 = $('#section-48-select-501').html();
        field549 = $('#section-48-select-549').html();
    }
    $("input[name*=\\[id\\]]").change(function(){
        if($(this).attr('type') == "checkbox" ||$(this).attr('type') == "radio") return false;
        let field_id = $(this).parent().attr('id').split("-")[4];
        let val = $(this).val();
        $("[id*=field-"+field_id+"]").each(function(){
            $(this).find("input[name*=\\[id\\]]").val(val);
        });
    });
    $("[name*=\\[field_value\\]]").change(function(){
        if($(this).attr('type') == "checkbox" ||$(this).attr('type') == "radio") return false;
        if(autoChangeflag) return false; 
        autoChangeflag = true;
        let field_id = $(this).attr('id').split("-")[3];
        let val = $(this).val();
        $("[id$=field-"+field_id+"]").each(function(){
            $(this).find("input[name*=\\[field_value\\]]").val(val).trigger("change");
        });
        autoChangeflag = false;
    });
    $('#section-48-select-501').change(function(){
        if(autoChangeflag) return false;
        var countrySelected = $(this).find('option:selected').text();
        console.log(autoChangeflag);
        var eventSeleted = $("#section-48-select-549").find('option:selected').val();
        $("body").prepend("<table id=\"instatable\">"+tableFields+"</table>");
        $('#section-48-select-549').html(field549);
        $("#section-48-select-549").val(eventSeleted).trigger('change');
        $('#section-48-select-549').find("option").each(function(){
            var eventOptions = $(this).text();
            var flag = false;
            $("#instatable").find("tr").each(function(){
                // console.log($("table[id^=sectionSummary-48]").find("#"+$(this).attr('id')).hasClass("selected-row"));
                if($(this).find("[id$=td-549]").text() ==eventOptions&&$(this).find("[id$=td-501]").text() == countrySelected&&!$("table[id^=sectionSummary-48]").find("#"+$(this).attr('id')).hasClass("selected-row")){
                    flag = true;
                    return false;
                }
            });
            if(flag) {
                if($(this).is(':selected'))
                    $("#section-48-select-549").val("").trigger('change');
                $(this).prop("disabled",true);
            }
        });
        $("#instatable").remove();
    });
    $("#section-65-field-176").find("input").each(function(){
        $(this).prop("disabled",true);
    });
    $("#section-44-field-149").find("input").each(function(){
        $(this).prop("disabled",true);
    });
    $(".js-example-responsive").select2({
        width: 'resolve'
    });
    // $("select").select2({
    //     selectOnClose: true
    // });
    $("span.select2").addClass("d-block w-100");

    //sort table content in every column
    $('.table').DataTable();

    // Logic for show or hide some field by SELECT options
    function selectShowORhide (target, optionA, optionB) {
        if($(optionA).prop('checked')==true){
            $(target).show();
        }
        if($(optionA).prop('checked')==false){
            $(target).hide();
        }
        $(optionA).click(function() {
            $(target).show();
        });
        $(optionB).click(function() {
            $(target).hide();
        });
    }

    // Logic for show or hide some field by RADIO options
    function checkboxShowORhide (target, option) {
        if($(option).prop('checked')==true){
            $(target).show();
        }
        if($(option).prop('checked')==false){
            $(target).hide();
        }
        $(option).change(function(){
            if($(this).prop('checked')==true){
                $(target).show();
            }
            if($(this).prop('checked')==false){
                $(target).hide();
            }
        });
    }
    // Logic for show or hide some field by choosing one of checkbox options
    function oneCheckboxShow (target, optionA, optionB) {
        if(($(optionA).prop('checked')==true)||($(optionB).prop('checked')==true)){
            $(target).show();
        }else{
            $(target).hide();
        }
        $(optionA).click(function() {
            if(($(optionA).prop('checked')==true)||($(optionB).prop('checked')==true)){
                $(target).show();
            }else{
                $(target).hide();
            }
        });
        $(optionB).click(function() {
            if(($(optionA).prop('checked')==true)||($(optionB).prop('checked')==true)){
                $(target).show();
            }else{
                $(target).hide();
            }
        });
    }

    

    //change general tab date format shown on screen
    function dateConvert(target){
        var sectionId=$(target).attr('id');
        if(typeof sectionId!='undefined'){
            sectionId=sectionId.split('-')[1];
        }else{
            return ;
        }
        var fieldId=$(target).attr('id').split('-')[3];
        var date=$(target).val();
        if((typeof date!="undefined")&&(date!="")){
            var dateInformat=date.substring(0,2)+" / "+date.substring(2,4)+" / "+date.substring(4,8);
            $("#specified-date-section-"+sectionId+"-date-"+fieldId).val(dateInformat);
        }else{
            $("#specified-date-section-"+sectionId+"-date-"+fieldId).val("");
        }
    }
        dateConvert("#section-1-date-5");
        dateConvert("#section-1-date-10");
        dateConvert("#section-1-date-12");
        dateConvert("#section-1-date-225");
        dateConvert("#section-1-date-414");
        dateConvert("#section-1-date-415");
        dateConvert("#section-55-date-388");
    //change unspecified date format when saving
    $("[id^=unspecified-]").change(function(){
        let sectionId = $(this).attr('id').split('-')[2];
        let fieldId = $(this).attr('id').split('-')[4];
        $("#section-"+sectionId+"-unspecifieddate-"+fieldId).val($("#unspecified-day_section-"+sectionId+"-unspecifieddate-"+fieldId).val()+$("#unspecified-month_section-"+sectionId+"-unspecifieddate-"+fieldId).val()+$("#unspecified-year_section-"+sectionId+"-unspecifieddate-"+fieldId).val());
    });
    $("[id*=unspecifieddate][id^=section]").val()
    //change specified date format when saving
    $("[id^=specified-]").change(function(){
        let sectionId = $(this).attr('id').split('-')[3];
        let fieldId = $(this).attr('id').split('-')[5];
        let date = $("#specified-date-section-"+sectionId+"-date-"+fieldId).val().split(' / ');
        $("#section-"+sectionId+"-date-"+fieldId).val(date[0]+date[1]+date[2]);
    });
    

    //gray out fields
    function grayout(target){
        $(target).prop("disabled", true);
    }
    // General Tab:
        //-> Admin section
        //grayout some date fields
        grayout("#specified-date-section-1-date-10,#specified-date-section-1-date-12,#specified-date-section-1-date-415,#specified-date-section-1-date-414,#specified-date-section-1-date-225");
        // For Additional documents (A.1.8.1) select and add document
        selectShowORhide ("#section-1-field-355, #section-1-field-14","#section-1-radio-13-option-1","#section-1-radio-13-option-2");
        // For Additional documents  (C.1.6.1) select and add document
        selectShowORhide ("#section-1-field-1000, #section-1-field-1064","#section-1-radio-13-option-1","#section-1-radio-13-option-2");
        //add upload files button
        var uploadDocButton="<button type=\"button\" class=\"btn btn-primary float-left ml-3 mt-3\" data-toggle=\"modal\" data-target=\".uploadDoc\"><i class=\"fas fa-cloud-upload-alt\"></i>"+i18n.gettext("Upload Documents")+"</button>";    
        $("#section-1-field_label-355").append(uploadDocButton);
        // For Report Nullification (A.1.13) checkbox
        checkboxShowORhide ('#section-1-field-23',"#section-1-checkbox-22-option-1");
        //Report Nullification / Amendment (C.1.11.1)
        oneCheckboxShow ('#section-1-field-1067',"#section-1-checkbox-1140-option-1","#section-1-checkbox-1140-option-2");
        // For Exist Other Case Identifiers? (A.1.11) checkbox
        checkboxShowORhide ('#section-1-field-19, #section-1-field-20,#section-1-field-1066', "#section-1-checkbox-18-option-1");
        //show document list in field
        var docLists=$('#hidden_docLists').val();
        $('#section-1-text-14').val(docLists)
 
    // Patient Tab:
        // Congenital Anomaly section
            // Congenital Anomaly field
            selectShowORhide ("#section-16-field-277","#section-16-radio-273-option-1","#section-16-radio-273-option-2");
        //Relevant medical history and concurrent conditions(B.1.7) section
            // //add title to distinct meddraw browser
            // $("#section-77-field-472").parent().prepend('<h5 class="col-md-12 mb-3">Drug Indication</h5>');
            // $("#section-77-field-473").parent().prepend('<h5 class="col-md-12 mb-3">Drug Reaction</h5>');
            // $("#section-5-field-401").parent().prepend('<h5 class="col-md-12 mb-3">Disease</h5>');
            // $("#section-8-field-477").parent().prepend('<h5 class="col-md-12 mb-3">Parent Disease</h5>');
            // $("#section-78-field-482").parent().prepend('<h5 class="col-md-12 mb-3">Drug Indication</h5>');
            // $("#section-78-field-486").parent().prepend('<h5 class="col-md-12 mb-3">Drug Reaction</h5>');
            // $("#section-19-field-490").parent().prepend('<h5 class="col-md-12 mb-3">Reported cause of death</h5>');
            // $("#section-19-field-494").parent().prepend('<h5 class="col-md-12 mb-3">Autopsy-determined cause of death</h5>');
            // $("#section-26-field-496").parent().prepend('<h5 class="col-md-12 mb-3">Reaction or event</h5>');
            // $("#section-69-field-499").parent().prepend('<h5 class="col-md-12 mb-3">Sender\'s diagnosis or syndrome</h5>');
            // $("#section-44-field-497").parent().prepend('<h5 class="col-md-12 mb-3">Recurred reaction</h5>');
            // $("#section-45-field-498").parent().prepend('<h5 class="col-md-12 mb-3">Reaction assessed</h5>');
            //diease therapy date,if choose continue,no end date and duration
            selectShowORhide('#section-5-field-240,#section-5-field-241,#section-5-field-102',"#section-5-radio-100-option-2,#section-5-radio-100-option-3","#section-5-radio-100-option-1");
            //parent diease therapy date,if choose continue,no end date and duration
            selectShowORhide('#section-8-field-343,#section-8-field-444,#section-8-field-137',"#section-8-radio-135-option-2,#section-8-radio-135-option-3","#section-8-radio-135-option-1");
    // Product Tab:
        // If "Ongoing field checked", then Therapy End date (B.4.k.14b) DISABLED
            function optionalGrayout(target,option){
                $("#section-22-checkbox-434-option-1").change(function(){
                    var checked=$(option).val().substring(0,1);
                    if (checked==1){
                        $(target).prop("disabled", true);
                    }else{
                        $(target).prop("disabled", false);
                    }
                });
            }
            optionalGrayout("#unspecified-day_section-22-unspecifieddate-205,#unspecified-month_section-22-unspecifieddate-205,#unspecified-year_section-22-unspecifieddate-205,#section-22-text-206,#section-22-select-207","#section-22-checkbox-434-2-final");
            
    //Seriousness Criteria Tab:
            
        function duration(startDay,startMonth,startYear,endDay,endMonth,endYear,dur,durUnit) {
            var endTime=($(endYear)*365+$(endMonth)*30+$(endDay))*24*60;
            var startTime=($(startYear)*365+$(startMonth)*30+$(startDay))*24*60;
            var diffTime=endTime-startTime;
            if(diffTime<60){
                $(dur).val(diffTime);
                $(durUnit).val('806').trigger('change');
            }else if((diffTime>=60)&&(diffTime<1440)){
                 $(dur).val(diffTime/60);
                 $(durUnit).val('805').trigger('change');
            }else if((diffTime>=1440)&&(diffTime<10080)){
                 $(dur).val(diffTime/60/24);
                 $(durUnit).val('804').trigger('change');
            }else if((diffTime>=10080)&&(diffTime<45360)){
                 $(dur).val(diffTime/60/24/7);
                 $(durUnit).val('803').trigger('change');
            }else if((diffTime>=45360)&&(diffTime<525600)){
                 $(dur).val(diffTime/24/60/31.5);
                 $(durUnit).val('802').trigger('change');
            }else if(diffTime>525600){
                 $(dur).val(diffTime/365/24/60);
                 $(durUnit).val('801').trigger('change');
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
    //labeling Tab:
    $('#section-48-checkbox-383-option-4').parent().removeClass("col-md-3");
    //Distribution tab:additional infomation
    grayout("#section-75-textarea-218,#section-75-textarea-222");

    //General:date format validation
    function checkValue(str, max) {
      if (str.charAt(0) !== '0' || str == '00') {
        var num = parseInt(str);
        if (isNaN(num) || num <= 0 || num > max) num = 1;
        str = num > parseInt(max.toString().charAt(0)) && num.toString().length == 1 ? '0' + num : num.toString();
      };
      return str;
    };
    function dateListner(target){
        var date = document.getElementById(target);
        if(date==null){
            return;
        }else{
            date.addEventListener('input', function(e) {
            this.type = 'text';
            var input = this.value;
            if (/\D\/$/.test(input)) input = input.substr(0, input.length - 3);
            var values = input.split('/').map(function(v) {
                return v.replace(/\D/g, '')
               
            });
            if (values[0]) values[0] = checkValue(values[0], 31);
            if (values[1]) values[1] = checkValue(values[1], 12);
            var output = values.map(function(v, i) {
                return v.length == 2 && i < 2 ? v + ' / ' : v;
            });
            this.value = output.join('').substr(0, 14);
            });
        }
    }
    dateListner('specified-date-section-1-date-5');
    dateListner('specified-date-section-1-date-414');
    dateListner('specified-date-section-1-date-415');
    dateListner('specified-date-section-55-date-388');
    dateListner('specified-date-section-1-date-1063');
});
    
    
