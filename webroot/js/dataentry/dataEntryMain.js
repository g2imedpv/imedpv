jQuery(function($) {
    $(document).ready(paginationReady());
});

$(document).ready(function(){

    $('[name$=\\[field_value\\]').change(function(){
        var id = $(this).attr('id').split('-');
        $('[id=section-'+id[1]+'-error_message-'+id[3]+']').text();
        $('[id=section-'+id[1]+'-error_message-'+id[3]+']').hide();

        $("div[id=section-"+id[1]+"-field-"+id[3]+']').each(function(){
            var field_value = null;
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
                var rule = $(this).find("[name$=\\[field_rule\\]]").val().split("-");
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
        var request={
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
                var text ="<table class=\"table table-hover w-100\">";
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
                var id = $(this).attr('id').split('-');
                var inputElement = $('[id^='+id[0]+'-'+id[1]+'-'+id[2]+'-'+id[3]+'][id$=final]');
                var Value = inputElement.val();
                Value = Value.substring(0, $(this).val()-1) + "1" + Value.substring($(this).val());
                inputElement.val(Value);
            }else{
                var id = $(this).attr('id').split('-');
                var inputElement = $('[id^='+id[0]+'-'+id[1]+'-'+id[2]+'-'+id[3]+'][id$=final]');
                var Value = inputElement.val();
                Value = Value.substring(0, $(this).val()-1) + "0" + Value.substring($(this).val());
                inputElement.val(Value);
            }
        });
});
function level2setPageChange(section_id, pageNo, addFlag=null){
    var child_section =  $("[id^=child_section][id$=section-"+section_id+"]").attr('id').split('-');
    child_section_id = child_section[1].split(/[\[\]]/);
    child_section_id = jQuery.grep(child_section_id, function(n, i){
        return (n !== "" && n != null);
    });
    var max_set_no  = 0 ;
    $(child_section_id).each(function(k, v){
        var sectionKey = $("[id^=add_set-"+v+"]").attr('id').split('-')[3];
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

// function setPageChange(section_id, pageNo, addFlag=null, pFlag) {
//     $("[id^=save-btn"+section_id+"]").hide();
//     /**get sectionkey */
//     if($("[name^=Input-"+section_id+"]").length){
//         var sectionId = $("[name^=Input-"+section_id+"]").attr('name').split('-');// ["Input", "65", "sectionKey", "1"]
//         var parentSection=section[sectionId[3]].parent_section;
//         //var parentSectionKey=$("[name^=Input-"+parentSection+"]").attr('name').split('-')[3];
//         var childSection=section[sectionId[3]].child_section;
//         //var childSectionKey=$("[name^=Input-"+childSection+"]").attr('name').split('-')[3];
//         //var max_set_no = 0;
//         // $("[id^=section-"+sectionId[1]+"-page_number").each(function(){
//         //     max_set_no = Math.max(Number($(this).attr('id').split('-')[3]), max_set_no);  
//         // });
//         //if ((Number(setNo)+Number(steps) <= 0)||(Number(setNo)+Number(steps)>max_set_no)) {console.log("set_no not avaiable"); return;};
//         // if ((((pageNo <= 0)&&(addFlag!=1))||pageNo>max_set_no)&&pFlag!=1) {console.log("set_no not avaiable"); return;};
//         // if(addFlag == 1){
//         //     $("[id=addbtnalert-"+sectionId[1]+"]").show();
//         // }else{
//         //     $("[id=addbtnalert-"+sectionId[1]+"]").hide()
//         // }
//         // if((addFlag == 1)&&(pFlag!=1)) {
//         //     pageNo = max_set_no+1;
//         //     $("[id^=add_set-"+sectionId[1]+"]").hide();
//         //     }else {$("[id^=add_set-"+sectionId[1]+"]").show();}

//         //change [id]
//         $("[id^=section-"+sectionId[1]+"][name$=\\[id\\]]").each(function(){
//             var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
//             var valueFlag = false;
//             var thisElement = $(this);
//             var idholder = thisElement.attr('id').split('-');//section-65-sd_section_structures-0-sd_field_values-0-id
//             var maxindex=0;
//             if (section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
//                 $.each(section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
//                     if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
//                         var set_number=value.sd_section_sets.set_array.split(",");
//                         if ((typeof value != 'undefined')&&(set_number[0]== pageNo)){
//                             thisElement.val(value.id);
//                             thisElement.attr('id',idholder[0]+'-'+idholder[1]+'-'+idholder[2]+'-'+idholder[3]+'-'+idholder[4]+'-'+index+'-'+idholder[6]);
//                             valueFlag = true;
//                             return false;
//                         }
//                         maxindex = maxindex+1;
//                     }
//                 });
//             }
//             if(valueFlag == false) {
//                 $(this).val(null);
//                 var idholder = thisElement.attr('id').split('-');
               
//                 thisElement.attr('id',idholder[0]+'-'+idholder[1]+'-'+idholder[2]+'-'+idholder[3]+'-'+idholder[4]+'-'+maxindex+'-'+idholder[6]);
//             };    
//         });
//         $("[id^=section-"+sectionId[1]+"][name$=\\[set_number\\]]").each(function(){
//             var newSetNumber = pageNo;
//             $(this).val(newSetNumber);
//         });
//         //change product value
//         $("[id^=section-"+sectionId[1]+"][name$=\\[field_value\\]]").each(function(){
//             var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
//             var thisId = $(this).attr('id').split('-');
//             var valueFlag = false;
//             var thisElement = $(this);
//             if (section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
//                 $.each(section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
//                     if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
//                         var set_number=value.sd_section_sets.set_array.split(",");
//                         if ((typeof value != 'undefined')&&(set_number[0]== pageNo)){
//                             if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
//                                 thisElement.val(value.field_value).trigger('change');
//                                 valueFlag = true;
                            
//                             }else{
//                                 if(thisElement.attr('id').split('-')[2]=='radio'){
//                                     if(thisElement.val()==value.field_value) {
//                                         thisElement.prop('checked',true);
//                                         valueFlag = true;
//                                     }else thisElement.prop('checked',false);
//                                 }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
//                                     valueFlag = true;
//                                     if(value.field_value.charAt(Number(thisElement.val())-1) == 1){
//                                         thisElement.prop('checked',true);
//                                     }else thisElement.prop('checked',false);
//                                     if((typeof thisId[5] != 'undefined')&&(thisId[5]=="final")) {thisElement.val(value.field_value); }
//                                 }
//                             }
//                         }
                        
//                     }
//                 });
//             }
//             if(valueFlag == false) {
//                 if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
//                     thisElement.val(null).trigger('change');;
//                 }else{
//                     thisElement.prop('checked',false);
//                     if((typeof thisId[5] != 'undefined')&&(thisId[5]=="final")) {
//                         val = "";
//                         for (i = 0; i < thisId[4]; i++){
//                             val = val+"0";
//                         }
//                         thisElement.val(val);
//                     }
//                 }
//             };
//         });
//         //change child filed value
//         $("[id^=section-"+childSection+"][name$=\\[field_value\\]]").each(function(){
//             var childSectionKey = $("[name^=Input-"+childSection+"]").attr('name').split("-")[3];
//             var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
//             var thisId = $(this).attr('id').split('-');
//             var valueFlag = false;
//             var thisElement = $(this);
//             thisElement.val(" ").trigger('change');
//             if (section[childSectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
//                 $.each(section[childSectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
//                     if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
//                         var set_number=value.sd_section_sets.set_array.split(",");
//                         if ((typeof value != 'undefined')&&(set_number[0]== 1)&&(set_number[1]==pageNo)){
//                             if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
//                                 thisElement.val(value.field_value).trigger('change');
//                                 valueFlag = true;
//                             }else{
//                                 if(thisElement.attr('id').split('-')[2]=='radio'){
//                                     if(thisElement.val()==value.field_value) {
//                                         thisElement.prop('checked',true);
//                                         valueFlag = true;
//                                     }else thisElement.prop('checked',false);
//                                 }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
//                                     valueFlag = true;
//                                     if(value.field_value.charAt(Number(thisElement.val())-1) == 1){
//                                         thisElement.prop('checked',true);
//                                     }else thisElement.prop('checked',false);
//                                     if((typeof thisId[5] != 'undefined')&&(thisId[5]=="final")) {thisElement.val(value.field_value); }
//                                 }
//                             }
//                         }
                        
//                     }
//                 });
//             }
//         });
//         //change table value
//         $("[id^=section-"+childSection+"-row]").each(function changeTableValue(){
//             var childSectionKey =  $("[name^=Input-"+childSection+"]").attr('name').split("-")[3];
//             var childSectionSet = $(this).attr('id').split("-")[3];
//             var fieldArray = section[childSectionKey].sd_section_summary.fields.split(",");
//             var fieldId = $(this).attr('id').split("-")[4];
//             var fieldKey = fieldArray.indexOf(fieldId);
//             var thisElement = $(this);
//             thisElement.html(" ").trigger('change');
//             if (section[childSectionKey].sd_section_summary.sdFields[fieldKey].sd_field_values.length>=1){
//                 var count = 0;
//                 $.each(section[childSectionKey].sd_section_summary.sdFields[fieldKey].sd_field_values, function(index,value){ 
//                     if((typeof value.sd_section_sets[0] !='undefined')&&(typeof value.sd_section_sets[0].set_array !='undefined')){
//                         var set_number=value.sd_section_sets[0].set_array.split(",");
//                         var fieldType=section[childSectionKey].sd_section_summary.sdFields[fieldKey].sd_element_type_id;
//                         if ((set_number[0]== childSectionSet)&&(set_number[1]==pageNo)){
//                             if((fieldType != 1 )&& (fieldType != 3) && (fieldType != 4)){
//                                 thisElement.html(value.field_value).trigger('change');
//                                 valueFlag = true;   
//                             }else{
//                                 $.each(section[childSectionKey].sd_section_summary.sdFields[fieldKey].sd_field_value_look_ups ,function(index,lookups){
//                                     if(lookups.value ==value.field_value ){
//                                         thisElement.html(lookups.caption).trigger('change');
//                                     }
//                                 });
//                             }
//                         }
//                     }
//                 });
//             }
        
//         });
//         //change child fields value
//         // $("[id^=section-"+childSection+"][name$=\\[field_value\\]]").each(function(){ 
//         //     var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
//         //     var thisId = $(this).attr('id').split('-');
//         //     var valueFlag = false;
//         //     var thisElement = $(this);
//         //     if (section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
//         //         $.each(section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
//         //             if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
//         //                 var set_number=value.sd_section_sets.set_array.split(",");
//         //                 if ((typeof value != 'undefined')&&(set_number[0]== pageNo)&&(set_number[1]==parentSet)){
//         //                     if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
//         //                         thisElement.val(value.field_value).trigger('change');
//         //                         valueFlag = true;
//         //                     }else{
//         //                         if(thisElement.attr('id').split('-')[2]=='radio'){
//         //                             if(thisElement.val()==value.field_value) {
//         //                                 thisElement.prop('checked',true);
//         //                                 valueFlag = true;
//         //                             }else thisElement.prop('checked',false);
//         //                         }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
//         //                             valueFlag = true;
//         //                             if(value.field_value.charAt(Number(thisElement.val())-1) == 1){
//         //                                 thisElement.prop('checked',true);
//         //                             }else thisElement.prop('checked',false);
//         //                             if((typeof thisId[5] != 'undefined')&&(thisId[5]=="final")) {thisElement.val(value.field_value); }
//         //                         }
//         //                     }
//         //                 }
                        
//         //             }
//         //         });
//         //     }
//         //     if(valueFlag == false) {
//         //         if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
//         //             thisElement.val(null).trigger('change');;
//         //         }else{
//         //             thisElement.prop('checked',false);
//         //             if((typeof thisId[5] != 'undefined')&&(thisId[5]=="final")) {
//         //                 val = "";
//         //                 for (i = 0; i < thisId[4]; i++){
//         //                     val = val+"0";
//         //                 }
//         //                 thisElement.val(val);
//         //             }
//         //         }
//         //     };
//         // });
//         //change level 2,3 field value
//         $("[id$=addableSectionNo-"+parentSection+"]").each(function(){
//             var parentSet=$(this).attr('value');
//             $("[id^=section-"+sectionId[1]+"][name$=\\[field_value\\]]").each(function(){
//                 var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
//                 var thisId = $(this).attr('id').split('-');
//                 var valueFlag = false;
//                 var thisElement = $(this);
//                 if (section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
//                     $.each(section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
//                         if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
//                             var set_number=value.sd_section_sets.set_array.split(",");
//                             if ((typeof value != 'undefined')&&(set_number[0]== pageNo)&&(set_number[1]==parentSet)){
//                                 if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
//                                     thisElement.val(value.field_value).trigger('change');
//                                     valueFlag = true;
//                                 }else{
//                                     if(thisElement.attr('id').split('-')[2]=='radio'){
//                                         if(thisElement.val()==value.field_value) {
//                                             thisElement.prop('checked',true);
//                                             valueFlag = true;
//                                         }else thisElement.prop('checked',false);
//                                     }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
//                                         valueFlag = true;
//                                         if(value.field_value.charAt(Number(thisElement.val())-1) == 1){
//                                             thisElement.prop('checked',true);
//                                         }else thisElement.prop('checked',false);
//                                         if((typeof thisId[5] != 'undefined')&&(thisId[5]=="final")) {thisElement.val(value.field_value); }
//                                     }
//                                 }
//                             }
                            
//                         }
//                     });
//                 }
//                 if(valueFlag == false) {
//                     if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
//                         thisElement.val(null).trigger('change');;
//                     }else{
//                         thisElement.prop('checked',false);
//                         if((typeof thisId[5] != 'undefined')&&(thisId[5]=="final")) {
//                             val = "";
//                             for (i = 0; i < thisId[4]; i++){
//                                 val = val+"0";
//                             }
//                             thisElement.val(val);
//                         }
//                     }
//                 };
//             });
//         });
//         //<input id="section-65-set_array-176-addableSectionNo-65" name="sd_field_values[65][0][set_array][65]" value="change" type="hidden">
//         $("[id$=addableSectionNo-"+sectionId[1]+"]").each(function(){
//             var newSetNumber = pageNo;
//             $(this).val(newSetNumber);
//         });

//         $("[id^=left_set-"+sectionId[1]+"]").attr('id', 'left_set-'+sectionId[1]+'-'+sectionId[2]+'-'+sectionId[3]+'-setNo-'+pageNo);
//         $("[id^=left_set-"+sectionId[1]+"]").attr('onclick','setPageChange('+sectionId[1]+','+Number(Number(pageNo)-1)+')');
//         $("[id^=right_set-"+sectionId[1]+"]").attr('id', 'right_set-'+sectionId[1]+'-'+sectionId[2]+'-'+sectionId[3]+'-setNo-'+pageNo);
//         $("[id^=right_set-"+sectionId[1]+"]").attr('onclick','setPageChange('+sectionId[1]+','+Number(Number(pageNo)+1)+')');
//         $("[id^=section-"+sectionId[1]+"-page_number-]").css('font-weight', 'normal');
//         $("[id=section-"+sectionId[1]+"-page_number-"+pageNo+"]").css('font-weight', 'bolder');
 
//     }
// }
function setPageChange(section_id, pageNo) {
    if($("[name^=Input-"+section_id+"]").length){
        var sectionId = $("[name^=Input-"+section_id+"]").attr('name').split('-');// ["Input", "65", "sectionKey", "1"]
        //get inheritance
        var children = new Array();////get child relationship
        var i=1;
        children[0]=section_id;
        var childSection =section[sectionId[3]].child_section;
        while(childSection.length!=0){
            children[i]=childSection;
            var childSectionKey = $("[name^=Input-"+childSection+"]").attr('name').split('-')[3];
            i=i+1;
            childSection =section[childSectionKey].child_section;  
        } 
        var parents = new Array();//get parent relationship
        var i=0;
        var parentSection =section[sectionId[3]].parent_section;
        while(typeof $("[name^=Input-"+parentSection+"]").attr('name')!='undefined'){
            parents[i]=parentSection;
            var parentSectionKey = $("[name^=Input-"+parentSection+"]").attr('name').split('-')[3];
            i=i+1;
            parentSection =section[parentSectionKey].parent_section;  
        }
        var inherit = new Array;
        inherit = parents.reverse().concat(children);
        level_number=inherit.length;
        //change [id]
        $("[id$=addableSectionNo-"+sectionId[1]+"]").each(function(){
            var newSetNumber = pageNo;
            $(this).val(newSetNumber);
        });
        $("[id^=section-"+sectionId[1]+"][name$=\\[id\\]]").each(function(){
            var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
            var thisElement = $(this);
            var idholder = thisElement.attr('id').split('-');//section-65-sd_section_structures-0-sd_field_values-0-id
            var maxindex=0;
            if (section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
                $.each(section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                    if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
                        var set_number=value.sd_section_sets.set_array.split(",");
                        if ((typeof value != 'undefined')&&(set_number[0]== pageNo)){
                            thisElement.val(value.id);
                            thisElement.attr('id',idholder[0]+'-'+idholder[1]+'-'+idholder[2]+'-'+idholder[3]+'-'+idholder[4]+'-'+index+'-'+idholder[6]);
                            valueFlag = true;
                            return false;
                        }
                        maxindex = maxindex+1;
                    }
                });
            }   
        });
        if($("[name^=sd_field_values\\["+section_id+"\\]\\[0\\]\\[set_array\\]]").length == 1){
            //change table value
            $("[id^=section-"+inherit[1]+"-row]").each(function changeTableValue(){
                var sectionKey = $("[name^=Input-"+inherit[1]+"]").attr('name').split('-')[3];
                var rowSet = $(this).attr('id').split("-")[3];
                var fieldArray = section[sectionKey].sd_section_summary.fields.split(",");
                var fieldId = $(this).attr('id').split("-")[4];
                var fieldKey = fieldArray.indexOf(fieldId);
                var thisElement = $(this);
                thisElement.html(" ").trigger('change');
                if (section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_field_values.length>=1){
                    $.each(section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_field_values, function(index,value){ 
                        if((typeof value.sd_section_sets[0] !='undefined')&&(typeof value.sd_section_sets[0].set_array !='undefined')){
                            var set_number=value.sd_section_sets[0].set_array.split(",");
                            var fieldType=section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_element_type_id;
                            if ((set_number[0]==rowSet )&&((set_number[1]==pageNo)||(set_number[1]=='*'))){
                                if((fieldType != 1 )&& (fieldType != 3) && (fieldType != 4)){
                                    thisElement.html(value.field_value).trigger('change');
                                    valueFlag = true;   
                                }else{
                                    $.each(section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_field_value_look_ups ,function(index,lookups){
                                        if(lookups.value ==value.field_value ){
                                            thisElement.html(lookups.caption).trigger('change');
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            });
            $("[id^=section-"+inherit[2]+"-row]").each(function changeTableValue(){
                var sectionKey = $("[name^=Input-"+inherit[2]+"]").attr('name').split('-')[3];
                var rowSet = $(this).attr('id').split("-")[3];
                var fieldArray = section[sectionKey].sd_section_summary.fields.split(",");
                var fieldId = $(this).attr('id').split("-")[4];
                var fieldKey = fieldArray.indexOf(fieldId);
                var thisElement = $(this);
                thisElement.html(" ").trigger('change');
                if (section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_field_values.length>=1){
                    $.each(section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_field_values, function(index,value){ 
                        if((typeof value.sd_section_sets[0] !='undefined')&&(typeof value.sd_section_sets[0].set_array !='undefined')){
                            var set_number=value.sd_section_sets[0].set_array.split(",");
                            var fieldType=section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_element_type_id;
                            if ((set_number[0]==rowSet )&&((set_number[1]==1)||(set_number[1]=='*'))&&(set_number[2]==pageNo)){
                                if((fieldType != 1 )&& (fieldType != 3) && (fieldType != 4)){
                                    thisElement.html(value.field_value).trigger('change');
                                    valueFlag = true;   
                                }else{
                                    $.each(section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_field_value_look_ups ,function(index,lookups){
                                        if(lookups.value ==value.field_value ){
                                            thisElement.html(lookups.caption).trigger('change');
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            });

            //change field value
            $("[id^=section-"+sectionId[1]+"][name$=\\[field_value\\]]").each(function(){
                var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
                var thisId = $(this).attr('id').split('-');
                var thisElement = $(this);
                thisElement.val(" ").trigger('change');
                if (section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
                    $.each(section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                        if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
                            var set_number=value.sd_section_sets.set_array.split(",");
                            if ((typeof value != 'undefined')&&(set_number[0]== pageNo)){
                                if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
                                    thisElement.val(value.field_value).trigger('change');   
                                }else{
                                    if(thisElement.attr('id').split('-')[2]=='radio'){
                                        if(thisElement.val()==value.field_value) {
                                            thisElement.prop('checked',true);   
                                        }else thisElement.prop('checked',false);
                                    }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
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
            });
            $("[id^=section-"+inherit[1]+"][name$=\\[field_value\\]]").each(function(){
                var sectionKey = $("[name^=Input-"+inherit[1]+"]").attr('name').split('-')[3];
                var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
                var thisId = $(this).attr('id').split('-');
                var thisElement = $(this);
                thisElement.val(" ").trigger('change');
                if (section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){
                    $.each(section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                        if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
                            var set_number=value.sd_section_sets.set_array.split(",");
                            if ((typeof value != 'undefined')&&(set_number[0]== 1)&&(set_number[1]==pageNo)){
                                if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
                                    thisElement.val(value.field_value).trigger('change');
                                }else{
                                    if(thisElement.attr('id').split('-')[2]=='radio'){
                                        if(thisElement.val()==value.field_value) {
                                            thisElement.prop('checked',true);   
                                        }else thisElement.prop('checked',false);
                                    }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
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
            });
            $("[id^=section-"+inherit[2]+"][name$=\\[field_value\\]]").each(function(){
                var sectionKey = $("[name^=Input-"+inherit[2]+"]").attr('name').split('-')[3];
                var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
                var thisId = $(this).attr('id').split('-');
                var thisElement = $(this);
                thisElement.val(" ").trigger('change');
                if (section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
                    $.each(section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                        if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
                            var set_number=value.sd_section_sets.set_array.split(",");
                            if ((typeof value != 'undefined')&&(set_number[0]== 1)&&(set_number[1]==1)&&(set_number[2]==pageNo)){
                                if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
                                    thisElement.val(value.field_value).trigger('change');
                                        
                                }else{
                                    if(thisElement.attr('id').split('-')[2]=='radio'){
                                        if(thisElement.val()==value.field_value) {
                                            thisElement.prop('checked',true);   
                                        }else thisElement.prop('checked',false);
                                    }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
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
            });
        }else if ($("[name^=sd_field_values\\["+section_id+"\\]\\[0\\]\\[set_array\\]]").length == 2){
            var ParentSection = $("[name$=\\[set_array\\]\\["+inherit[0]+"\\]]").attr('value');
            $("[id^=section-"+inherit[2]+"-row]").each(function changeTableValue(){
                var sectionKey = $("[name^=Input-"+inherit[2]+"]").attr('name').split('-')[3];
                var rowSet = $(this).attr('id').split("-")[3];
                var fieldArray = section[sectionKey].sd_section_summary.fields.split(",");
                var fieldId = $(this).attr('id').split("-")[4];
                var fieldKey = fieldArray.indexOf(fieldId);
                var thisElement = $(this);
                thisElement.html(" ").trigger('change');
                if (section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_field_values.length>=1){
                    $.each(section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_field_values, function(index,value){ 
                        if((typeof value.sd_section_sets[0] !='undefined')&&(typeof value.sd_section_sets[0].set_array !='undefined')){
                            var set_number=value.sd_section_sets[0].set_array.split(",");
                            var fieldType=section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_element_type_id;
                            if ((set_number[0]==rowSet )&&((set_number[1]==pageNo)||(set_number[1]=='*'))&&(set_number[2]==ParentSection)){
                                if((fieldType != 1 )&& (fieldType != 3) && (fieldType != 4)){
                                    thisElement.html(value.field_value).trigger('change');
                                    console.log(value.field_value);
                                    valueFlag = true;   
                                }else{
                                    $.each(section[sectionKey].sd_section_summary.sdFields[fieldKey].sd_field_value_look_ups ,function(index,lookups){
                                        if(lookups.value ==value.field_value ){
                                            thisElement.html(lookups.caption).trigger('change');
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            });
            $("[id^=section-"+inherit[1]+"][name$=\\[field_value\\]]").each(function(){
                var sectionKey = $("[name^=Input-"+inherit[1]+"]").attr('name').split('-')[3];
                var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
                var thisId = $(this).attr('id').split('-');
                var thisElement = $(this);
                thisElement.val(" ").trigger('change');
                if (section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){
                    $.each(section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                        if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
                            var set_number=value.sd_section_sets.set_array.split(",");
                            if ((typeof value != 'undefined')&&(set_number[0]== pageNo)&&(set_number[1]==ParentSection)){
                                if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
                                    thisElement.val(value.field_value).trigger('change');
                                }else{
                                    if(thisElement.attr('id').split('-')[2]=='radio'){
                                        if(thisElement.val()==value.field_value) {
                                            thisElement.prop('checked',true);   
                                        }else thisElement.prop('checked',false);
                                    }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
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
            });
            $("[id^=section-"+inherit[2]+"][name$=\\[field_value\\]]").each(function(){
                var sectionKey = $("[name^=Input-"+inherit[2]+"]").attr('name').split('-')[3];
                var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
                var thisId = $(this).attr('id').split('-');
                var thisElement = $(this);
                thisElement.val(" ").trigger('change');
                if (section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
                    $.each(section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                        if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
                            var set_number=value.sd_section_sets.set_array.split(",");
                            if ((typeof value != 'undefined')&&(set_number[0]== 1)&&(set_number[1]==pageNo)&&(set_number[2]==ParentSection)){
                                if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
                                    thisElement.val(value.field_value).trigger('change');
                                        
                                }else{
                                    if(thisElement.attr('id').split('-')[2]=='radio'){
                                        if(thisElement.val()==value.field_value) {
                                            thisElement.prop('checked',true);   
                                        }else thisElement.prop('checked',false);
                                    }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
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
            });
        }else if ($("[name^=sd_field_values\\["+section_id+"\\]\\[0\\]\\[set_array\\]]").length == 3){
            var ParentSectionOne = $("[name$=\\[set_array\\]\\["+inherit[0]+"\\]]").attr('value');
            var ParentSectionTwo = $("[name$=\\[set_array\\]\\["+inherit[1]+"\\]]").attr('value');
            $("[id^=section-"+inherit[2]+"][name$=\\[field_value\\]]").each(function(){
                var sectionKey = $("[name^=Input-"+inherit[2]+"]").attr('name').split('-')[3];
                var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
                var thisId = $(this).attr('id').split('-');
                var thisElement = $(this);
                thisElement.val(" ").trigger('change');
                if (section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){//TODO
                    $.each(section[sectionKey].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                        if((typeof value.sd_section_sets !='undefined')&&(typeof value.sd_section_sets.set_array !='undefined')){
                            var set_number=value.sd_section_sets.set_array.split(",");
                            if ((typeof value != 'undefined')&&(set_number[0]== pageNo)&&(set_number[1]==ParentSectionTwo)&&(set_number[2]==ParentSectionOne)){
                                if((thisElement.attr('id').split('-')[2] != 'radio')&&(thisElement.attr('id').split('-')[2]!='checkbox')){
                                    thisElement.val(value.field_value).trigger('change');
                                        
                                }else{
                                    if(thisElement.attr('id').split('-')[2]=='radio'){
                                        if(thisElement.val()==value.field_value) {
                                            thisElement.prop('checked',true);   
                                        }else thisElement.prop('checked',false);
                                    }else if(thisElement.attr('id').split('-')[2]=='checkbox'){
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
            });
        }
        $("[id^=left_set-"+sectionId[1]+"]").attr('id', 'left_set-'+sectionId[1]+'-'+sectionId[2]+'-'+sectionId[3]+'-setNo-'+pageNo);
        $("[id^=left_set-"+sectionId[1]+"]").attr('onclick','setPageChange('+sectionId[1]+','+Number(Number(pageNo)-1)+')');
        $("[id^=right_set-"+sectionId[1]+"]").attr('id', 'right_set-'+sectionId[1]+'-'+sectionId[2]+'-'+sectionId[3]+'-setNo-'+pageNo);
        $("[id^=right_set-"+sectionId[1]+"]").attr('onclick','setPageChange('+sectionId[1]+','+Number(Number(pageNo)+1)+')');
        $("[id^=section-"+sectionId[1]+"-page_number-]").css('font-weight', 'normal');
        $("[id=section-"+sectionId[1]+"-page_number-"+pageNo+"]").css('font-weight', 'bolder');
    }
        
        
}

function searchWhoDra(){
    var request = {
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
    $("[id^=pagination-l2").each(function(){
        var hsectionid = $(this).attr('id').split('-')[3];
        var child_section_element = $("[id^=child_section][id$=section-"+hsectionid+"]").attr('id');
        var child_section_id = child_section_element.split('-')[1];
        child_section_id = child_section_id.split(/[\[\]]/);
        child_section_id = jQuery.grep(child_section_id, function(n, i){
            return (n !== "" && n != null);
        });
        var max_set_no  = 0 ;
        $(child_section_id).each(function(k, v){
            var sectionKey = $("[id^=add_set-"+v+"]").attr('id').split('-')[3];
            $(section[sectionKey].sd_section_structures).each(function(k,v){
                $.each(v.sd_field.sd_field_values,function(key, value){
                    // console.log("v:");console.log(v);console.log(value);console.log(value.set_number);
                    max_set_no = Math.max(value.set_number, max_set_no);
                })
            })
            // section[section_Id[2]].sd_section_structures[sectionStructureK].sd_field.sd_field_values
            $("[id^=pagination-section-"+v+"]").hide();
        });
        if (max_set_no==0) {
            $("[id^=child_section][id$=section-"+hsectionid+"]").hide();
            max_set_no = 1;
        }else {
            $("[id^=child_section][id$=section-"+hsectionid+"]").show();
            $("[id=delete_section-"+hsectionid+"]").show();
        }
        var text= "";
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
function l2deleteSection(sectionId){
    pageNo = $("#showpagination-"+sectionId).find("[id^=right_set]").attr('id').split('-setNo-')[1];
    child_section = $("[id^=child][id$=section-"+sectionId+"]").attr('id').split('-');
    child_section_id = child_section[1].split(/[\[\]]/);
    child_section_id = jQuery.grep(child_section_id, function(n, i){
        return (n !== "" && n != null);
    });
    $(child_section_id).each(function(k,v){
        deleteSection(v);
    });
    $("[id=l2section-"+sectionId+"-page_number-"+pageNo+"]").remove();
    $("[id^=l2section-"+sectionId+"-page_number]").each(function(){
        if($(this).attr('id').split('-page_number-')[1]>pageNo){

            $pageIdHolder=$(this).attr('id').split('-page_number-');
            $(this).attr('id',$pageIdHolder[0]+'-page_number-'+Number($pageIdHolder[1]-1));
            $pageaIdHolder = $(this).children().attr('id').split('-page_number-');
            $(this).children().attr('id',$pageaIdHolder[0]+'-page_number-'+Number($pageaIdHolder[1]-1));
            $(this).children().attr('onclick',"level2setPageChange("+sectionId+","+Number($pageIdHolder[1]-1)+")");
            $pageNo = Number($(this).text()-1);
            $(this).children("a").text($pageNo);
        }
    })
    if(pageNo!=1){
        pageNo --;
    }
    level2setPageChange(sectionId,pageNo);
    $("[id=section-"+sectionId+"-page_number-"+pageNo+"]").css('font-weight', 'bold');
}
// function validation(sectionId){
//     var validate = 1;
//     $("div[id^=section-"+sectionId+"-field]").each(function(){
//         var field_value = null;
//         if($(this).find("[name$=\\[field_value\\]]").length){
//             field_type = $(this).find("[name$=\\[field_value\\]]").attr('id').split('-')[2];
//             if((field_type!="radio")&&(field_type!="checkbox")){
//                  field_value = $(this).find("[name$=\\[field_value\\]]").val();
//             }else{
//                 if(field_type=="radio"){
//                     $(this).find("[name$=\\[field_value\\]]").each(function(){
//                         if($(this).prop('checked')){
//                             field_value = $(this).val();}
//                     });
//                 }else{
//                     field_value = $(this).find("[id$=final]").val();
//                 }
//             }
//         }
//         if((parseInt($(this).find("[name$=\\[is_required\\]]").val()))&&(field_value=="")){
//             console.log('required  '+$(this).attr('id'));
//             $(this).find("[id^=section-"+sectionId+"-error_message-]").show();
//             $(this).find("[id^=section-"+sectionId+"-error_message-]").text('is required');
//         }
//         if($(this).find("[id^=section-"+sectionId+"-error_message-]").is(":visible")) validate = 0;
//     });
//     return validate;
// }
function deleteSection(sectionId, pcontrol=false){

    var request = {};
    var savedArray = [];

    $("div[id^=section-"+sectionId+"-field]").each(function(){
        if($(this).find("[name$=\\[field_value\\]]").length){
            var field_id = $(this).attr('id').split('-')[3];
            var field_request =
            {
                'id': $(this).children("[name$=\\[id\\]]").val(),
                'sd_field_id':$(this).children("[name$=\\[sd_field_id\\]]").val(),
                'set_number':$(this).children("[name$=\\[set_number\\]]").val(),
            };
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
            //alert("This set has been deleted");

            swal({
                icon: "success",
                title: "This set has been deleted",
              });

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
            var sectionIdOriginal =  $("[id^=save-btn"+sectionId+"]").attr('id');
            var section_Id = sectionIdOriginal.split('-');

            var max_set_no = 0
            for(var k in request){
                var setNum = $("[id$=field-"+k+"]").children("[id^=section-"+sectionId+"-set_number]").val();
                var fieldvalueK = $("[id$=field-"+k+"]").children("[id^=section-"+sectionId+"-sd_section_structures]").attr('id').split('-')[5];
                var sectionStructureK = $("[id^=section-"+sectionId+"-set_number-"+k+"]").attr('name').split(/[\[\]]/)[3];
                section[section_Id[2]].sd_section_structures[sectionStructureK].sd_field.sd_field_values = savedArray[k];
                $(section[section_Id[2]].sd_section_structures[sectionStructureK].sd_field.sd_field_values).each(function(k,v){
                    if(typeof v != 'undefined')
                    max_set_no = Math.max(v.set_number, max_set_no);
                });
            }
            if (max_set_no!=0){
                $("[id^=right_set-"+sectionId+"]").prev().remove();
                var previousPageNo = $("[id^=right_set-"+sectionId+"]").prev().attr('id').split('-page_number-')[1];
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
    // if(!validation(sectionId)) return;
    var request = {};
    var savedArray = [];
    var error = 0;

    $("div[id^=section-"+sectionId+"-field]").each(function(){
        if($(this).find("[id^=section-"+sectionId+"-error_message-]").is(":visible")) error = 1;
        var field_value = null;
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
            var field_id = $(this).attr('id').split('-')[3];
            var field_request =
            {
                'sd_field_id' : $(this).children("[name$=\\[sd_field_id\\]]").val(),
                'id': $(this).children("[name$=\\[id\\]]").val(),
                'set_number':$(this).children("[name$=\\[set_number\\]]").val(),
                'field_value': field_value
            };
            request[field_id] = field_request;
        }

    });
        if(error) return;
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-sections/saveSection/'+caseId,
        data:request,
        success:function(response){
            console.log(response);
            //alert("This section has been saved");
            swal({
                icon: "success",
                title: "This section has been saved",
              });
            savedArray = $.parseJSON(response);
            var sectionIdOriginal =  $("[id^=save-btn"+sectionId+"]").attr('id');
            var section_Id = sectionIdOriginal.split('-');
            var max_set_no  = 0
            for(var k in savedArray){
                var sectionStructureK = $("[id^=section-"+sectionId+"-set_number-"+k+"]").attr('name').split(/[\[\]]/)[3];
                var fieldvalueK = $("[id$=field-"+k+"]").children("[id^=section-"+sectionId+"-sd_section_structures]").attr('id').split('-')[5];
                var setNum = $("[id$=field-"+k+"]").children("[id^=section-"+sectionId+"-set_number]").val();
                if(savedArray[k]!=''){

                    $(section[section_Id[2]].sd_section_structures[sectionStructureK].sd_field.sd_field_values).each(function(k,v){
                        if(typeof v != 'undefined')
                        max_set_no = Math.max(v.set_number, max_set_no);
                    });
                    section[section_Id[2]].sd_section_structures[sectionStructureK].sd_field.sd_field_values = savedArray[k];
                }
            }
            console.log(max_set_no);console.log(setNum);
            setPageChange(sectionId,setNum);
            if(max_set_no<setNum){
                pageNew = 0;
                if($("[id^=add_set-"+sectionId+"]").length){
                    add_set_id = $("[id^=add_set-"+sectionId+"]").attr('id').split('-');
                    pageNew=setNum;
                    if(pageNew!=1)
                    $("<li class=\"page-item\" id=\"section-"+sectionId+"-page_number-"+pageNew+"\" onclick=\"setPageChange("+sectionId+","+pageNew+")\"><a class=\"page-link\">"+pageNew+"</a></li>").insertBefore("[id^=right_set-"+sectionId+"]");
                    $("[id^=add_set-"+sectionId+"]").show();
                    $("[id^=add_set-"+sectionId+"]").attr('id', add_set_id[0]+'-'+add_set_id[1]+'-'+add_set_id[2]+'-'+add_set_id[3]+'-'+add_set_id[4]+'-'+pageNew);
                }else{
                    $("#pagination-section-"+sectionId).show();
                }
                paginationReady();
                $("[id=section-"+sectionId+"-page_number-"+pageNew+"]").css('font-weight', 'bold');
                $("[id^=l2section]").find("[id$=page_number-"+pageNew+"]").css('font-weight', 'bold');
            };
            $("[id=addbtnalert-"+sectionId+"]").hide();
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
                    var previousSectionK ="";
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
                    var users =  $.parseJSON($('#previous_activity-'+$(this).val()).html());
                    var text =""
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
    var request ={
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
            // window.location.href = "/sd-cases/caselist";
        },
        error:function(response){
            console.log(response.responseText);
            }
        });
}
function backward(){
    var request ={
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
        var unsaved = false;

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
                var _msg = 'Your data is changed, are you sure you want to complete?';
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
    //         var foa = $("option:selected", this).text();
    //         $('#section-22-text-347').val(foa);
    //     });

    //     $('#section-22-select-192').change(function() {
    //         var roa = $("option:selected", this).text();
    //         $('#section-22-text-286').val(roa);
    //     });

    // });

});
