$(document).ready(function(){
    $('[name$=\\[field_value\\]').change(function(){
        let id = $(this).attr('id').split('-');
        $('#section-'+id[1]+'-error_message-'+id[3]).text();
        $('#section-'+id[1]+'-error_message-'+id[3]).hide();
        $("#section-"+id[1]+"-field-"+id[3]).each(function(){
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
                    $(this).find("[id^=section-"+id[1]+"-error_message-]").html(swal(i18n.gettext("Numbers Only"), i18n.gettext("Please re-entry the valid data"), "warning"));
                    validate = 0;
                }else if((rule[1]=="A")&&(!/^[a-zA-Z]+$/.test(field_value))){
                    $(this).find("[id^=section-"+id[1]+"-error_message-]").show();
                    //$(this).find("[id^=section-"+id[1]+"-error_message-]").text('/alphabet only');
                    $(this).find("[id^=section-"+id[1]+"-error_message-]").html(swal(i18n.gettext("Alphabet Only"), i18n.gettext("Please re-entry the valid data"), "warning"));
                    validate = 0;
                }
                if(rule[0]<field_value.length) {
                    $(this).find("[id^=section-"+id[1]+"-error_message-]").show();
                    //$(this).find("[id^=section-"+id[1]+"-error_message-]").text( $(this).find("[id^=section-"+id[1]+"-error_message-]").text()+'/exccess the length');
                    $(this).find("[id^=section-"+id[1]+"-error_message-]").html(swal(i18n.gettext("Exccess the length"), i18n.gettext("Please re-entry the valid data"), "warning"));
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
        // console.log(request);
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
                text +="<tr><th scope=\"col\">"+i18n.gettext("Field Label")+"</th>";
                text +="<th scope=\"col\">"+i18n.gettext("Tab Name")+"</th>";
                text +="<th scope=\"col\">"+i18n.gettext("Section Name")+"</th>";
                text +="<th scope=\"col\">"+i18n.gettext("Action")+"</th><tr>";
                $.each(searchResult,function(k,v){
                    text +="<tr>";
                    text +="<td>"+v['field']['field_label']+"</td>";
                    text +="<td>"+v['tab']['tab_name']+"</td>";
                    text +="<td>"+v['section_name']+"</td>";
                    text +="<td><a class=\"btn btn-outline-info btn-sm\" onclick=\"hightlightField("+v['tab']['id']+","+v['field']['id']+")\" role=\"button\" href=\"/sd-tabs/showdetails/"+caseNo+"/"+version+"/"+v['tab']['id']+"#secdiff-"+v['id']+"?searchFlag=true&field="+v['field']['id']+"&section="+v['id']+"\">"+i18n.gettext("Go")+"</a>";
                    text +="<a class=\"btn btn-outline-danger btn-sm\" onclick=\"removeField("+v['field']['id']+")\" role=\"button\" href=\"/sd-tabs/showdetails/"+caseNo+"/"+version+"/"+v['tab']['id']+"#secdiff-"+v['id']+"\">"+i18n.gettext("Del")+"</a></td></tr>";
                });
                text +="</table>";
                $('#searchFieldResult').html(text);
            },
            error:function(response){
                // console.log(response.responseText);
            }
        });}
        else $('#searchFieldResult').html("");
    });
    if(tabId == 9){
        //labeling field
        var filterText = "<div class=\"form-control\" style=\"text-align:center;color:#007bff;font-weight:bold;\"><label for=\"country_filter\">"+i18n.gettext("Country")+":&nbsp;&nbsp;&nbsp;</label><select id=\"country_filter\">";
        var countryField = document.getElementById('section-48-select-501');
        var options = countryField.innerHTML;
        filterText = filterText + options+"</select></div>";
        $('#card-summary-48').prepend(filterText);
        $('#country_filter').find('option:selected').removeAttr("selected");
        autoChangeflag = true;
        $('#country_filter').val("null").trigger('change');
        autoChangeflag = false;
        tableFields = $('table[id^=sectionSummary-48]').find("tbody").html();
     }
     if($('#country_filter').length == 1){
        $("#country_filter").change(function(){
            if(autoChangeflag) return false;
            autoChangeflag = true;
            let country = $(this).find('option:selected').text();
            $('[id^=sectionSummary-48]').find('tbody').html(tableFields);
            if(country != ""){
                let exitFlag = 0;
                $("table[id^=sectionSummary-48]").find("tbody").find("tr").each(function(){
                    if($(this).find('td[id*=td-501]').text() != country){
                        $(this).remove();
                    }else{
                        if(exitFlag==0) exitFlag = $(this).attr('id').split('-')[3];
                    }
                });
                console.log(exitFlag);
                if(exitFlag == 0)
                    setPageChange(48, tableFields.split("</tr>").length, true);
                else setPageChange(48,exitFlag);
            }
            // $("table[id^=sectionSummary-48]").removeClass("dataTable");
            // $("table[id^=sectionSummary-48]").removeAttr("role");
            // $("table[id^=sectionSummary-48]").find('th').each(function(){
            //     $(this).removeAttr("aria-describedby");
            //     $(this).removeAttr("tabindex");
            //     $(this).removeAttr("aria-controls");
            //     $(this).removeAttr("rowspan");
            //     $(this).removeAttr("colspan");
            //     $(this).removeAttr("aria-label");
            //     $(this).removeAttr("aria-sort");
            //     $(this).removeAttr("style");
            // });
            var htmltext = $("table[id^=sectionSummary-48]").html();
            htmltext = "<table class=\""+$("table[id^=sectionSummary-48]").attr('class')+"\" id=\""+$("table[id^=sectionSummary-48]").attr('id')+"\">"+htmltext+"</table>";
            $("#sectionSummary-48-sectionKey-1_wrapper").remove();
            $("#summary-48").prepend(htmltext);

            $("table[id^=sectionSummary-48]").find(".dataTables_empty").parent().remove();
            console.log($("table[id^=sectionSummary-48]").html());
            $("table[id^=sectionSummary-48]").DataTable();
            autoChangeflag = false;

        });
     }
});
// Search Bar
    function hightlightField (sectionID,fieldID) {
        window.location.hash = "#section-"+sectionID+"-field-"+fieldID+"";
        $("div[id$='field-"+fieldID+"']").css("border", "3px dotted red").delay(2000);
    };
    function removeField(fieldID) {
        $("div[id$='field-"+fieldID+"']").css("border", "none").delay(2000);
    };
