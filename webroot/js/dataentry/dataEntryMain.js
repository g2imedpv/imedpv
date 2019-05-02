jQuery(function($) {
    $(document).ready(paginationReady());
});

$(document).ready(function(){

    $('[name$=\\[field_value\\]').change(function(){
        let id = $(this).attr('id').split('-');
        $('[id=section-'+id[1]+'-error_message-'+id[3]+']').text();
        $('[id=section-'+id[1]+'-error_message-'+id[3]+']').hide();

        $("div[id=section-"+id[1]+"-field-"+id[3]+']').each(function(){
            let field_value = null;
            if($(this).find("[name$=\\[field_value\\]]").length){
                field_type = $(this).find("[name$=\\[field_value\\]]").attr('id').split('-')[2];
                if((field_type!="radio")&&(field_type!="checkbox")){
                    field_value = $(this).find("[name$=\\[field_value\\]]").val();
                }else{
                    if(field_type=="radio"){
                        $(this).find("[name$=\\[field_value\\]]").each(function(){
                            if($(this).prop('checked')){
                                field_value = $(this).val();}
                        });
                    }else{
                        field_value = $(this).find("[id$=final]").val();
                    }
                }
            }
            if(($(this).find("[name$=\\[field_rule\\]]").length)&&(field_value!="")){
                let rule = $(this).find("[name$=\\[field_rule\\]]").val().split("-");
                if((rule[1]=="N")&&(!/^[0-9]+$/.test(field_value)))
                {
                    $(this).find("[id^=section-"+id[1]+"-error_message-]").show();
                    //$(this).find("[id^=section-"+id[1]+"-error_message-]").text('/numbers only');
                    $(this).find("[id^=section-"+id[1]+"-error_message-]").html(swal("Numbers Only", "Please re-entry the valid data", "warning"));
                    console.log('number only at '+$(this).attr('id'));
                    validate = 0;
                }else if((rule[1]=="A")&&(!/^[a-zA-Z]+$/.test(field_value))){
                    console.log('alphabet only at '+$(this).attr('id'));
                    $(this).find("[id^=section-"+id[1]+"-error_message-]").show();
                    //$(this).find("[id^=section-"+id[1]+"-error_message-]").text('/alphabet only');
                    $(this).find("[id^=section-"+id[1]+"-error_message-]").html(swal("Alphabet Only", "Please re-entry the valid data", "warning"));
                    validate = 0;
                }
                if(rule[0]<field_value.length) {
                    console.log('exccess the length at'+$(this).attr('id'));
                    $(this).find("[id^=section-"+id[1]+"-error_message-]").show();
                    //$(this).find("[id^=section-"+id[1]+"-error_message-]").text( $(this).find("[id^=section-"+id[1]+"-error_message-]").text()+'/exccess the length');
                    $(this).find("[id^=section-"+id[1]+"-error_message-]").html(swal("Exccess the length", "Please re-entry the valid data", "warning"));
                    validate = 0;
                }
            };
        });
    });
 if(readonly) {
    $('input').prop("disabled", true);
    $('select').prop("disabled", true);
    $('textarea').prop("disabled", true);
};

    $("#searchFieldKey").keyup(function(){
        let request={
            'key':$('#searchFieldKey').val(),
            'caseId':caseId,
            'userId':userId,
        };
        console.log(request);
        if(request['key']!="")
        {
            $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            type:'POST',
            url:'/sd-sections/search',
            data:request,
            success:function(response){
                $('#searchFieldResult').html("");
                console.log(response);
                searchResult = $.parseJSON(response);
                let text ="<table class=\"table table-hover w-100\">";
                text +="<tr><th scope=\"col\">Field Lable</th>";
                text +="<th scope=\"col\">Tab Name</th>";
                text +="<th scope=\"col\">Section Name</th>";
                text +="<th scope=\"col\">Action</th><tr>";
                $.each(searchResult,function(k,v){
                    text +="<tr>";
                    text +="<td>"+v['field']['field_label']+"</td>";
                    text +="<td>"+v['tab']['tab_name']+"</td>";
                    text +="<td>"+v['section_name']+"</td>";
                    text +="<td><a class=\"btn btn-outline-info btn-sm\" onclick=\"hightlightField("+v['field']['id']+")\" role=\"button\" href=\"/sd-tabs/showdetails/"+caseNo+"/"+version+"/"+v['tab']['id']+"#secdiff-"+v['id']+"\">Go</a></td></tr>";
                });
                text +="</table>";
                $('#searchFieldResult').html(text);

            },
            error:function(response){
                console.log(response.responseText);
            }
        });}
        else $('#searchFieldResult').html("");
    });

});
// Search Bar
    function hightlightField (fieldID) {
        $("div[id*='"+fieldID+"']").css("border", "3px dotted red").delay(2000);
    };