//get parameter from url
    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
            vars[key] = value;
        });
        return vars;
    }
//search result in other page
$(document).ready(function(){
    var url = window.location.href;
    index = url.indexOf("searchFlag");
    if(index !=-1){
        var fieldID = getUrlVars()["field"];
        var sectionID = getUrlVars()["section"];
        var parent=$("h3[id='section_label-"+sectionID+"']").parent().parent().parent().parent().attr('id');
        if(typeof parent!="undefined"){
        var parentID=parent.split('-')[1];
        $("a[id$='-tab']")&&$("a[id^='nav-']").removeClass("active");
        $("#nav-"+parentID+"-tab").addClass("active");
        $("div[id^='secdiff-']").removeClass("active");
        $("div[id^='secdiff-']").removeClass("show");
        $("#secdiff-"+parentID).addClass("active");
        $("#secdiff-"+parentID).addClass("show");}
        window.location.hash = "#section-"+sectionID+"-field-"+fieldID+"";
        $("div[id$='field-"+fieldID+"']").css("border", "3px dotted red");
    }
});

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
// function level2setPageChange(section_id, pageNo, addFlag=null){
//     let child_section =  $("[id^=child_section][id$=section-"+section_id+"]").attr('id').split('-');
//     child_section_id = child_section[1].split(/[\[\]]/);
//     child_section_id = jQuery.grep(child_section_id, function(n, i){
//         return (n !== "" && n != null);
//     });
//     let max_set_no  = 0 ;
//     $(child_section_id).each(function(k, v){
//         let sectionKey = $("[id^=add_set-"+v+"]").attr('id').split('-')[3];
//         $(section[sectionKey].sd_section_structures).each(function(k,v){
//             $.each(v.sd_field.sd_field_values,function(key, value){
//                 max_set_no = Math.max(value.set_number, max_set_no);
//             })
//         })
//     });
//     if ((pageNo <= 0)||(pageNo>max_set_no)) {console.log("set_no not avaiable");console.log(max_set_no); return;};
//      if(addFlag==1)
//     {
//         pageNo = max_set_no+1;
//         $("[id^=child_section][id$=section-"+section_id+"]").hide();
//     }else{$("[id^=child_section][id$=section-"+section_id+"]").show()}
//     $(child_section_id).each(function(k,v){
//         setPageChange(v, pageNo, addFlag, 1);
//     });

//     $("[id^=left_set-"+section_id+"]").attr('id', 'left_set-'+section_id+'-setNo-'+pageNo);
//     $("[id^=left_set-"+section_id+"]").attr('onclick','level2setPageChange('+section_id+','+Number(Number(pageNo)-1)+')');
//     $("[id^=right_set-"+section_id+"]").attr('id', 'right_set-'+section_id+'-setNo-'+pageNo);
//     $("[id^=right_set-"+section_id+"]").attr('onclick','level2setPageChange('+section_id+','+Number(Number(pageNo)+1)+')');
//     $("[id=section-"+section_id+"-page_number-"+child_section[5]+"]").css('font-weight', 'normal');
//     $("[id=section-"+section_id+"-page_number-"+pageNo+"]").css('font-weight', 'bold');
//     $("[id^=child_section][id$=section-"+section_id+"]").attr('id',child_section[0]+'-'+child_section[1]+'-'+child_section[2]+'-'+child_section[3]+'-'+child_section[4]+'-'+pageNo+'-'+child_section[6]+'-'+child_section[7]);
// }
function renderSummaries(section_id, pageNo){
    let setArray = {};
    setArray = {};
    if($("[name=section\\["+section_id+"\\]]").length){
        $.each($("[name=section\\["+section_id+"\\]]").val().split(','),function(k, setSectionArray){
            setArray[setSectionArray.split(':')[0]] = parseInt(setSectionArray.split(':')[1]);
        });
    }else{
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
    }
    if(section_id in setArray)
        setArray[section_id] = parseInt(pageNo);
    $("[id^=sectionSummary-][id$=wrapper]").each(function(){
        let text ="";
        let tbodyText ="";
        let sectionId = $(this).attr('id').split('-')[1];
        if(sectionId in setArray && sectionId != section_id) return true;
        let row = 1;
        $(this).find("table[id^=sectionSummary-]").each(function(){
            let setSections = $('#setArray-'+sectionId).val().substr(0,$('#setArray-'+sectionId).val().length-1).split(',');
            let targetSetArray = "";
            let related = false;
            $.each(setSections, function(k, setSectionId){
                if(setSectionId in setArray)
                    targetSetArray = targetSetArray+setArray[setSectionId]+",";
                else targetSetArray = targetSetArray+"1,";
            });
            targetSetArray = targetSetArray.substr(0,targetSetArray.length-1);
            let sectionKey = $(this).attr('id').split('-')[3];
            let noValue = section[sectionKey].sd_section_summary.sdFields.length;
            text = "<table class=\"table table-bordered table-hover layer"+section[sectionKey].section_level+"\" id=\"sectionSummary-"+sectionId+"-sectionKey-"+sectionKey+"\">";
            text = text+"<thead>";
            text = text+"<tr>";
            text = text+"<th>"+i18n.gettext("SetNo")+"</th>";
            $.each(section[sectionKey].sd_section_summary.sdFields, function(k,field_detail){
                text = text+"<th scope=\"col\" id=\"col-"+sectionId+"-"+field_detail.id+"\">"+field_detail.field_label+"</th>";
            });
            text = text+"<th scope=\"col\">"+i18n.gettext("Action")+"</th>";
            text = text+"</thead>";
            do{
                noValue = section[sectionKey].sd_section_summary.sdFields.length;
                let rowtext = "";
                $.each(section[sectionKey].sd_section_summary.sdFields,function(k, field_detail){
                    let noMatchFlag = 0;
                    $.each(field_detail.sd_field_values,function(k, field_value_detail){
                        let fieldSetArray = field_value_detail.set_number+'';
                        let match =true;
                        $.each(fieldSetArray.split(','), function(k, setNo){
                            if(setNo != targetSetArray.split(',')[k] && field_detail.id!='149' && k > 0)
                            {
                                match = false;
                                return false;
                            }
                        });
                        if(match && fieldSetArray.split(',')[0] == row){
                            rowtext = rowtext+"<td id=\"section-"+sectionId+"-row-"+row+"-td-"+field_detail.id+"\">";
                            if(field_detail.sd_element_type_id != 1 && field_detail.sd_element_type_id != 13 && field_detail.sd_element_type_id != 3 && field_detail.sd_element_type_id != 4)
                                rowtext = rowtext + field_value_detail.field_value;
                            else if(field_detail.sd_element_type_id == 13){
                                console.log(field_value_detail);
                                rowtext = rowtext + dynamic_options[field_detail.descriptor.split('-')[1]][field_value_detail.field_value]

                            }//TODO ADD DATE RENDER LOGIC
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
                    tbodyText = tbodyText+"id=\"section-"+sectionId+"-row-"+row+"\" onclick=\"setPageChange("+sectionId+","+row+")\" ><td>"+row+"</td>"+rowtext+"<td><button class='btn btn-outline-danger' type=\"button\" onclick='deleteSection("+sectionId+","+row+","+sectionKey+")' role='button' title='show'><i class='fas fa-trash-alt'></i></button></td></tr>";
                }
                row  = row +1;
            }while(noValue !=section[sectionKey].sd_section_summary.sdFields.length);
            $("#addbtn-"+sectionId).attr("onclick","setPageChange("+sectionId+","+parseInt(row-1)+",true)");
        });
        $(this).html(text+tbodyText);
        $(this).find('#section-'+sectionId+'-row-1').removeClass('selected-row');
        $(this).find('#section-'+sectionId+'-row-'+setArray[sectionId]).addClass('selected-row');
        $(this).find("table").DataTable();

        if(sectionId==48) tableFields = $('table[id^=sectionSummary-48]').find("tbody").html();
        console.log($(this).find("table").find(".dataTables_empty").length);
        if($(this).find("table").find(".dataTables_empty").length==0){
            console.log(sectionId);
            $("#addbtn-"+sectionId).show();
        }
        else{
            $("#addbtn-"+sectionId).hide();
        }
    });
    $("[id^=pagination-section-]").each(function(){
        let text ="";
        let sdSectionId = $(this).find("[id^=left_set]").attr('id').split('-')[1];
        let sectionKey = $(this).find("[id^=left_set]").attr('id').split('-')[3];
        let set_array = "";
        let max_set_No = 0 ;
        if(sdSectionId in setArray && sdSectionId != section_id) return true;
        $.each(section[sectionKey].sd_section_structures,function(sd_section_structureK, sd_section_structure_detail){
            // console.log(sd_section_structure_detail);
            $.each(sd_section_structure_detail.sd_field.sd_field_values,function(key_detail_field_values, value_detail_field_values){
                // console.log(value_detail_field_values);
                set_array = value_detail_field_values.set_number+'';
                if(set_array.split(',')[0]>=max_set_No)
                max_set_No = set_array.split(',')[0];
            });
        });
        text = text+ "<ul class=\"pagination mb-0 mx-2\">";
        text = text+    "<li class=\"page-item\" id=\"left_set-"+sdSectionId+"-sectionKey-"+sectionKey+"-setNo-1\" onclick=\"setPageChange("+sdSectionId+",0)\" >";
        text = text+    "<a class=\"page-link\" aria-label=\"Previous\">";
        text = text+        "<span aria-hidden=\"true\">&laquo;</span>";
        text = text+        "<span class=\"sr-only\">"+i18n.gettext("Previous")+"</span>";
        text = text+    "</a>";
        text = text+    "</li>";
        if(max_set_No != 0){
            for(pageNo = 1; pageNo<=max_set_No; pageNo++ ){
                text = text+"<li class=\"page-item";
                text = text+"\" id=\"section-"+sdSectionId+"-page_number-"+pageNo+"\" onclick=\"setPageChange("+sdSectionId+","+pageNo+")\"><a class=\"page-link\">"+pageNo+"</a></li>";
            }
        }else{
            text = text+    "<li class=\"page-item\" style=\"font-weight:bold\" id=\"section-"+sdSectionId+"-page_number-1\" onclick=\"setPageChange("+sdSectionId+",1)\"><a class=\"page-link\">1</a></li>";

        }
        text = text+    "<li class=\"page-item\" id=\"right_set-"+sdSectionId+"-sectionKey-"+sectionKey+"-setNo-1\" onclick=\"setPageChange("+sdSectionId+",2)\">";
        text = text+    "<a class=\"page-link\" aria-label=\"Next\">";
        text = text+        "<span aria-hidden=\"true\">&raquo;</span>";
        text = text+        "<span class=\"sr-only\">"+i18n.gettext("Next")+"</span>";
        text = text+    "</a>";
        text = text+    "</li>";
        text = text+ "</ul>";
        $(this).html(text);
        $("#section-"+sdSectionId+"-page_number-"+setArray[sdSectionId]).addClass('selected-page');
        $("#addbtn-"+sdSectionId).attr("onclick","setPageChange("+sdSectionId+","+parseInt(parseInt(max_set_No)+1)+",1)");
        $("#deletebtn-"+sdSectionId).attr("onclick","deleteSection("+sdSectionId+","+setArray[sdSectionId]+","+sectionKey+")");
        console.log(max_set_No);
        if(max_set_No>0){
            $("#addbtn-"+sdSectionId).show();
            $("#deletebtn-"+sdSectionId).show();
        }
        else{
            $("#addbtn-"+sdSectionId).hide();
            $("#deletebtn-"+sdSectionId).hide();
        }
    });
}
//todo addflag boolean
function setPageChange(section_id, pageNo, addFlag=false, resultflag = null) {
    let filterFlag =false;
    if(section_id==48) filterFlag = true;
    if(pageNo == 0) return false;
    $("[id^=save-btn"+section_id+"]").hide();
    $("[id^=save-btn"+section_id+"]").attr("onclick","saveSection("+section_id+","+pageNo+")")
    let setFlag = true;
    let max_set = 0;
    let setArray = {};
    let fieldTargetArray = [];
    //TODO HIGHLIGHT SELECTED PAGE
    let realsection_id = section_id;
    if(!$("[id=summary-"+section_id+"]").length&&!$("#pagination-section-"+section_id).length){
        if($("[name=section\\["+section_id+"\\]]").length)
            section_id = $("[name=section\\["+section_id+"\\]]").val().split(',')[$("[name=section\\["+section_id+"\\]]").val().split(',').length - 1].split(':')[0];
        else setFlag = false;
    }
    if(setFlag)
    {
        if($("[id=summary-"+section_id+"]").length){
            $("[id=summary-"+section_id+"]").find("tr").each(function(){
                if(!$(this).find('th').length)
                    max_set ++;
            });
            if(addFlag==false){
                $("[id=summary-"+section_id+"]").find(".selected-row").removeClass("selected-row");
                $("[id=summary-"+section_id+"]").find("#section-"+section_id+"-row-"+pageNo).addClass("selected-row");
            }
        }else{
            $("#pagination-section-"+section_id).find("[id*=page_number]").each(function(){
                max_set ++;
            });
            if(addFlag==false){
                $("[id^=left_set-"+section_id+"]")
                $("#pagination-section-"+section_id).find(".selected-page").removeClass("selected-page");
                console.log(section_id);
                console.log($("#pagination-section-"+section_id).find("#section-"+section_id+"-page_number-"+pageNo));
                $("#section-"+section_id+"-page_number-"+pageNo).addClass("selected-page");
            }
        }
        //Judge whether this has fields
        if($("[name=section\\["+section_id+"\\]]").length){
            //get this section setNo
            $.each($("[name=section\\["+section_id+"\\]]").val().split(','),function(k, setSectionArray){
                if(section_id == setSectionArray.split(':')[0]){
                    setArray[section_id] = parseInt(pageNo);
                }else{
                    setArray[setSectionArray.split(':')[0]] = parseInt(setSectionArray.split(':')[1]);
                }
            });
        }else{
            //get this section's set array
            $.each($("#setArray-"+section_id).val().split(','),function(k,sectionId){
                if(sectionId=="") return true;
                setArray[sectionId] = null;
            });
            //get parent setNo
            $.each(setArray,function(detailSectionId, setNo){
                if(detailSectionId == section_id) return true;
                if($("#summary-"+detailSectionId).length){
                    setArray[detailSectionId] = parseInt($("#summary-"+detailSectionId).find(".selected-row").attr('id').split("-")[3]);
                }else{
                    setArray[detailSectionId] = parseInt($("[id=pagination-section-"+detailSectionId+"]").find(".selected-page").attr('id').split("-")[3]);
                }
            });
        }
        if(section_id == 48 && addFlag) max_set = tableFields.split("</tr>").length-1;
        if(addFlag)
            setArray[section_id] = max_set+1;
        else setArray[section_id] = pageNo;
    }
    //for each field
    $("[id^=input-").each(function(){
        $(this).find("[id^=llt-searchbar]").val("");
        let orignalId = $(this).attr('id').split('-')[1];
        if(resultflag==1&&orignalId!=realsection_id) return true;
        let sectionId = $(this).attr('id').split('-')[1];
        let sectionKey = $(this).attr('id').split('-')[3];
        let inputSetflag  = true;
        if(!$("[id=summary-"+sectionId+"]").length&&!$("#pagination-section-"+sectionId).length){
            if($("[name=section\\["+sectionId+"\\]]").length)
                sectionId = $("[name=section\\["+sectionId+"\\]]").val().split(',')[$("[name=section\\["+sectionId+"\\]]").val().split(',').length - 1].split(':')[0];
            else inputSetflag = false;
        }
        if(sectionId!=section_id&&!inputSetflag) return true;
            //get this field setArray
            let targetSetArray = {};
            let fieldsectionSetArray = [];
            let fieldDiv = $(this);
            let newSetSectionString ="";
            targetSetArray = {};
            console.log(setFlag);
            if(setFlag&&inputSetflag)
            {
                if($(this).find("[name=section\\["+sectionId+"\\]]").length){
                    newSetSectionString ="";
                    //get this section setNo
                    let setK = 999;
                    console.log($(this).find("[name=section\\["+sectionId+"\\]]").val());
                    $.each($(this).find("[name=section\\["+sectionId+"\\]]").val().split(','),function(k, setSectionArray){
                        fieldsectionSetArray.push(setSectionArray.split(':')[0]);
                        if(section_id == setSectionArray.split(':')[0]){
                            setK = k;
                            targetSetArray[section_id] = parseInt(pageNo);
                            if(addFlag)
                                newSetSectionString = newSetSectionString+setSectionArray.split(':')[0]+":"+parseInt(max_set+1)+",";
                            else newSetSectionString = newSetSectionString+setSectionArray.split(':')[0]+":"+pageNo+",";
                        }else{
                            if(k>setK){
                                targetSetArray[setSectionArray.split(':')[0]] = parseInt(setSectionArray.split(':')[1]);
                                newSetSectionString = newSetSectionString+setSectionArray+",";
                            }else{
                                targetSetArray[setSectionArray.split(':')[0]] = 1;
                                newSetSectionString = newSetSectionString+setSectionArray.split(':')[0]+":"+"1,";
                            }
                        }
                    });
                }else{
                    newSetSectionString ="";
                    //get this section's set array
                    $.each($("#setArray-"+sectionId).val().split(','),function(k,setsectionId){
                        if(setsectionId == "") return true;
                        fieldsectionSetArray.push(setsectionId);
                        if(setsectionId == section_id){
                            if(addFlag)
                                newSetSectionString = newSetSectionString+setsectionId+":"+parseInt(max_set+1)+",";
                            else newSetSectionString = newSetSectionString+setsectionId+":"+pageNo+",";
                        }
                        if($("#summary-"+setsectionId).length){
                            if($("#summary-"+setsectionId).find(".selected-row").length&&setsectionId in setArray){
                                targetSetArray[setsectionId] = parseInt($("#summary-"+setsectionId).find(".selected-row").attr('id').split("-")[3]);                            }else{
                                targetSetArray[setsectionId] = 1;
                                if(setsectionId == section_id)
                                    newSetSectionString = newSetSectionString+setsectionId+":1,";
                            }
                        }else{
                            if($("[id=pagination-section-"+setsectionId+"]").find(".selected-page").length&&setsectionId in setArray){
                                targetSetArray[setsectionId] = parseInt($("[id=pagination-section-"+setsectionId+"]").find(".selected-page").attr('id').split("-")[3]);
                            }else{
                                targetSetArray[setsectionId] = 1;
                            }
                        }
                    });
                    if(section_id in targetSetArray)
                        targetSetArray[section_id] = parseInt(pageNo);
                }
                $("[id^=save-btn"+orignalId+"]").attr("onclick","saveSection("+orignalId+","+pageNo+")");
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
                if(sectionId!=section_id){
                    if($("[id=summary-"+sectionId+"]").length){
                        $("[id=summary-"+sectionId+"]").find(".selected-row").removeClass("selected-row");
                        $("[id=summary-"+sectionId+"]").find("#section-"+sectionId+"-row-"+targetSetArray[sectionId]).addClass("selected-row");
                    }else{
                        $("#pagination-section-"+sectionId).find(".selected-page").removeClass(".selected-page");
                        $("#pagination-section-"+sectionId).find("#section-"+sectionId+"-page_number-"+targetSetArray[sectionId]).addClass(".selected-page");
                    }
                };
                $("[name=section\\["+orignalId+"\\]]").val(newSetSectionString.substr(0,newSetSectionString.length-1));
                fieldTargetArray = [];
                console.log(fieldsectionSetArray);
                console.log(targetSetArray);
                $.each(fieldsectionSetArray,function(k,v){
                    fieldTargetArray.push(targetSetArray[v]);
                });
                //type of 4
                if(!relateFlag) return true;
            };
            $(this).find("[id^=section-"+orignalId+"][name$=\\[id\\]]").each(function(){
                //<input id="section-2-sd_section_structures-9-sd_field_values-0-id" name="sd_field_values[2][9][id]" value="" type="hidden">
                let sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
                let valueFlag = false;
                let thisElement = $(this);
                let idholder = thisElement.attr('id').split('-');//section-65-sd_section_structures-0-sd_field_value_details-0-id
                let maxindex=0;
                console.log();
                if (section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){
                    $.each(section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                        let setMatch = true;
                        if(setFlag&&inputSetflag){
                            $.each(fieldTargetArray,function(k,v){
                                let set_NO = value.set_number+'';
                                if(v == parseInt(set_NO.split(',')[k])||(section[sectionKey].sd_section_structures[sectionStructureK].sd_field.id=='149'&&k!=0))
                                    return true;
                                setMatch = false;
                                return false;
                            });
                        }
                        if ((typeof value != "undefined")&&(setMatch)){
                            thisElement.val(value.id);
                            thisElement.attr('id',idholder[0]+'-'+idholder[1]+'-'+idholder[2]+'-'+idholder[3]+'-'+idholder[4]+'-'+index+'-'+idholder[6]);
                            valueFlag = true;
                            return false;
                        }
                        maxindex = maxindex+1;
                    });
                }
                if(valueFlag == false) {
                    $(this).val(null);
                    let idholder = thisElement.attr('id').split('-');

                    thisElement.attr('id',idholder[0]+'-'+idholder[1]+'-'+idholder[2]+'-'+idholder[3]+'-'+idholder[4]+'-'+maxindex+'-'+idholder[6]);
                };
            });
            if(addFlag) $("#addbtnalert-"+orignalId).show();
            else $("#addbtnalert-"+orignalId).hide();
            $(this).find("[id^=section-"+orignalId+"][name$=\\[field_value\\]]").each(function(){
                let sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
                let thisId = $(this).attr('id').split('-');
                let valueFlag = false;
                let thisElement = $(this);
                if (section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
                    $.each(section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                        let setMatch = true;
                        if(setFlag&&inputSetflag){
                            $.each(fieldTargetArray,function(k,v){
                                let set_NO = value.set_number+'';
                                    if(v == parseInt(set_NO.split(',')[k])||(section[sectionKey].sd_section_structures[sectionStructureK].sd_field.id=='149'&&k!=0))
                                        return true;
                                    setMatch = false;
                                    return false;
                            });
                        }
                        if ((typeof value != "undefined")&&(setMatch)){

                            valueFlag = true;
                            if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox'&&thisElement.attr('id').split('-')[2]!='unspecifieddate'&&thisElement.attr('id').split('-')[2]!='date')){
                                autoChangeflag = true;
                                thisElement.val(value.field_value).trigger('change');
                                autoChangeflag = false;

                            }else{
                                if(thisElement.attr('id').split('-')[2]=='unspecifieddate'){
                                    let fieldId = thisElement.attr('id').split('-')[3];
                                    let sectionId = thisElement.attr('id').split('-')[1];
                                    autoChangeflag = true;
                                    $("#unspecified-day_section-"+sectionId+"-unspecifieddate-"+fieldId).val(value.field_value.substring(0,2)).trigger('change');
                                    $("#unspecified-month_section-"+sectionId+"-unspecifieddate-"+fieldId).val(value.field_value.substring(2,4)).trigger('change');
                                    $("#unspecified-year_section-"+sectionId+"-unspecifieddate-"+fieldId).val(value.field_value.substring(4,8)).trigger('change');
                                    autoChangeflag = false;
                                }else if(thisElement.attr('id').split('-')[2]=='date'){
                                    let fieldId = thisElement.attr('id').split('-')[3];
                                    let sectionId = thisElement.attr('id').split('-')[1];
                                    console.log(value.field_value);//TODO CONVERT TO DATAFORMAT
                                    $("#specified-date-section-"+sectionId+"-date-"+fieldId).val(value.field_value);
                                }else if(thisElement.attr('id').split('-')[2]=='radio'){
                                    if(thisElement.val()==value.field_value) {
                                        thisElement.prop('checked',true);
                                    }else thisElement.prop('checked',false);
                                }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
                                    if(value.field_value.charAt(Number(thisElement.val())-1) == 1){
                                        thisElement.prop('checked',true);
                                    }else thisElement.prop('checked',false);
                                    if((typeof thisId[5] != "undefined")&&(thisId[5]=="final")) {thisElement.val(value.field_value); }
                                }
                            }
                        }
                    });
                }
                if(valueFlag == false) {
                    if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox'&&thisElement.attr('id').split('-')[2]!='unspecifieddate')){
                        autoChangeflag = true;
                        thisElement.val(null).trigger('change');
                        if(thisElement.attr('id').split('-')[2]=='date'){
                            let fieldId = thisElement.attr('id').split('-')[3];
                            let sectionId = thisElement.attr('id').split('-')[1];
                            $("#specified-date-section-"+sectionId+"-date-"+fieldId).val("");
                        }
                        autoChangeflag = false;
                    }else if(thisElement.attr('id').split('-')[2]=='unspecifieddate'){
                        let fieldId = thisElement.attr('id').split('-')[3];
                        let sectionId = thisElement.attr('id').split('-')[1];
                        autoChangeflag = true;
                        console.log(valueFlag);
                        $("#unspecified-day_section-"+sectionId+"-unspecifieddate-"+fieldId).val("00").trigger('change');
                        $("#unspecified-month_section-"+sectionId+"-unspecifieddate-"+fieldId).val("00").trigger('change');
                        $("#unspecified-year_section-"+sectionId+"-unspecifieddate-"+fieldId).val("0000").trigger('change');
                        autoChangeflag = false;
                    }else{
                        thisElement.prop('checked',false);
                        if((typeof thisId[5] != "undefined")&&(thisId[5]=="final")) {
                            val = "";
                            for (i = 0; i < thisId[4]; i++){
                                val = val+"0";
                            }
                            thisElement.val(val);
                        }
                    }


                    // if(thisElement.attr('id').split('-')[2]=='unspecifieddate'){

                    //     let fieldId = thisElement.attr('id').split('-')[3];
                    //     let sectionId = thisElement.attr('id').split('-')[1];console.log($("#unspecified-day_section-"+sectionId+"unspecifieddate"+fieldId));
                    //     autoChangeflag = true;
                    //     $("#unspecified-day_section-"+sectionId+"unspecifieddate"+fieldId).val("00").trigger('change');
                    //     $("#unspecified-month_section-"+sectionId+"unspecifieddate"+fieldId).val("00").trigger('change');
                    //     $("#unspecified-year_section-"+sectionId+"unspecifieddate"+fieldId).val("0000").trigger('change');
                    //     autoChangeflag = false;
                    // }
                };
            });

    });
    if(!(filterFlag||(section_id == 48 && addFlag))||resultflag !=null)
        renderSummaries(section_id, pageNo, addFlag);
    else if(addFlag){
        console.log($("table[id^=sectionSummary-48-sectionKey]").find('.selected-row'))
        $("table[id^=sectionSummary-48-sectionKey]").find('.selected-row').removeClass("selected-row");
    }
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

            $("#textHint").html(i18n.gettext("Sorry, no case matches"));

        }
    });
}
function deleteSection(sectionId, setNo,sectionKey){
    let request = {};
    if('child_section' in section[sectionKey]){
        request['child_section'] =  section[sectionKey].child_section;
        let added="";
        $.each(section[sectionKey].child_section.split(','), function(k, sectionid){
            if(sectionid =="") return true;
            $.each(section,function(k,sectiondetail){
                if(sectiondetail['id']==sectionid){
                    if('child_section' in sectiondetail)
                        added = added + sectiondetail['child_section']+',';
                    return false;
                }
            });
        });
        added = added.substr(0,added.length-1);
        request['child_section'] = request['child_section'] +added;
    }
    let i = 0;
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        data:request,
        type:'POST',
        url:'/sd-sections/deleteSection/'+tabId+'/'+caseId+'/'+sectionId+'/'+setNo+'/'+distribution_id,
        success:function(response){
            console.log(response);
            swal({
                icon: "success",
                title: i18n.gettext("This set has been deleted"),
              });
            section = $.parseJSON(response);
            // var country = $('#country_filter').val("");
            autoChangeflag = true;
            setPageChange(sectionId,1,false,2);
            autoChangeflag = false;
            // if(sectionId == 48) $('#country_filter').val(country).trigger("change");
            return false;
        },
        error:function(response){
            console.log(response.responseText);

            // $("#textHint").html("Sorry, no case matches");

        }
    });

}
function saveSection(sectionId,setNo){
    // if(!validation(sectionId)) return;
    let request = {};
    let error = 0;
    let fieldData ={};
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
            fieldData[field_id] = field_request;
        }
        sectionRequest[sectionId] = fieldData;
    });
    request['sd_field_values'] = sectionRequest;
    let sectionArray={};
    //eg: name=section[123]
    if($("[name=section\\["+sectionId+"\\]]").length){
        sectionArray[sectionId] = $("[name=section\\["+sectionId+"\\]]").val();
        request['sectionArray'] =sectionArray;
    }
    if(error) return false;
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-sections/saveSection/'+tabId+'/'+caseId+'/'+distribution_id,
        data:request,
        success:function(response){
            console.log(response);
            swal({
                icon: "success",
                title: i18n.gettext("This section has been saved"),
              });
            section = $.parseJSON(response);
            var country = $("#section-48-select-501").val();
            autoChangeflag = true;
            if(sectionId == 48) $('#country_filter').val("").trigger("change");
            setPageChange(sectionId,setNo, false, 1);
            if(sectionId == 48) $('#country_filter').val(country).trigger("change");
            autoChangeflag = false;
            return false;
        },
        error:function(response){
            console.log(response.responseText);
        }
    });
    return false;
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