$(document).ready(function(){
 if(readonly) {
    $('input').prop("disabled", true);
    $('select').prop("disabled", true);
    $('textarea').prop("disabled", true);
};


    $('input:checkbox[id^=section]').change(
        function(){
            if ($(this).is(':checked')) {
                let id = $(this).attr('id').split('-');
                let inputElement = $('[id^='+id[0]+'-'+id[1]+'-'+id[2]+'-'+id[3]+'][id$=final]');
                let Value = inputElement.val();
                Value = Value.substring(0, $(this).val()-1) + "1" + Value.substring($(this).val());
                inputElement.val(Value);
            }else{
                let id = $(this).attr('id').split('-');
                let inputElement = $('[id^='+id[0]+'-'+id[1]+'-'+id[2]+'-'+id[3]+'][id$=final]');
                let Value = inputElement.val();
                Value = Value.substring(0, $(this).val()-1) + "0" + Value.substring($(this).val());
                inputElement.val(Value);
            }
        });
});
function level2setPageChange(section_id, pageNo, addFlag=null){
    let child_section =  $("[id^=child_section][id$=section-"+section_id+"]").attr('id').split('-');
    child_section_id = child_section[1].split(/[\[\]]/);
    child_section_id = jQuery.grep(child_section_id, function(n, i){
        return (n !== "" && n != null);
    });
    let max_set_no  = 0 ;
    $(child_section_id).each(function(k, v){
        let sectionKey = $("[id^=add_set-"+v+"]").attr('id').split('-')[3];
        $(section[sectionKey].sd_section_structures).each(function(k,v){
            $.each(v.sd_field.sd_field_values,function(key, value){
                max_set_no = Math.max(value.set_number, max_set_no);
            })
        })
    });
    if ((pageNo <= 0)||(pageNo>max_set_no)) {console.log("set_no not avaiable");console.log(max_set_no); return;};
     if(addFlag==1)
    {
        pageNo = max_set_no+1;
        $("[id^=child_section][id$=section-"+section_id+"]").hide();
    }else{$("[id^=child_section][id$=section-"+section_id+"]").show()}
    $(child_section_id).each(function(k,v){
        setPageChange(v, pageNo, addFlag, 1);
    });

    $("[id^=left_set-"+section_id+"]").attr('id', 'left_set-'+section_id+'-setNo-'+pageNo);
    $("[id^=left_set-"+section_id+"]").attr('onclick','level2setPageChange('+section_id+','+Number(Number(pageNo)-1)+')');
    $("[id^=right_set-"+section_id+"]").attr('id', 'right_set-'+section_id+'-setNo-'+pageNo);
    $("[id^=right_set-"+section_id+"]").attr('onclick','level2setPageChange('+section_id+','+Number(Number(pageNo)+1)+')');
    $("[id=section-"+section_id+"-page_number-"+child_section[5]+"]").css('font-weight', 'normal');
    $("[id=section-"+section_id+"-page_number-"+pageNo+"]").css('font-weight', 'bold');
    $("[id^=child_section][id$=section-"+section_id+"]").attr('id',child_section[0]+'-'+child_section[1]+'-'+child_section[2]+'-'+child_section[3]+'-'+child_section[4]+'-'+pageNo+'-'+child_section[6]+'-'+child_section[7]);
}
function renderSummaries(section_id, pageNo){
    let setArray = {};
    //get this section's set array
    if($("#setArray-"+section_id).length){
        $.each($("#setArray-"+section_id).val().split(','),function(k,sectionId){
            if(sectionId=="") return true;
            setArray[sectionId] = null;
        });
    }else{
        $("[id^=section-"+section_id+"][id*=addableSectionNo]").each(function(){
            setArray[$(this).attr('name').split(/[\[\]]/)[7]] = null;
            section_id = $(this).attr('name').split(/[\[\]]/)[7];
        });
    }
    //get parent setNo
    $.each(setArray,function(detailSectionId, setNo){
        if($("#summary-"+detailSectionId).length){
            if($("#summary-"+detailSectionId).find(".selected-row").length)
                setArray[detailSectionId] = parseInt($("#summary-"+detailSectionId).find(".selected-row").attr('id').split("-")[3]);
        }else{
            setArray[detailSectionId] = parseInt($("[id=pagination-section-"+detailSectionId+"]").find(".selected-page").attr('id').split("-")[3]);
        }
    });
    if(section_id in setArray)
        setArray[section_id] = pageNo;
    console.log(setArray);
    $("table[id^=sectionSummary-]").each(function(){
        let tbodyText ="";
        let sectionId = $(this).attr('id').split('-')[1];
        if(sectionId in setArray && sectionId != section_id) return true;
        let setSections = $('#setArray-'+sectionId).val().substr(0,$('#setArray-'+sectionId).val().length-1).split(',');
        let targetSetArray = "";
        let row = 1;
        let related = false;
        $.each(setSections, function(k, setSectionId){
            if(setSectionId in setArray)
                targetSetArray = setArray[setSectionId]+","+targetSetArray;
            else targetSetArray = "1,"+targetSetArray;
        });
        targetSetArray = targetSetArray.substr(0,targetSetArray.length-1);
        let setString = $('#setArrayValue-'+sectionId).val();
        let sectionKey = $(this).attr('id').split('-')[3];
        let noValue = section[sectionKey].sd_section_summary.sdFields.length;
        do{
            noValue = section[sectionKey].sd_section_summary.sdFields.length;
            let rowtext = "";
            $.each(section[sectionKey].sd_section_summary.sdFields,function(k, field_detail){
                let noMatchFlag = 0;  
                $.each(field_detail.sd_field_values,function(k, field_value_detail){
                    if(field_value_detail.sd_section_sets.length == 0) return true;
                    let fieldSetArray = field_value_detail.sd_section_sets[0].set_array;
                    for(let i = 0; i < fieldSetArray.length; i ++){
                        if(fieldSetArray.charAt(i) == '*') {
                            fieldSetArray = fieldSetArray.substring(0,i)+targetSetArray.charAt(i)+fieldSetArray.substring(i+1);
                        }
                    }
                    if(fieldSetArray.substring(2) == targetSetArray.substring(2)&&fieldSetArray.split(',')[0] == row){

                        rowtext = rowtext+"<td id=\"section-"+sectionId+"-row-"+row+"-td-"+field_detail.id+"\">";     
                        if(field_detail.sd_element_type_id != 1 && field_detail.sd_element_type_id != 3 && field_detail.sd_element_type_id != 4)
                            rowtext = rowtext + field_value_detail.field_value;
                        else{
                        $.each(field_detail.sd_field_value_look_ups,function(k, look_ups){
                            if(look_ups.value == field_value_detail.field_value){
                                rowtext = rowtext+look_ups.caption;
                                return false;
                            }
                        });
                        }
                        rowtext = rowtext+"</td>"; 
                        noValue --;
                        noMatchFlag = 1;
                        return true;     
                    }
                });
                if(!noMatchFlag) rowtext = rowtext+"<td id=\"section-"+sectionId+"-row-"+row+"-"+field_detail.id+"\"></td>";
            });
            if(noValue != section[sectionKey].sd_section_summary.sdFields.length) {
                tbodyText = tbodyText+"<tr ";
                if(row==1) tbodyText = tbodyText+"class=\"selected-row\" ";
                tbodyText = tbodyText+"id=\"section-"+sectionId+"-row-"+row+"\" onclick=\"setPageChange("+sectionId+","+row+")\" >"+rowtext+"<td><button class='btn btn-outline-danger' onclick='#' role='button' title='show'><i class='fas fa-trash-alt'></i></button></td></tr>";
            }
            row  = row +1;
        }while(noValue !=section[sectionKey].sd_section_summary.sdFields.length);
        $(this).find('tbody').html(tbodyText);
        $(this).find('#section-'+section_id+'-row-'+setArray[section_id]).addClass('selected-row');
    });
    // $("#[id^=pagination-section-]").each(function(){
        //TODO
        // let text ="";
        //     text = $text+"<input type=\"hidden\" id='setArray-".$sdSections->id."' value='";
        //         foreach($setArray as $setSectionId){
        //             $text = $text.$setSectionId.",";
        //         } 
        //     text = text+"'>";
        //     text = text+ "<ul class=\"pagination mb-0 mx-2\">";
        //     text = text+    "<li class=\"page-item\" id=\"left_set-".$sdSections->id."-sectionKey-".$section_key."-setNo-1\" onclick=\"setPageChange(".$sdSections->id.",0)\" >";
        //     text = text+    "<a class=\"page-link\" aria-label=\"Previous\">";
        //     text = text+        "<span aria-hidden=\"true\">&laquo;</span>";
        //     text = text+        "<span class=\"sr-only\">Previous</span>";
        //     text = text+    "</a>";
        //     text = text+    "</li>";
        //     if($max_set_No != 0){
        //         for($pageNo = 1; $pageNo<=$max_set_No; $pageNo++ ){
        //             text = text+    "<li class=\"page-item";
        //             if($pageNo == 1) text = text+" selected-page";
        //             text = text+"\" id=\"section-".$sdSections->id."-page_number-".$pageNo."\" onclick=\"setPageChange(".$sdSections->id.",".$pageNo.")\"><a class=\"page-link\">".$pageNo."</a></li>";
        //         }
        //     }else{
        //         text = text+    "<li class=\"page-item selected-page\" style=\"font-weight:bold\" id=\"section-".$sdSections->id."-page_number-1\" onclick=\"setPageChange(".$sdSections->id.",1)\"><a class=\"page-link\">1</a></li>";

        //     }
        //     text = text+    "<li class=\"page-item\" id=\"right_set-".$sdSections->id."-sectionKey-".$section_key."-setNo-1\" onclick=\"setPageChange(".$sdSections->id.",2)\">";
        //     text = text+    "<a class=\"page-link\" aria-label=\"Next\">";
        //     text = text+        "<span aria-hidden=\"true\">&raquo;</span>";
        //     text = text+        "<span class=\"sr-only\">Next</span>";
        //     text = text+    "</a>";
        //     text = text+    "</li>";
        //     text = text+ "</ul>";
    // });
}
function setPageChange(section_id, pageNo, addFlag=null, pFlag) {
    $("[id^=save-btn"+section_id+"]").hide();
    //TODO HIGHLIGHT SELECTED PAGE
    let max_set = 0;
    if($("[id=summary-"+section_id+"]").length){
        $("[id=summary-"+section_id+"]").find("tr").each(function(){
            max_set ++;
        });
        $("[id=summary-"+section_id+"]").find(".selected-row").removeClass("selected-row");
        $("[id=summary-"+section_id+"]").find("#section-"+section_id+"-row-"+pageNo).addClass("selected-row");
    }else{
        $("#pagination-section-"+section_id).find("tr").each(function(){
            max_set ++;
        });
        $("#pagination-section-"+section_id).find(".selected-page").removeClass("selected-page");
        $("#pagination-section-"+section_id).find("#section-"+section_id+"-page_number-"+pageNo).addClass("selected-page");
    }

    let setArray = {};
    //get this section's set array
    $.each($("#setArray-"+section_id).val().split(','),function(k,sectionId){
        if(sectionId=="") return true;
        setArray[sectionId] = null;
    });
    //get parent setNo
    $.each(setArray,function(detailSectionId, setNo){
        if($("#summary-"+detailSectionId).length){
            if($("#summary-"+detailSectionId).find(".selected-row").length)
                setArray[detailSectionId] = parseInt($("#summary-"+detailSectionId).find(".selected-row").attr('id').split("-")[3]);
        }else{
            setArray[detailSectionId] = parseInt($("[id=pagination-section-"+detailSectionId+"]").find(".selected-page").attr('id').split("-")[3]);
        }
    });
    setArray[section_id] = pageNo;
    if(addFlag)
        setArray[section_id] = max_set;
    console.log(setArray);
    //for each field
    $("[id^=input-").each(function(){
        let sectionId = $(this).attr('id').split('-')[1];
        let sectionKey = $(this).attr('id').split('-')[3];
        $(this).find("[id*=-field-]").each(function(){
            //get this field setArray
            let targetSetArray = {};
            let fieldsectionSetArray = [];
            let fieldDiv = $(this);
            if($(this).find("[id*=addableSectionNo]").length == 0) return true;
            $(this).find("[id*=addableSectionNo]").each(function(){
                fieldsectionSetArray.unshift($(this).attr('id').split('-')[5]);
                targetSetArray[$(this).attr('id').split('-')[5]] = $(this).val();
            });
            let relateFlag = false;
            //classify sections: 1. parent section 2.same section 3.child section 4.unrelated section
            $.each(targetSetArray,function(setSectionId,detailSetNo){
                if(setSectionId in setArray){
                    targetSetArray[setSectionId] = setArray[setSectionId];
                    relateFlag = true;
                }else{
                    targetSetArray[setSectionId] = 1;
                };
            });
            if($("[id=summary-"+sectionId+"]").length){
                $("[id=summary-"+sectionId+"]").find(".selected-row").removeClass("selected-row");
                $("[id=summary-"+sectionId+"]").find("#section-"+sectionId+"-row-"+targetSetArray[sectionId]).addClass("selected-row");
            }else{
                $("#pagination-section-"+sectionId).find(".selected-page").removeClass(".selected-page");
                $("#pagination-section-"+sectionId).find("#section-"+sectionId+"-page_number-"+targetSetArray[sectionId]).addClass(".selected-page");
            }
            let fieldTargetArray = [];
            $.each(fieldsectionSetArray,function(k,v){
                fieldDiv.find("[id$=addableSectionNo-"+v+"]").val(targetSetArray[v]);
                fieldTargetArray.push(targetSetArray[v]);
            })
            //type of 4
            if(!relateFlag) return true;
            $(this).find("[name$=\\[id\\]]").each(function(){
                let sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
                let valueFlag = false;
                let thisElement = $(this);
                let idholder = thisElement.attr('id').split('-');//section-65-sd_section_structures-0-sd_field_value_details-0-id
                let maxindex=0;
                if (section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){
                    $.each(section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                        if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
                            let setMatch = true;
                            $.each(fieldTargetArray,function(k,v){
                                if(v == parseInt(value.sd_section_sets.set_array.split(',')[k])||(value.sd_section_sets.set_array.split(',')[k]=='*'&&k!=0))
                                    return true;
                                setMatch = false;
                                return false;
                            });
                            if ((typeof value != 'undefined')&&(setMatch)){
                                thisElement.val(value.id);
                                thisElement.attr('id',idholder[0]+'-'+idholder[1]+'-'+idholder[2]+'-'+idholder[3]+'-'+idholder[4]+'-'+index+'-'+idholder[6]);
                                valueFlag = true;
                                return false;
                            }
                            maxindex = maxindex+1;
                        }
                    });
                }
                if(valueFlag == false) {
                    $(this).val(null);
                    let idholder = thisElement.attr('id').split('-');
                   
                    thisElement.attr('id',idholder[0]+'-'+idholder[1]+'-'+idholder[2]+'-'+idholder[3]+'-'+idholder[4]+'-'+maxindex+'-'+idholder[6]);
                };    
            });

            $(this).find("[name$=\\[field_value\\]]").each(function(){
                let sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
                let thisId = $(this).attr('id').split('-');
                let valueFlag = false;
                let thisElement = $(this);
                if (section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
                    $.each(section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                        if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
                            let setMatch = true;
                            $.each(fieldTargetArray,function(k,v){
                                if(v == parseInt(value.sd_section_sets.set_array.split(',')[k])||(value.sd_section_sets.set_array.split(',')[k]=='*'&&k!=0))
                                    return true;
                                setMatch = false;
                                return false;
                            });
                            if ((typeof value != 'undefined')&&(setMatch)){
                                if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
                                    thisElement.val(value.field_value).trigger('change');
                                    valueFlag = true;
                                }else{
                                    if(thisElement.attr('id').split('-')[2]=='radio'){
                                        if(thisElement.val()==value.field_value) {
                                            thisElement.prop('checked',true);
                                            valueFlag = true;
                                        }else thisElement.prop('checked',false);
                                    }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
                                        valueFlag = true;
                                        if(value.field_value.charAt(Number(thisElement.val())-1) == 1){
                                            thisElement.prop('checked',true);
                                        }else thisElement.prop('checked',false);
                                        if((typeof thisId[5] != 'undefined')&&(thisId[5]=="final")) {thisElement.val(value.field_value); }
                                    }
                                }
                            }
                            
                        }
                    });
                }
                if(valueFlag == false) {
                    if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
                        thisElement.val(null).trigger('change');;
                    }else{
                        thisElement.prop('checked',false);
                        if((typeof thisId[5] != 'undefined')&&(thisId[5]=="final")) {
                            val = "";
                            for (i = 0; i < thisId[4]; i++){
                                val = val+"0";
                            }
                            thisElement.val(val);
                        }
                    }
                };
            });
    
        });
    });
    renderSummaries(section_id, pageNo);
    return false;
}
function searchWhoDra(){
    let request = {
        'atc-code': $("#atc").val(),
        'drug-code':$("#drugcode").val(),
        'medicinal-prod-id':$('#medicalProd').val(),
        'trade-name':$('#tradename').val(),
        'ingredient':$('#ingredient').val(),
        'formulation':$('#formulation').val(),
        'country':$('#inputState').val(),
    };
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/who-dra/search',
        data:request,
        success:function(response){
            console.log(response);

        },
        error:function(response){
                console.log(response.responseText);

            $("#textHint").html("Sorry, no case matches");

        }
    });
}
function paginationReady(){
    return false;
    $("[id^=pagination-l2").each(function(){
        let hsectionid = $(this).attr('id').split('-')[3];
        let child_section_element = $("[id^=child_section][id$=section-"+hsectionid+"]").attr('id');
        let child_section_id = child_section_element.split('-')[1];
        child_section_id = child_section_id.split(/[\[\]]/);
        child_section_id = jQuery.grep(child_section_id, function(n, i){
            return (n !== "" && n != null);
        });
        let max_set_no  = 0 ;
        $(child_section_id).each(function(k, v){
            let sectionKey = $("[id^=add_set-"+v+"]").attr('id').split('-')[3];
            $(section[sectionKey].sd_section_structures).each(function(k,v){
                $.each(v.sd_field.sd_field_values,function(key, value){
                    // console.log("v:");console.log(v);console.log(value);console.log(value.set_number);
                    max_set_no = Math.max(value.set_number, max_set_no);
                })
            })
            // section[section_Id[2]].sd_section_structures[sectionStructureK].sd_field.sd_field_value_details
            $("[id^=pagination-section-"+v+"]").hide();
        });
        if (max_set_no==0) {
            $("[id^=child_section][id$=section-"+hsectionid+"]").hide();
            max_set_no = 1;
        }else {
            $("[id^=child_section][id$=section-"+hsectionid+"]").show();
            $("[id=delete_section-"+hsectionid+"]").show();
        }
        let text= "";
        text += "<nav class=\"d-inline-block float-right\" title=\"Pagination\" aria-label=\"Data Entry Set Pagination\">";
        text += "<ul class=\"pagination mb-0\">";
        text +=    "<li class=\"page-item\" id=\"left_set-"+hsectionid+"-setNo-1\" onclick=\"level2setPageChange("+hsectionid+",0)\" >";
        text +=    "<a  class=\"page-link\" aria-label=\"Previous\">";
        text +=        "<span aria-hidden=\"true\">&laquo;</span>";
        text +=        "<span class=\"sr-only\">Previous</span>";
        text +=    "</a>";
        text +=    "</li>";
        for(pageNo=1; pageNo<=max_set_no; pageNo++){
                    text +=    "<li class=\"page-item\" id=\"l2section-"+hsectionid+"-page_number-"+pageNo+"\" ><a id=\"section-"+hsectionid+"-page_number-"+pageNo+"\" onclick=\"level2setPageChange("+hsectionid+","+pageNo+")\" class=\"page-link\">"+pageNo+"</a></li>";
        }
        text +=    "<li class=\"page-item\" id=\"right_set-"+hsectionid+"-setNo-1\" onclick=\"level2setPageChange("+hsectionid+",2)\">";
        text +=    "<a class=\"page-link\" aria-label=\"Next\">";
        text +=        "<span aria-hidden=\"true\">&raquo;</span>";
        text +=        "<span class=\"sr-only\">Next</span>";
        text +=    "</a>";
        text +=    "</li>";
        text += "</ul>";
        text += "</nav>";
        $("#showpagination-"+hsectionid).html(text);
    })
}
function deleteSection(sectionId, setArray=null, pcontrol=false){

    let request = {};
    let savedArray = [];
    let setNo = 0;
    $("div[id^=section-"+sectionId+"-field]").each(function(){
        if($(this).find("[name$=\\[field_value\\]]").length){
            let field_id = $(this).attr('id').split('-')[3];
            let field_request =
            {
                'id': $(this).children("[name$=\\[id\\]]").val(),
                'sd_field_id':$(this).children("[name$=\\[sd_field_id\\]]").val(),
            };
            let setArray = {};
            $(this).children("[name*=set_array]").each(function(){
                setArray[$(this).attr('id').split('-')[5]] = $(this).val();
                setNo = $(this).val();
            });
            field_request['set_array'] = setArray;
            request[field_id] = field_request;
        }
    });
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-sections/deleteSection/'+caseId,
        data:request,
        success:function(response){
            console.log(response);
            swal({
                icon: "success",
                title: "This set has been deleted",
              });
            section = $.parseJSON(response);
            renderSummaries(sectionId,setNo);
            $("[id=addbtnalert-"+sectionId+"]").hide();
            return false;

            // TODO
            // swal("Are you sure?","This action can not be undone", "warning",{
            //     buttons: {
            //         Yes: true,
            //         cancel: "Cancel"
            //     },
            // }).then((value) => {
            //     if (value) {
            //         swal({
            //             icon: "success",
            //             title: "This set has been deleted",
            //           });
            //     }
            // });
            savedArray = $.parseJSON(response);
            console.log(savedArray);
            let sectionIdOriginal =  $("[id^=save-btn"+sectionId+"]").attr('id');
            let section_Id = sectionIdOriginal.split('-');

            let max_set_no = 0
            for(var k in request){
                let setNum = $("[id$=field-"+k+"]").children("[id^=section-"+sectionId+"-set_number]").val();
                let fieldvalueK = $("[id$=field-"+k+"]").children("[id^=section-"+sectionId+"-sd_section_structures]").attr('id').split('-')[5];
                let sectionStructureK = $("[id^=section-"+sectionId+"-set_number-"+k+"]").attr('name').split(/[\[\]]/)[3];
                section[section_Id[2]].sd_section_structures[sectionStructureK].sd_field.sd_field_values = savedArray[k];
                $(section[section_Id[2]].sd_section_structures[sectionStructureK].sd_field.sd_field_values).each(function(k,v){
                    if(typeof v != 'undefined')
                    max_set_no = Math.max(v.set_number, max_set_no);
                });
            }
            if (max_set_no!=0){
                $("[id^=right_set-"+sectionId+"]").prev().remove();
                let previousPageNo = $("[id^=right_set-"+sectionId+"]").prev().attr('id').split('-page_number-')[1];
                if(setNum>max_set_no) setNum = max_set_no;
                if(pcontrol==false) setPageChange(sectionId,setNum);
            }else{
                if(pcontrol==false) setPageChange(sectionId, 1, 1);
            }
            $addId = $("[id^=add_set-"+sectionId+"]").attr('id').split('-setNo-');
            if(typeof previousPageNo !='undefined'){
            $("[id^=add_set-"+sectionId+"]").attr('id',$addId[0]+'-setNo-'+previousPageNo);}
            else{$("[id^=add_set-"+sectionId+"]").attr('id',$addId[0]+'-setNo-0');}
            // paginationReady();
            //TODO
            // $("[id^=child_section]").each(function(){

            //     child_section = $(this).attr("id").split('-');
            //     child_section_id = child_section[1].split(/[\[\]]/);
            //     child_section_id = jQuery.grep(child_section_id, function(n, i){
            //         return (n !== "" && n != null);
            //     });
            //     if($.inArray(sectionId,child_section_id)){
            //         console.log(Number(max_set_no)-1);
            //         console.log(child_section[7]);
            //         level2setPageChange(child_section[7], setNum);
            //     }
            // });
        },
        error:function(response){
            console.log(response.responseText);

            // $("#textHint").html("Sorry, no case matches");

        }
    });
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-tabs/validateForm/'+caseId+'/'+sectionId+'/'+tabId,
        data:request,
        success:function(response){
            console.log(response);
        },
        error:function(response){
            console.log(response.responseText);

        }
    });

}
function saveSection(sectionId){
    let setNo = 0;
    // if(!validation(sectionId)) return;
    let request = {};
    let error = 0;
    let sectionRequest = {};
    $("div[id^=section-"+sectionId+"-field]").each(function(){
        if($(this).find("[id^=section-"+sectionId+"-error_message-]").is(":visible")) error = 1;
        let field_value = null;
        if($(this).find("[name$=\\[field_value\\]]").length){
            field_type = $(this).find("[name$=\\[field_value\\]]").attr('id').split('-')[2];
            if((field_type!="radio")&&(field_type!="checkbox")){
                 field_value = $(this).find("[name$=\\[field_value\\]]").val();
            }else{
                if(field_type=="radio"){
                    $(this).find("[name$=\\[field_value\\]]").each(function(){
                        if($(this).prop('checked')){
                            field_value = $(this).val();}
                    });
                }else{
                    field_value = $(this).find("[id$=final]").val();
                }
            }
            let field_id = $(this).attr('id').split('-')[3];
            let field_request =
            {
                'sd_field_id' : $(this).children("[name$=\\[sd_field_id\\]]").val(),
                'id': $(this).children("[name$=\\[id\\]]").val(),
                'field_value': field_value
            };
            let setArray = {};
            $(this).children("[name*=set_array]").each(function(){
                setArray[$(this).attr('id').split('-')[5]] = $(this).val();
                setNo = $(this).val();
            });
            field_request['set_array'] = setArray;
            request[field_id] = field_request;
        }
        sectionRequest[sectionId] = request;
    });
    if(error) return false;
    console.log(sectionRequest);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-sections/saveSection/'+tabId+'/'+caseId,
        data:sectionRequest,
        success:function(response){
            console.log(response);
            swal({
                icon: "success",
                title: "This section has been saved",
              });
            section = $.parseJSON(response);
            renderSummaries(sectionId,setNo);
            $("[id=addbtnalert-"+sectionId+"]").hide();
            return false;
        },
        error:function(response){
            console.log(response.responseText);
        }
    });
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-tabs/validateForm/'+caseId+'/'+sectionId+'/'+tabId,
        data:request,
        success:function(response){
            console.log(response);
        },
        error:function(response){
            console.log(response.responseText);

        }
    });

};
function action(type){
    text = "";
    if(type==1){
        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            type:'POST',
            url:'/sd-users/searchNextAvailable/'+caseNo+'/'+version,
            success:function(response){console.log(response);
                response = JSON.parse(response);
                console.log(response);
                text +="<div class=\"modal-header\">";
                text +="<h3 class=\"modal-title text-center w-100\" id=\"exampleModalLabel\">Sign Off</h3>";
                text +="<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">";
                text +="<span aria-hidden=\"true\">&times;</span>";
                text +="</button>";
                text +="</div>";
                text +="<div class=\"modal-body text-center m-3\">";
                text +="<p class=\"lead\">Next activity is: "+response['actvity']['activity_name']+"</p>";
                text +="<input type=\"hidden\" id=\"next-activity-id\" value=\""+response['actvity']['id']+"\">";
                text +="<div class=\"form-group\">";
                text +="<label><h5>Comment</h5></label>";
                text +="<textarea class=\"form-control\" id=\"query-content\" rows=\"3\"></textarea>";
                text +="</div>";
                text +="<hr class=\"my-4\">";
                if(response['previousUserOnNextActivity'].length > 0){
                    text +="<div><h6>Previous User On This Case On Next Activity: </h6>";
                    $.each(response['previousUserOnNextActivity'],function(k,v){
                        text +=v['user']['firstname']+" "+v['user']['lastname']+"("+v['company']['company_name']+"), ";
                    });
                    text +="</div>";
                    text +="<hr class=\"my-4\">";
                }
                //add function to chose most avaiable person
                text +="<div class=\"form-group\">";
                text +="<label><h6>Select person you want to send to:</h6></label><select class=\"form-control\" id=\"receiverId\">";
                $.each(response['users'],function(k,v){
                    text +="<option value="+v['id']+">"+v['firstname']+" "+v['lastname'];
                    if(v['sd_cases'].length > 0)
                        text +="(currently working on "+v['sd_cases']['0']['casesCount']+" cases)";
                    else text +="(currently working on 0 case)";
                    text +="</option>";
                });
                text +="</select>";
                text +="</div>";
                text +="<h3>Field Required</h3>"
                text +="<table class=\"table table-hover\">";
                text +="<tr>";
                text +="<th scope=\"col\">Category</th>";
                text +="<th scope=\"col\">Section</th>";
                text +="<th scope=\"col\">Field Name</th>";
                text +="<th scope=\"col\">Set Number</th>";
                text +="<tr>";
                $.each(response['caseValidate'],function(tabK,tabDetail){
                    let previousSectionK ="";
                    $.each(tabDetail,function(sectionK, sectionDetail){
                        $.each(sectionDetail,function(fieldId,fieldDetail){
                            text +="<tr>";
                            text +="<td>"+fieldDetail.tab_name+"</td>";
                            text +="<td>"+fieldDetail.section_name+"</td>";
                            text +="<td>"+fieldDetail.field_label+"</td>";
                            text +="<td>"+fieldDetail.set_number+"</td>";
                            text +="</tr>";
                        });
                        previousSectionK = sectionK;
                    });
                    previousTabk = tabK;
                });
                text +="</table>"
                text +="<div class=\"text-center\"><button class=\"btn btn-primary w-25\" onclick=\"forward()\">Confirm</button></div>";
                text +="</div>";
                $('#action-text-hint').html(text);
            },
            error:function(response){
                console.log(response.responseText);
            },
        });
    }
    if(type==2){
        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            type:'POST',
            url:'/sd-users/searchPreviousAvailable/'+caseNo+'/'+version,
            success:function(response){console.log(response);
                response = JSON.parse(response);
                console.log(response);
                text +="<div class=\"modal-header\">";
                text +="<h3 class=\"modal-title text-center w-100\" id=\"exampleModalLabel\">Push Backward</h3>";
                text +="<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">";
                text +="<span aria-hidden=\"true\">&times;</span>";
                text +="</button>";
                text +="</div>";
                text +="<div class=\"modal-body text-center m-3\">";
                text +="<div class=\"form-group\">";
                text +="<label><h5>Comment</h5></label>";
                text +="<textarea class=\"form-control\" id=\"query-content\" rows=\"3\"></textarea>";
                text +="</div>";
                text +="<h5>Case Info:</h5>";
                text +="<table class=\"table table-hover\">";
                text +="<thead>";
                text +="<tr>";
                text +="<th scope=\"col\">Activity </th>";
                text +="<th scope=\"col\">Previous User On This Activity </th>";
                text +="<th scope=\"col\">Avaliable User </th>";
                text +="</tr>";
                text +="</thead>";
                $.each(response,function(k,activity){
                    text += "<div id=\"previous_activity-"+activity['id']+"\" hidden>"+JSON.stringify(activity['users'])+"</div> ";
                    if(activity['previousUserOnPreviousActivity'].length > 0){
                        text +="<tr>";
                        text += "<td>"+activity['activity_name']+"</td>";
                        text +="<td>";
                        $.each(activity['previousUserOnPreviousActivity'],function(k,v){
                            text +=v['user']['firstname']+" "+v['user']['lastname']+"("+v['company']['company_name']+")<br>";
                        });
                        text += "</td><td>";
                        $.each(activity['users'],function(k,v){
                            text +=v['firstname']+" "+v['lastname'];
                            if(v['sd_cases'].length > 0)
                                text +="(currently working on "+v['sd_cases']['0']['casesCount']+" cases)<br>";
                            else text +="(currently working on 0 case)<br>";
                        });
                        text +="</tr>";
                    }
                });
                text +="</table>";
                text +="<hr class=\"my-4\">";
                //add function to chose most avaiable person
                text +="<div class=\"form-group\">";
                text +="<label>Which you want to push to?</label>";
                text +="<select class=\"form-control w-50 mx-auto\" id=\"next-activity-id\" >";
                text +="<option value=\"null\">Select Activity</option>";
                $.each(response,function(k,v){
                    text += "<option value=\""+v['id']+"\">"+v['activity_name']+"</option>";
                });
                text +="</select>";
                text +="<label class=\"my-2\">Select person you want to send to:</label><select class=\"form-control w-50 mx-auto\" id=\"receiverId\">";
                text +="</select>";
                text +="</div>";
                text +="<div class=\"text-center\"><button class=\"btn btn-primary w-25\" onclick=\"backward()\">Confirm</button></div>";
                text +="</div>";
                $('#action-text-hint').html(text);
                $('#next-activity-id').change(function(){
                    console.log($('#previous_activity-'+$(this).val()).html());
                    let users =  $.parseJSON($('#previous_activity-'+$(this).val()).html());
                    let text =""
                    $('#receiverId').html(text);
                    $.each(users,function(k,v){
                        text += "<option value=\""+v['id']+"\">"+v['firstname']+" "+v['lastname']+"</option>"
                    });
                    $('#receiverId').html(text);
                });
            },
            error:function(response){
                console.log(response.responseText);
            },
        });
    }

}
function forward(){
    let request ={
        'senderId':userId,
        'next-activity-id':$('#next-activity-id').val(),
        'receiverId':$('#receiverId').val(),
        'content':$('#query-content').val()
    }
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/forward/'+caseNo+'/'+version+"/0",
        data:request,
        success:function(response){
            console.log(response);
            window.location.href = "/sd-cases/caselist";
        },
        error:function(response){
            console.log(response.responseText);
            }
        });
}
function backward(){
    let request ={
        'senderId':userId,
        'next-activity-id':$('#next-activity-id').val(),
        'receiverId':$('#receiverId').val(),
        'content':$('#query-content').text()
    }
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/forward/'+caseNo+'/'+version+"/1",
        data:request,
        success:function(response){
            console.log(response);
            window.location.href = "/sd-cases/caselist";
        },
        error:function(response){
            console.log(response.responseText);
            }
        });
}