//Distribution Decision End
function endDistributionDecision(){
    var activityList = {};
    let count = 0;
    $("[id^=selectedActivity]").each(function(){
        if($(this).prop('checked')){
            var detail = {};
            let id = $(this).attr('id').split('-')[1];
            detail['linkId'] = $(this).val();
            detail['next-activity-id'] = id;
            detail['receiverId'] = $("#receiverId-"+id).val();
            detail['content'] = $('#query-content-'+id).text()
            console.log(detail);
            activityList[count] = detail;
            count++;
        }
    });
    let request ={
        'senderId':userId,
        'activityList':activityList,
    }
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/distribute/'+caseNo+'/'+version,
        data:request,
        success:function(response){console.log(response);
            response = JSON.parse(response);

        },
        error:function(response){
            console.log(response.responseText);
        },
    });
}

// sign off then push backward && push to next step
function action(type){
    text = "";
    if(type==1){
        console.log(distribution_id);
        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            type:'POST',
            url:'/sd-users/searchNextAvailable/'+caseNo+'/'+version+'/'+distribution_id,
            success:function(response){
                console.log(response);
                response = JSON.parse(response);
                console.log(response);
                if('one' in response) {
                    response = response['one'];
                    text +="<div class=\"modal-header\">";
                    text +="<h3 class=\"modal-title text-center w-100\" id=\"exampleModalLabel\">"+i18n.gettext("Sign Off")+"</h3>";
                    text +="<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">";
                    text +="<span aria-hidden=\"true\">&times;</span>";
                    text +="</button>";
                    text +="</div>";
                    text +="<div class=\"modal-body text-center m-3\">";
                    text +="<p class=\"lead\">"+i18n.gettext("Next activity is")+": "+response['actvity']['activity_name']+"</p>";
                    text +="<input type=\"hidden\" id=\"next-activity-id\" value=\""+response['actvity']['id']+"\">";
                    text +="<div class=\"form-group\">";
                    text +="<label><h5>"+i18n.gettext("Comment")+"</h5></label>";
                    text +="<textarea class=\"form-control\" id=\"query-content\" rows=\"3\"></textarea>";
                    text +="</div>";
                    text +="<hr class=\"my-4\">";
                    if(response['previousUserOnNextActivity'].length > 0){
                        text +="<div><h6>"+i18n.gettext("Previous User On This Case On Next Activity")+": </h6>";
                        $.each(response['previousUserOnNextActivity'],function(k,v){
                            text +=v['user']['firstname']+" "+v['user']['lastname']+"("+v['company']['company_name']+"), ";
                        });
                        text +="</div>";
                        text +="<hr class=\"my-4\">";
                    }
                    //add function to chose most avaiable person
                    text +="<div class=\"form-group\">";
                    text +="<label><h6>"+i18n.gettext("Select person you want to send to")+":</h6></label><select class=\"form-control\" id=\"receiverId\">";
                    $.each(response['users'],function(k,v){
                        text +="<option value="+v['id']+">"+v['firstname']+" "+v['lastname'];
                        if(v['sd_cases'].length > 0)
                            text +="(currently working on "+v['sd_cases']['0']['casesCount']+" cases)";
                        else text +="(currently working on 0 case)";
                        text +="</option>";
                    });
                    text +="</select>";
                    text +="</div>";
                    text +="<h3>"+i18n.gettext("Field Required")+"</h3>";
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
                    text +="<div class=\"text-center\"><button class=\"btn btn-primary w-25\" onclick=\"forward()\">"+i18n.gettext("Confirm")+"</button></div>";
                    text +="</div>";
                }else{
                    text +="<div class=\"modal-header\">";
                    text +="<h3 class=\"modal-title text-center w-100\" id=\"exampleModalLabel\">"+i18n.gettext("Distribution Decision")+"</h3>";
                    text +="<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">";
                    text +="<span aria-hidden=\"true\">&times;</span>";
                    text +="</button>";
                    text +="</div>";
                    text +="<div class=\"modal-body text-center m-3\">";
                    text +="<div class=\"form-group\">";
                    text +="</div>";
                    text +="<h5>Case Info:</h5>";
                    text +="<table class=\"table table-hover\">";
                    text +="<thead>";
                    text +="<tr>";
                    text +="<th scope=\"col\">"+i18n.gettext("Select")+"</th>";
                    text +="<th scope=\"col\">"+i18n.gettext("Activity")+"</th>";
                    text +="<th scope=\"col\">"+i18n.gettext("Previous User On This Activity")+"</th>";
                    text +="<th scope=\"col\">"+i18n.gettext("Available User")+"</th>";
                    text +="<th scope=\"col\">"+i18n.gettext("Comment")+"</th>";
                    text +="</tr>";
                    text +="</thead>";
                    $.each(response,function(k,detail){
                        if(k=="caseValidate") return true;
                        activity = detail['activity'];
                        text += "<div id=\"previous_activity-"+activity['id']+"\" hidden>"+JSON.stringify(activity['users'])+"</div> ";
                        text +="<tr>";
                        text += "<td><input value=\""+activity['links']['id']+"\" type=\"checkbox\" id=\"selectedActivity-"+activity['id']+"\"></td>";
                        text += "<td>"+activity['activity_name']+"</td>";
                        text +="<td>";
                        $.each(activity['previousUserOnNextActivity'],function(k,v){
                            text +=v['user']['firstname']+" "+v['user']['lastname']+"("+v['company']['company_name']+")<br>";
                        });
                        text += "</td><td>";
                        text +="<select id=\"receiverId-"+activity['id']+"\">";
                        $.each(detail['users'],function(k,v){
                            text +="<option value=\""+v['id']+"\">";
                            text +=v['firstname']+" "+v['lastname'];
                            num = 0;
                            if(v['sd_cases']!="")
                                num = v['sd_cases']['0']['casesCount']
                            text +="("+i18n.translate("currently working on %d case").ifPlural(num, "currently working on %d cases").fetch(num)+")";
                            text +="</option>";
                        });
                        text +="</select></td><td>";
                        text +="<textarea class=\"form-control\" id=\"query-content-"+activity['id']+"\" rows=\"3\"></textarea></td>";
                        text +="</tr>";
                    });
                    text +="</table>";
                    text +="<hr class=\"my-4\">";
                    text +="<div class=\"text-center\"><button class=\"btn btn-primary w-25\" onclick=\"endDistributionDecision()\">"+i18n.gettext("Confirm")+"</button></div>";
                    text +="</div>";
                    $('#action-text-hint').html(text);
                }
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
                text +="<h3 class=\"modal-title text-center w-100\" id=\"exampleModalLabel\">"+i18n.gettext("Push Backward")+"</h3>";
                text +="<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">";
                text +="<span aria-hidden=\"true\">&times;</span>";
                text +="</button>";
                text +="</div>";
                text +="<div class=\"modal-body text-center m-3\">";
                text +="<div class=\"form-group\">";
                text +="<label><h5>"+i18n.gettext("Comment")+"</h5></label>";
                text +="<textarea class=\"form-control\" id=\"query-content\" rows=\"3\"></textarea>";
                text +="</div>";
                text +="<h5>Case Info:</h5>";
                text +="<table class=\"table table-hover\">";
                text +="<thead>";
                text +="<tr>";
                text +="<th scope=\"col\">"+i18n.gettext("Activity")+"</th>";
                text +="<th scope=\"col\">"+i18n.gettext("Previous User On This Activity")+"</th>";
                text +="<th scope=\"col\">"+i18n.gettext("Available User")+"</th>";
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
                                num = 0;
                                if(v['sd_cases']!="")
                                num = v['sd_cases']['0']['casesCount']
                                text +="("+i18n.translate("currently working on %d case").ifPlural(num, "currently working on %d cases").fetch(num)+")";
                        });
                        text +="</td></tr>";
                    }
                });
                text +="</table>";
                text +="<hr class=\"my-4\">";
                //add function to chose most avaiable person
                text +="<div class=\"form-group\">";
                text +="<label>"+i18n.gettext("Which activity do you want to push to?")+"</label>";
                text +="<select class=\"form-control w-50 mx-auto\" id=\"next-activity-id\" >";
                text +="<option value=\"null\">"+i18n.gettext("Select Activity")+"</option>";
                $.each(response,function(k,v){
                    text += "<option value=\""+v['id']+"\">"+v['activity_name']+"</option>";
                });
                text +="</select>";
                text +="<label class=\"my-2\">"+i18n.gettext("Select person you want to send to")+":</label><select class=\"form-control w-50 mx-auto\" id=\"receiverId\">";
                text +="</select>";
                text +="</div>";
                text +="<div class=\"text-center\"><button class=\"btn btn-primary w-25\" onclick=\"backward()\">"+i18n.gettext("Confirm")+"</button></div>";
                text +="</div>";
                $('#action-text-hint').html(text);
                $('#next-activity-id').change(function(){
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
        url:'/sd-cases/forward/'+caseNo+'/'+version+"/0"+"/"+distribution_id,
        data:request,
        success:function(response){
            console.log(response);
            if(response == "success")
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
    // $(document).ready(function() {
    //     let unsaved = false;

    //     $("input:not(:button,:submit),textarea,select").change(function(){   //triggers change in all input fields including text type
    //         unsaved = true;
    //     });

    //     // $(window).bind('beforeunload', function(){
    //     //     if(unsaved){
    //     //          return swal({
    //     //             title: "Are you sure?",
    //     //             text: "Your data is changed, are you sure you want to complete?",
    //     //             icon: "warning",
    //     //             buttons: true,
    //     //             dangerMode: true,
    //     //         })}
    //     //   });

    //     window.onbeforeunload = function (){
    //         if(unsaved){
    //             let _msg = 'Your data is changed, are you sure you want to complete?';
    //             return  _msg;
    //             // return swal({
    //             //     title: "Are you sure?",
    //             //     text: "Your data is changed, are you sure you want to complete?",
    //             //     icon: "warning",
    //             //     buttons: true,
    //             //     dangerMode: true,
    //             // })
    //         }
    //     };
    // });

    // Show "Save" button when any input change
    $(document).ready(function() {
        $("input,textarea,select[name$=\\[field_value\\]]").change(function () {
            if(!autoChangeflag)
                $("[id^=save-btn"+$(this).attr('id').split('-')[1]+"]").show();
         });

         $("input[id^=specified-date]").change(function (){
            if(!autoChangeflag)
                $("[id^=save-btn"+$(this).attr('id').split('-')[3]+"]").show();
         });
         $("select[id^=unspecified]").change(function (){
            if(!autoChangeflag)
                $("[id^=save-btn"+$(this).attr('id').split('-')[2]+"]").show();
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