jQuery(function($) {
    // Alert if changes unsaved
    $(document).ready(function() {
        let unsaved = false;

        $("input:not(:button,:submit),textarea,select").change(function(){   //triggers change in all input fields including text type
            unsaved = true;
        });

        // $(window).bind('beforeunload', function(){
        //     if(unsaved){
        //          return swal({
        //             title: "Are you sure?",
        //             text: "Your data is changed, are you sure you want to complete?",
        //             icon: "warning",
        //             buttons: true,
        //             dangerMode: true,
        //         })}
        //   });

        window.onbeforeunload = function (){
            if(unsaved){
                let _msg = 'Your data is changed, are you sure you want to complete?';
                return  _msg;
            }
        };
    });

    // Show "Save" button when any input change
    $(document).ready(function() {
        $("input,textarea,select").change(function () {
            $(this).parents('.fieldInput').siblings().find("[id^=save-btn]").show();
         });
    });

    // // Auto populate the selected value into next
    // $(document).ready(function() {
    //     // Dsiabled the field of Form and Route of admin. Text
    //     $('#section-22-text-347,#section-22-text-286').attr('disabled',true);

    //     $('#section-22-select-191').change(function() {
    //         let foa = $("option:selected", this).text();
    //         $('#section-22-text-347').val(foa);
    //     });

    //     $('#section-22-select-192').change(function() {
    //         let roa = $("option:selected", this).text();
    //         $('#section-22-text-286').val(roa);
    //     });

    // });

});
