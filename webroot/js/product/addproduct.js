var accessment_resource_list={};
var distribution_resource_list={};
var workflow_k = 0;
var workflow_list =[];
var cro_list=[];
var accessment_permission_list = {};
var distribution_permission_list ={};
var distribution_list =[];
var distribution_No = 0;
$(document).ready(function() {
    var unsaved = false;

    $("input:not(:button,:submit),textarea,select").change(function(){   //triggers change in all input fields including text type
        unsaved = true;
    });

    window.onbeforeunload = function (){
        if(unsaved){
            return 'Your data is changed, are you sure you want to complete?';
        }
    };
     /**  
     * dashboard advanced search:activity due date and submission due date calendar
     */
    $('#start_date,#end_date').datepicker({dateFormat: 'dd/mm/yy'});
});
function selectCro(id, typeFlag){
    var show_resource_list = {};
    if(typeFlag == 1){
        $('[id^=conass]').attr('id', 'conass-accessment-'+id);
        show_resource_list = accessment_resource_list;
    }else{
        $('[id^=conass]').attr('id', 'conass-distribution-'+id);
        show_resource_list = distribution_resource_list;
    }
    var member_text = "";
    $(show_resource_list[workflow_k][id]['member_list']).each(function(k,v){
        member_text +="<div class=\"personnel\" id=\"userid_"+v.id+"\">"+v.firstname+" "+v.lastname+"</div>";
    });
    $('#personnelDraggable').html(member_text);

    var workflow_manager_text = "";
    $(show_resource_list[workflow_k][id]['workflow_manager']).each(function(k,v){
        workflow_manager_text +="<div class=\"personnel\" id=\"userid_"+v.id+"\">"+v.firstname+" "+v.lastname+"</div>";
    });
    $('#workflow_manager-add').html(workflow_manager_text);

    var team_resources_text = "";
    $(show_resource_list[workflow_k][id]['team_resources']).each(function(k,v){
        team_resources_text +="<div class=\"personnel\" id=\"userid_"+v.id+"\">"+v.firstname+" "+v.lastname+"</div>";
    });
    $('#team_resources-add').html(team_resources_text);
    croDroppableArea();
}
function removeCro(id, typeFlag){
    var workflowType = "";
    swal({
        title: "Are you sure?",
        text: "This record would be removed permanently once deleted",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
            if(typeFlag==1) workflowType ="accessment";else workflowType = "distribution"
            var crocaption = $('#'+workflowType+'-crocompany-' + id).text();
            $('#'+workflowType+'-crocompany-' + id).closest('tr').remove();
            $("#"+workflowType+"-croname").append($("<option></option>").attr("value",id).text(crocaption));
            swal("This record has been deleted!", {
                icon: "success",
            });
        } else {
          swal("This record is safe!", {
            icon: "success",
        });
        }
      });
}
jQuery(function($) {  // In case of jQuery conflict
    $(document).ready(function(){
        $('[id^=defDistriBtn]').click(function(){
            var defDistriSequence = $(this).attr('id').split('-')[1];
            console.log(defDistriSequence)
            $('#defDistriContent-' + defDistriSequence).show();
            $('#custDistriContent-' + defDistriSequence).hide();
        });
        $('[id^=custDistriBtn]').click(function(){
            var custDistriSequence = $(this).attr('id').split('-')[1];
            console.log(custDistriSequence);
            $('#custDistriContent-' + custDistriSequence).show();
            $('#defDistriContent-' + custDistriSequence).hide();
        });
        
        $('[id^=write]').change(function(){
            var id = $(this).attr('id').split('-')[2];
            let tabid = $(this).attr('id').split('-')[1];
            if($(this).is(':checked')) $('[id=read-'+tabid+'-'+id+']').prop('checked',true);
            // console.log(paretnid);
            while(typeof $("[id=section-"+id+"]").parent().parent().attr('id')!='undefined'&&$("[id=section-"+id+"]").parent().parent().attr('id').split('-')[0]=="section"){
                id = $("[id=section-"+id+"]").parent().parent().attr('id').split('-')[1];
                if($(this).is(':checked')) $('[id=read-'+tabid+'-'+id+']').prop('checked',true);
            }
        });
        $('[id^=read]').change(function(){
            var id = $(this).attr('id').split('-')[2];
            let tabid = $(this).attr('id').split('-')[1];
            // console.log(paretnid);
            while(typeof $("[id=section-"+id+"]").parent().parent().attr('id')!='undefined'&&$("[id=section-"+id+"]").parent().parent().attr('id').split('-')[0]=="section"){
                id = $("[id=section-"+id+"]").parent().parent().attr('id').split('-')[1];
                if($(this).is(':checked')) $('[id=read-'+tabid+'-'+id+']').prop('checked',true);
            }
        });


        // $('#submit_accessment_country').click(function(){
        //     var default_text = "<p>This is default workflow and cannot be changed</p>";
        //     var customize_text = "";
        //     var country = $('#select-accessment-country').val();
        //     $(accessment_workflow_structure[country]['sd_workflow_activities']).each(function(k,v){

        //         default_text +="<li class=\"defworkflowstep\">";
        //             default_text +="<div class=\"card w-100 h-25 my-2\">";
        //                 default_text +="<div class=\"card-body p-3\">";
        //                     default_text +="<h5 class=\"card-title\"><b>"+v.activity_name+"</b></h5>";
        //                     default_text +="<p class=\"card-text\">"+v.description+"</p>";
        //                 default_text +="</div>";
        //             default_text +="</div>";
        //         default_text +="</li>"

        //         customize_text +="<li class=\"custworkflowstep\">";
        //             customize_text +="<input value="+v.activity_name+" name=\"[workflow][0][workflow_activities]["+k+"]activity_name\" type=\"hidden\">";
        //             customize_text +="<input value="+v.description+" name=\"[workflow][0][workflow_activities]["+k+"]description\" type=\"hidden\">";
        //             customize_text +="<div class=\"card w-100 h-25 my-2\">";
        //                 customize_text +="<div class=\"card-body p-3\">";
        //                     customize_text +="<button class=\"close closewf\">&times;</button>";
        //                     customize_text +="<h5 class=\"card-title\"><b>"+v.activity_name+"</b></h5>";
        //                     customize_text +="<p class=\"card-text\">"+v.description+"</p>";
        //                 customize_text +="</div>";
        //             customize_text +="</div>";
        //         customize_text +="</li>"
        //     });
        //     $('#default_accessment_workflow').html(default_text);
        //     $('#accessment-sortable').html(customize_text);
        // });
        // $("#sd_sponsor_company_id").change(function(){ 
        //     var request = {'sponsor_id': $("#sd_sponsor_company_id").val()};

        //     console.log(request);
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-Token': csrfToken
        //         },
        //         type:'POST',
        //         url:'/sd-products/searchCroCompanies',
        //         data:request,
        //         success:function(response){
        //             console.log(response);
        //             cro_list = [];
        //             var result = $.parseJSON(response);
        //             $.each(result, function(k,caseDetail){
        //                     cro_list.push({'id':k, 'name':caseDetail});
        //                 });
        //         },
        //         error:function(response){

        //         }
        //     });
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-Token': csrfToken
        //         },
        //         type:'POST',
        //         url:'/sd-products/searchCallcenterCompanies',
        //         data:request,
        //         success:function(response){
        //             console.log(response);
        //             var result = $.parseJSON(response);
        //             $.each(result, function(k,caseDetail){
        //                     call_center_list[k]={'name': caseDetail};
        //                 });
        //         },
        //         error:function(response){}
        //     });
        // });
    })
    // Select workflow manager and staff to CRO
    $('[id^=conass]').click(function() {
        var cro_id = $(this).attr('id').split('-')[2];
        var workflowType = $(this).attr('id').split('-')[1];
        var textmanager  = "";
        var textstaff = "";
        var member_list = [];
        var team_resources = [];
        var workflow_manager = [];
        if(workflowType == "accessment"){
            show_resource_list = accessment_resource_list;
        }else{
            show_resource_list = distribution_resource_list;
        }
        // var managerChosed = $(".stackDrop1 > .personnel").text().match(/[A-Z][a-z]+/g);
        // var staffChosed = $(".stackDrop2 > .personnel").text().match(/[A-Z][a-z]+/g);
        $("#personnelDraggable").children("div").each(function(){
            var user_info = {};
            user_info.id = $(this).attr('id').split('_')[1];
            user_info.firstname = $(this).text().split(' ')[0];
            user_info.lastname = $(this).text().split(' ')[1];
            member_list.push(user_info);
            console.log(member_list);
        });
        show_resource_list[workflow_k][cro_id].member_list = member_list;
        $("#team_resources-add").children("div").each(function(){
            var user_info = {};
            user_info.id = $(this).attr('id').split('_')[1];
            user_info.firstname = $(this).text().split(' ')[0];
            user_info.lastname = $(this).text().split(' ')[1];
            team_resources.push(user_info);
            textstaff += "<div class=\"personnel\">"+$(this).text()+"</div>";
            console.log($(this).text());
        });
        show_resource_list[workflow_k][cro_id].team_resources = team_resources;
        $("#workflow_manager-add").children("div").each(function(){
            var user_info = {};
            user_info.id = $(this).attr('id').split('_')[1];
            user_info.firstname = $(this).text().split(' ')[0];
            user_info.lastname = $(this).text().split(' ')[1];
            workflow_manager.push(user_info);
            textmanager += "<div class=\"personnel\">"+$(this).text()+"</div>";
            console.log($(this).text());
        });
        show_resource_list[workflow_k][cro_id].workflow_manager = workflow_manager;
        // $.each(managerChosed, function(k,manager){
        //     textmanager += manager + "; ";
        // });
        $("#"+workflowType+"-cromanager-"+cro_id).html(textmanager);
        // $.each(staffChosed, function(k,staff){
        //     textstaff += "<div id=>"+staff + "; </div>";
        // });
        $("#"+workflowType+"-crostaff-"+cro_id).html(textstaff);
    });

    $('[id^=confirm_][id$=_activities]').click(function(){
        var workflowType = $(this).attr('id').split('_')[1];
        $('.step_backward').each(function(){
            $(this).prop("disabled", false);
        });
        console.log('#customize_'+workflowType+'_workflow');
        if ($('#default_'+workflowType+'_workflow').is(':visible') && $('#customize_'+workflowType+'_workflow').is(':hidden'))
        {
            $('#customize_'+workflowType+'_workflow_div, #default_'+workflowType+'_btn').hide();
            $('#default_'+workflowType+'_T').show();
            $('#ifdef').addClass("mx-auto w-50");

        }else 
        if (($('#default_'+workflowType+'_workflow').is(':hidden') && $('#customize_'+workflowType+'_workflow').is(':visible')))
        {
            if(!$('#custom_'+workflowType+'_workflow_name').val()  ) {
                $('#custom_'+workflowType+'_workflow_name-validate').show().delay(2000).fadeOut();
                return false;
            }
            else if (
                !$('#custom_'+workflowType+'_workflow_description').val()  ) {
                $('html,body').animate({
                    scrollTop: $("#customize_"+workflowType+"_workflow").offset().top
                    });
                $('#custom_'+workflowType+'_workflow_description-validate').show().delay(2000).fadeOut();
                return false;
            }
            else {
                $('#default_'+workflowType+'_workflow_div, #cust_'+workflowType+'_btn, .closewf').hide();
                $('#customize_'+workflowType+'_T, #undocho-'+workflowType+'-WF').show();
                $('#'+workflowType+'-sortable, #draggable').addClass("mx-auto w-50");
                $('#customize_'+workflowType+'_workflow').find('ul').hide();
                $('#custom_'+workflowType+'_workflow_name').attr('disabled',true);
                $('#custom_'+workflowType+'_workflow_description').attr('disabled',true);
                $('#cust-'+workflowType+'-workflowstep').find('button').hide();
                var order = 1;
                $('#'+workflowType+'-sortable').find('.card-body').each(function(){
                    console.log($(this).children('.input-group'));
                    var workflowTypeFlag = 0;
                    if(workflowType=="distribution") workflowTypeFlag = 1;
                    var text ="<button type=\"button\" id=\"cust-"+workflowType+"-permission-"+order+"\" onclick=\"sectionPermission("+order+",2,"+workflowTypeFlag+")\" class=\"btn btn-primary btn-sm mx-2\" data-toggle=\"modal\" data-target=\"#selectPermission\">Set Permission</button>";
                    $(this).append(text);
                    order ++;
                });
                $(this).hide();
                //TODO JUN 24st
                $('#'+workflowType+'-sortable').find('.card-body').append( '<div class="input-group w-25 mx-auto"><i class="fas fa-arrow-up gobackstep"></i><input type="text" class="step_backward form-control form-control-sm backstep_input" aria-label="Back Steps" aria-describedby="backSteps">Workdays in 7 days case<input type="text" class="due_day-7 form-control form-control-sm backstep_input">Workdays in 15 days case<input type="text" class="due_day-15 form-control form-control-sm backstep_input"> Workdays in 90 days case<input type="text" class="due_day-90 form-control form-control-sm backstep_input"></div>');
                $('#custworkflowname').next('#erraccessmentWorkflow').remove(); // *** this line have been added ***
                $("#"+workflowType+"-sortable").sortable({ disabled: true });
            }
        };
        $('#undocho-'+workflowType+'-con').hide();
        $(this).hide();
        $('#submit_'+workflowType+'_workflow').show();
        $('#undo_'+workflowType+'_activities').show();
    });
    $('[id^=undo_][id$=_activities]').click(function(){
        var workflowType = $(this).attr('id').split('_')[1];
        if ($('#default_'+workflowType+'_workflow').is(':visible') && $('#customize_'+workflowType+'_workflow').is(':hidden'))
        {
            $('#customize_'+workflowType+'_workflow_div').show();
            $('#default_'+workflowType+'_btn').show();
            $('#default_'+workflowType+'_T, #undocho-'+workflowType+'-WF').hide();
        }
        if (($('#default_'+workflowType+'_workflow').is(':hidden') && $('#customize_'+workflowType+'_workflow').is(':visible')))
        {
            $('[id^=cust-'+workflowType+'-permission]').each(function(){
                $(this).remove();
            });
            $("#"+workflowType+"-sortable").sortable({ disabled: false });
            $('#def-'+workflowType+'-workflowstep').hide()
            $('#default_'+workflowType+'_workflow_div, #cust_'+workflowType+'_btn, .closewf').show();
            $('#customize_'+workflowType+'_T, #undocho-'+workflowType+'-WF').hide();
            $('#'+workflowType+'-sortable, #draggable').removeClass("mx-auto w-50");
            $('#customize_'+workflowType+'_workflow').find('ul').show();
            $('#custom_'+workflowType+'_workflow_name').attr('disabled',false);
            $('#custom_'+workflowType+'_workflow_description').attr('disabled',false);
            $('li.cust-'+workflowType+'-workflowstep').find('button').show();
            $('#'+workflowType+'-sortable').find('.input-group').remove();

        }
        $('#confirm_'+workflowType+'_activities').show();
        $('#submit_'+workflowType+'_workflow').hide();
        $(this).hide();
        $('#undocho-'+workflowType+'-con').show();
    });
    $('[id^=submit_][id$=_workflow]').click(function() {
        var finished = 1;
        var workflowType = $(this).attr('id').split('_')[1];
        $('[id^=cust-'+workflowType+'-permission]').each(function(){
            if($(this).text() != "View Permission"){
                finished = 0;
                // TODO notify that THIS activity's permission not finished
                return false;
            }
            var activity_Id = $(this).attr('id').split('-')[3];
            var workflowTypeFlag = 0;
            if (workflowType =="distribution") workflowTypeFlag = 1;
            $(this).prop('onclick','sectionPermission('+activity_Id+',3,'+workflowTypeFlag+')');
        });
        if(!finished)return false;
        
        $(this).hide();
        $('#confirm-'+workflowType+'-WFlist, #undocho-'+workflowType+'-WF').show();
        $('#undo_'+workflowType+'_activities').hide();
        $('#choose-'+workflowType+'-company').show();
        // $('#undochoaccessmentWF, #chooseDistri').show();
        //$('#chooseaccessmentCompany').show();
        // var cro_text = "";
        // $.each(cro_list, function(k,cro){
        //     cro_text +="<option value=\""+cro.id+"\">"+cro.name+"</option>";
        //     });
        // $('#croname').html(cro_text);
        if(workflowType=="accessment")
            workflow_list[workflow_k].activities = [];
        else distribution_list[distribution_No].activities = [];
        $('#'+workflowType+'-croname').html("");
        $.each(cro_companies,function(key,value){
            $('#'+workflowType+'-croname').prepend('<option value="'+key+'">'+value+'</option>');
        });
        $('#'+workflowType+'-crotable').html("");
        if (($('#default_'+workflowType+'_workflow').is(':visible') && $('#customize_'+workflowType+'_workflow').is(':hidden')))
        {
            var order_no = 1;
            $('#default_'+workflowType+'_workflow').find(".card-body").each(function(){
                var activities_list = {};
                $(this).find(".card-title").each(function(){
                    activities_list.activity_name = $(this).text();
                });
                $(this).find(".card-text").each(function(){
                    activities_list.activity_description = $(this).text();
                });
                $(this).find(".step_backward").each(function(){
                    activities_list.step_backward = $(this).val();
                });
                activities_list.order_no = order_no;
                if(workflowType=="accessment") workflow_list[workflow_k].activities.push(activities_list);
                else distribution_list[workflow_k].activities.push(activities_list);
                order_no++;
            });
            if(workflowType=="accessment"){
                workflow_list[workflow_k].id = $('#default_'+workflowType+'_workflow_id').val();
                workflow_list[workflow_k].workflow_type = 0;
                workflow_list[workflow_k].workflow_name = $('#default_'+workflowType+'_workflow_name').val();
                workflow_list[workflow_k].workflow_description= $('#default_'+workflowType+'_workflow_description').val();
            }else{
                distribution_list[distribution_No].id = $('#default_'+workflowType+'_workflow_id').val();
                distribution_list[distribution_No].workflow_type = 0;
                distribution_list[distribution_No].workflow_name = $('#default_'+workflowType+'_workflow_name').val();
                distribution_list[distribution_No].workflow_description= $('#default_'+workflowType+'_workflow_description').val();
            }
        }
        else if (($('#default_'+workflowType+'_workflow').is(':hidden') && $('#customize_'+workflowType+'_workflow').is(':visible')))
        {
            $('.step_backward').each(function(){
                $(this).prop("disabled", true);
            })
            var order_no = 1;
            $('#'+workflowType+'-sortable').find(".card-body").each(function(){
                var activities_list = {};
                $(this).find(".card-title").each(function(){
                    activities_list.activity_name = $(this).text();
                });
                $(this).find(".card-text").each(function(){
                    activities_list.activity_description = $(this).text();
                });
                $(this).find(".step_backward").each(function(){
                    console.log($(this))
                    activities_list.step_backward = $(this).val();
                });
                activities_list.order_no = order_no;
                activities_list.due_day = $(this).find(".due_day-7").val()+','+$(this).find(".due_day-15").val()+','+$(this).find(".due_day-90").val();
                if(workflowType=="accessment") workflow_list[workflow_k].activities.push(activities_list);
                else distribution_list[workflow_k].activities.push(activities_list);
                order_no++;
            });
            if(workflowType=="accessment"){
                workflow_list[workflow_k].workflow_type = 1;
                workflow_list[workflow_k].workflow_name = $('#custom_'+workflowType+'_workflow_name').val();
                workflow_list[workflow_k].workflow_description= $('#custom_'+workflowType+'_workflow_description').val();
            }else{
                distribution_list[distribution_No].workflow_type = 1;
                distribution_list[distribution_No].workflow_name = $('#custom_'+workflowType+'_workflow_name').val();
                distribution_list[distribution_No].workflow_description= $('#custom_'+workflowType+'_workflow_description').val();
            }
        }
    });
    $('[id^=addNew-][id$=-WL]').click(function() {
        // function addNewWorkflow() {
            var workflowType = $(this).attr('id').split('-')[1];
            if(workflowType=="accessment"){
                accessment_resource_list[workflow_k] = {};
                workflow_list[workflow_k] = {};
            }else{
                distribution_resource_list[workflow_k] = {};
                distribution_list[distribution_No] = {};
            }
            // var call_center_text = "<option value=\"\">Select Call Center</option>";
            // $.each(call_center_list, function(k,call_center){
            //     call_center_text +="<option value=\""+k+"\">"+call_center.name+"</option>";
            //     });
            $("#select-"+workflowType+"-country, #callCenter").prop("disabled", false);
            $("#select-"+workflowType+"-country").val("");
            // $('#callCenter').html(call_center_text);
            $('#addNew-'+workflowType+'-WL').hide();
            $('#'+workflowType+'-workflowlist').slideUp();
            $('#cho-'+workflowType+'-workflow').slideDown();
            $('#exit_'+workflowType+'_workflow').show();
            $('#submit_'+workflowType+'_country').show();
            $('#choose_'+workflowType+'_wf').hide();
        // }
        // if(!$('#workflow_table > tr > td').first().text()  ) {
        //         addNewWorkflow();
        //     }
        // else {
        //     swal({
        //         title: "Do you want to reuse the previous info?",
        //         icon: "info",
        //         buttons: ["No", "Yes"]
        //         })
        //         .then((yes) => {
        //             if (yes) {
        //             swal("New Workflow would based on the previous one", {
        //                 icon: "success",
        //             });
        //             addNewWorkflow();
        //             }
        //             else {
        //             swal("New Workflow has been added", {
        //                 icon: "success",
        //             });
        //             addNewWorkflow();
        //             $('#cho-accessment-workflow, #chooseDistri, #chooseaccessmentCompany').find('select,input').val('');
        //             }
        //         });
        //     }


    });
    $('[id^=exit_][id$=_workflow]').click(function(){
        var workflowType = $(this).attr('id').split('_')[1];
        
        $('#cho-'+workflowType+'-workflow').slideUp();
        $('#'+workflowType+'-workflowlist').slideDown();
        $('#addNew-'+workflowType+'-WL').show();
    });
    $('[id^=submit_][id$=_country]').click(function() {
        var workflowType = $(this).attr('id').split('_')[1];
        $('#confirm_'+workflowType+'_activities').hide();
        // if(
        //     !$('#select-accessment-country').val()  ) {
        //     $('#select-accessment-country-validate').show().delay(2000).fadeOut();;
        // }
        // else
        if ((workflowType=='accessment')&&(!$('#callCenter').val())) {
            $('#callCenter-validate').show().delay(2000).fadeOut();;
        }
        else
        {
            $('#default_'+workflowType+'_workflow_div').show();
            $('#customize_'+workflowType+'_workflow_div').show();
            $('#cust_'+workflowType+'_btn, .closewf').show();
            $('#customize_'+workflowType+'_T, #undocho'+workflowType+'WF').hide();
            $('#customize_'+workflowType+'_workflow').find('ul').show();
            $('#customize_'+workflowType+'_workflow').hide();
            $('#custom_'+workflowType+'_workflow_name').attr('disabled',false);
            $('#custom_'+workflowType+'_workflow_description').attr('disabled',false);
            $('#cust-'+workflowType+'-workflowstep').find('button').show();
            $('#'+workflowType+'-sortable').find('.input-group').remove();
            $('#customize_'+workflowType+'_workflow').find('input').prop("disabled", false);
            $("#"+workflowType+"-sortable").sortable({ disabled: false });
            $('#'+workflowType+'-sortable, #draggable').removeClass("mx-auto w-50");
            $('#ifdef').removeClass("mx-auto w-50");
            $('#default_'+workflowType+'_workflow').hide();

            $('#submit_'+workflowType+'_workflow').hide();
            $('#exit_'+workflowType+'_workflow').hide();

            $(this).hide();
            $('#undocho-'+workflowType+'-con').show();
            $("#select-"+workflowType+"-country, #callCenter ").prop("disabled", true);
            $('#choose_'+workflowType+'_wf').show();
            $('#default_'+workflowType+'_T').hide();
            $('#default_'+workflowType+'_btn').show();
            var default_text = "<p>This is default workflow and cannot be changed</p>";
            var customize_text = "";
            var country = $('#select-'+workflowType+'-country').val();
            var activities = "";
            if(workflowType=='accessment'){            
                workflow_list[workflow_k].country = $('#select-'+workflowType+'-country').val();
                workflow_list[workflow_k].sd_company_id = $('#callCenter').val();
                default_text +="<h4>Name: "+ accessment_workflow_structure[country]['name']+"</h4>";
                default_text +="<h5>Description: "+accessment_workflow_structure[country]['description']+"</h5>";
                $('#default_accessment_workflow_description').val(accessment_workflow_structure[country]['description']);
                $('#default_accessment_workflow_name').val(accessment_workflow_structure[country]['name']);
                $('#custom_accessment_workflow_name').val('customize-'+accessment_workflow_structure[country]['name']);
                $('#default_accessment_workflow_id').val(accessment_workflow_structure[country]['id']);
                $('#default_accessment_workflow').html('');
                activities = accessment_workflow_structure[country]['sd_workflow_activities'];
            }else{
                distribution_list[distribution_No].country = $('#select-'+workflowType+'-country').val();
                distribution_list[distribution_No].sd_company_id = $('#callCenter').val();
                default_text +="<h4>Name: "+ distribution_workflow_structure[country]['name']+"</h4>";
                default_text +="<h5>Description: "+distribution_workflow_structure[country]['description']+"</h5>";
                $('#default_distribution_workflow_description').val(distribution_workflow_structure[country]['description']);
                $('#default_distribution_workflow_name').val(distribution_workflow_structure[country]['name']);
                $('#custom_distribution_workflow_name').val('customize-'+distribution_workflow_structure[country]['name']);
                $('#default_distribution_workflow_id').val(distribution_workflow_structure[country]['id']);
                $('#default_distribution_workflow').html('');
                activities = distribution_workflow_structure[country]['sd_workflow_activities'];
            }
            var workflowTypeFlag = 0;
            if (workflowType =="distribution") workflowTypeFlag = 1;
            $(activities).each(function(k,v){
                default_text +="<li class=\"def-"+workflowType+"-workflowstep\">";
                    default_text +="<div class=\"card w-100 h-25 my-2\">";
                        default_text +="<div class=\"card-body p-3\">";
                            default_text +="<h5 class=\"card-title\"><b>"+v.activity_name+"</b></h5>";
                            default_text +="<p class=\"card-text\">"+v.description+"</p>";
                            default_text +="<div class=\"input-group w-25 mx-auto\">";
                                default_text +="<i class=\"fas fa-arrow-up gobackstep\"></i>";
                                default_text +="<input type=\"text\" readonly=\"readonly\" value="+v.step_backward+" class=\"step_backward form-control form-control-sm\" aria-label=\"Back Steps\" aria-describedby=\"backSteps\">"
                                default_text +="<button type=\"button\" onclick=\"sectionPermission("+v.id+",1,"+workflowTypeFlag+")\" class=\"btn btn-primary btn-sm mx-2\" data-toggle=\"modal\" data-target=\"#selectPermission\">View Permission</button>";
                            default_text +="</div>";
                        default_text +="</div>";
                    default_text +="</div>";
                default_text +="</li>"  ;
                customize_text +="<li class=\"custworkflowstep\" id=\"cust-"+workflowType+"-workflowstep\">";
                    customize_text +="<div class=\"card w-100 h-25 my-2\">";
                        customize_text +="<div class=\"card-body p-3\">";
                            customize_text +="<button class=\"close closewf\">&times;</button>";
                            customize_text +="<h5 class=\"card-title\"><b>"+v.activity_name+"</b></h5>";
                            customize_text +="<p class=\"card-text\">"+v.description+"</p>";
                        customize_text +="</div>";
                    customize_text +="</div>";
                customize_text +="</li>"
            });
            $('#default_'+workflowType+'_workflow').html(default_text);
            $('#'+workflowType+'-sortable').html(customize_text);
        };

    });


    $('[id^=undocho-][id$=-WF]').click(function() {
        $('.step_backward').each(function(){
            $(this).prop("disabled", false);
        });
        var workflowType = $(this).attr('id').split('-')[1];
        $('#undo_'+workflowType+'_activities').show();
        $('#choose-'+workflowType+'-company').hide();
        $('#submit_'+workflowType+'_workflow').show();
    });
    $('[id^=undocho-][id$=-con]').click(function() {
        $(this).hide();
        var workflowType = $(this).attr('id').split('-')[1];
        $('#exit_'+workflowType+'_workflow').show();
        $('#choose_'+workflowType+'_wf').hide();
        $('#choose-'+workflowType+'-company').hide();
        $('#undo_'+workflowType+'_activities').hide();
        $('#submit_'+workflowType+'_country').show();
        $("#select-"+workflowType+"-country, #callCenter").prop("disabled", false);
    });
    $('[id^=confirm-][id$=-WFlist').click(function() {
        $(this).hide();
        var workflowType = $(this).attr('id').split('-')[1];
        $('#undocho-'+workflowType+'-WF').hide(); 
        $('#addNew-'+workflowType+'-WL').show();
        var text ="";
        if(workflowType == "distribution"){
            $('#cho-distribution-workflow, #choose-distribution-company').hide();
            distribution_No++;
        } 
        $('#distribution-workflowlist').show();
        var text ="";
        if(distribution_list.length>0){
            text += "<table>";
            text += "<thead>";
            text +="<tr>";
            text +="<th></th>"
            text +="<th>Country</th>";
            text +="<th>Distribution Name</th>";
            text +="<th>Team Resources</th>";
            text +="<th>Actions</th>";
            text +="</tr>";
            text += "</thead>";
            $(distribution_list).each(function(k,distri_detail){
                console.log(distri_detail);
                text +="<tr>";
                text +="<td><input type=\"checkbox\" id=\"selected-distri-"+k+"\"></td>";
                text +="<td>"+distri_detail.country+"</td>";
                text +="<td>"+distri_detail.workflow_name+"</td>";
                text +="<td>";
                $.each(distribution_resource_list[k],function(key,compay){
                    text += compay.name+" ";
                });
                // $(distri_detail.members).each(function(key, member_detial){
                //     text += member_detial.firstname+"/"+member_detial.lastname+"; ";
                // });
                text +="</td>";
                text +="</tr>";
            });
        }else{
            text +="<div id=\"noDistriLabel\">Please Create a new Distribution Workflow</div>";
        }
        console.log('create ');
        $('#distriList').html(text);
    });
    $('#backDistri').click(function(){
        $('#distribution-workflowlist').hide();
        $('#undocho-accessment-WF').show();
        $('#confirm-accessment-WFlist').show();
    });

    //confirm Distribution company
    $('#submitDistri').click(function(){
        var text = "";
        var distribution_text = "";
        var cro_text = "";
        $('[id^=accessment-crocompany-').each(function(){
            cro_text +=$(this).text();
            cro_text += " ; "
        });
        text +="<tr>";
        text +="<td>"+$('#custom_accessment_workflow_name').val()+"</td>";
        text +="<td>"+$('#custom_accessment_workflow_description').val()+"</td>";
        text +="<td>"+$('#callCenter option:selected').text()+"</td>";
        text +="<td>"+$('#select-accessment-country option:selected').text()+"</td>";
        text +="<td>"+cro_text+"</td>";
        text +="<td>";
        text +="<div class=\"btn btn-sm btn-primary mx-2\" data-toggle=\"modal\" onclick=\"view_workflow("+workflow_k+")\" data-target=\".WFlistView\">View</div>"
        text +="<button class=\"btn btn-sm btn-outline-danger\" onclick=\"$(this).closest('tr').remove();\">Delete</button>";
        text +="</td>";
        if(workflow_list[workflow_k].workflow_type == 0){
            text +="<input name=\"accessment_workflow["+workflow_k+"][id]\" value="+workflow_list[workflow_k].id+" type=\"hidden\">";
        }else{
            text +="<input name=\"accessment_workflow["+workflow_k+"][name]\" value="+workflow_list[workflow_k].workflow_name+" type=\"hidden\">";
            text +="<input name=\"accessment_workflow["+workflow_k+"][description]\" value="+workflow_list[workflow_k].workflow_description+" type=\"hidden\">";
            text +="<input name=\"accessment_workflow["+workflow_k+"][country]\" value="+workflow_list[workflow_k].country+" type=\"hidden\">";
            text +="<input name=\"accessment_workflow["+workflow_k+"][workflow_type]\" value=\"1\" type=\"hidden\">";
            $.each(workflow_list[workflow_k]['activities'], function(k, activity_detail){
                text +="<input name=\"accessment_workflow_activity["+workflow_k+"]["+k+"][activity_name]\" value=\""+activity_detail['activity_name']+"\" type=\"hidden\">";
                text +="<input name=\"accessment_workflow_activity["+workflow_k+"]["+k+"][description]\" value=\""+activity_detail['activity_description']+"\" type=\"hidden\">";
                text +="<input name=\"accessment_workflow_activity["+workflow_k+"]["+k+"][step_backward]\" value=\""+activity_detail['step_backward']+"\" type=\"hidden\">";
                text +="<input name=\"accessment_workflow_activity["+workflow_k+"]["+k+"][order_no]\"  value=\""+activity_detail['order_no']+"\" type=\"hidden\">";
            });
        }
        text +="<input name=\"accessment_product_workflow["+workflow_k+"][sd_company_id]\" value="+workflow_list[workflow_k].sd_company_id+" type=\"hidden\">";
        text +="<input name=\"accessment_product_workflow["+workflow_k+"][sd_user_id]\" value="+workflow_list[workflow_k].sd_user_id+" type=\"hidden\">";//TODO
        text +="<input name=\"accessment_product_workflow["+workflow_k+"][due_day]\" value="+workflow_list[workflow_k].due_day+" type=\"hidden\">";//TODO
        text +="<input name=\"accessment_product_workflow["+workflow_k+"][status]\" value=\"1\" type=\"hidden\">";

        //accessment-distribution relation
        $('[id^=selected-distri-]').each(function(){
            var key = $(this).attr('id').split('-')[2];
            if($(this).prop('checked')){
                text +="<input name=\"accessment_distribution["+workflow_k+"]["+key+"][status]\" value=\"1\" type=\"hidden\">";
            }
        });
        //accessment user assignment
        $.each(accessment_resource_list,function(key,workflow){
            $.each(workflow,function(k,company){
                $.each(company.team_resources,function(k,personDetail){
                    text +="<input name=\"accessment_user_assignment["+key+"]["+personDetail.id+"][sd_user_id]\" value="+personDetail.id+" type=\"hidden\">";
                })
                $.each(company.workflow_manager,function(k,personDetail){
                    text +="<input name=\"accessment_product_workflow["+key+"][sd_user_id]\" value="+personDetail.id+" type=\"hidden\">";
                    accessment_resource_list[key].sd_user_id = personDetail.id;
                })
            })
        });
        text +="</tr>";
        $.each(distribution_resource_list,function(k,workflow){
            $.each(workflow,function(k,company){
                $.each(company.team_resources,function(k,personDetail){
                    distribution_text +="<input name=\"distribution_user_assignment["+workflow_k+"]["+personDetail.id+"][sd_user_id]\" value="+personDetail.id+" type=\"hidden\">";
                })
                $.each(company.workflow_manager,function(k,personDetail){
                    distribution_text +="<input name=\"distribution_workflow["+workflow_k+"][sd_user_id]\" value="+personDetail.id+" type=\"hidden\">";
                    distribution_list[workflow_k].sd_user_id = personDetail.id;
                })
            })
        });
        //accessment permission
        $.each(accessment_permission_list[workflow_k],function(activity_order,activity_permissions){
            $.each(activity_permissions,function(section_id, permission_action){
                if(permission_action == '0'||typeof permission_action == 'undefined') return true;
                text +="<input name=\"accessment_permission["+workflow_k+"]["+activity_order+"]["+section_id+"][action]\" value=\""+permission_action+"\" type=\"hidden\">"
            });
        });

        //distribution permission
        $.each(distribution_permission_list,function(distribution_k, workflow_permissions){
            $.each(workflow_permissions,function(activity_order,activity_permissions){
                $.each(activity_permissions,function(section_id, permission_action){
                    if(permission_action == '0'||typeof permission_action == 'undefined') return true;
                    distribution_text +="<input name=\"distribution_permission["+distribution_k+"]["+activity_order+"]["+section_id+"][action]\" value=\""+permission_action+"\" type=\"hidden\">"
                });
            });
        });
        
        $.each(distribution_list, function(key, distribution_workflow){
            text +="<input name=\"distribution_product_workflow["+workflow_k+"][sd_company_id]\" value=\""+distribution_list[workflow_k].sd_company_id+"\" type=\"hidden\">";
            text +="<input name=\"distribution_product_workflow["+workflow_k+"][sd_user_id]\" value=\""+distribution_list[workflow_k].sd_user_id+"\" type=\"hidden\">";//TODO
            text +="<input name=\"distribution_product_workflow["+workflow_k+"][status]\" value=\"1\" type=\"hidden\">";
            text +="<input name=\"distribution_product_workflow["+workflow_k+"][due_day]\" value=\""+distribution_list[workflow_k].due_day+"\" type=\"hidden\">";
            if(distribution_workflow.workflow_type == 0){
                distribution_text +="<input name=\"distribution_workflow["+key+"][id]\" value="+distribution_workflow.id+" type=\"hidden\">";
            }else{
                distribution_text +="<input name=\"distribution_workflow["+key+"][name]\" value="+distribution_workflow.workflow_name+" type=\"hidden\">";
                distribution_text +="<input name=\"distribution_workflow["+key+"][description]\" value="+distribution_workflow.workflow_description+" type=\"hidden\">";
                distribution_text +="<input name=\"distribution_workflow["+key+"][country]\" value="+distribution_workflow.country+" type=\"hidden\">";
                distribution_text +="<input name=\"distribution_workflow["+key+"][workflow_type]\" value=\"1\" type=\"hidden\">";
                $.each(distribution_workflow['activities'],function(k, activity_detail){
                    distribution_text +="<input name=\"distribution_workflow_activity["+key+"]["+k+"][activity_name]\" value=\""+activity_detail['activity_name']+"\" type=\"hidden\">";
                    distribution_text +="<input name=\"distribution_workflow_activity["+key+"]["+k+"][description]\" value=\""+activity_detail['activity_description']+"\" type=\"hidden\">";
                    distribution_text +="<input name=\"distribution_workflow_activity["+key+"]["+k+"][step_backward]\" value=\""+activity_detail['step_backward']+"\" type=\"hidden\">";
                    distribution_text +="<input name=\"distribution_workflow_activity["+key+"]["+k+"][order_no]\"  value=\""+activity_detail['order_no']+"\" type=\"hidden\">";
                });
            }           
        });
        $('#distribution_input').html(distribution_text);
        $('#addNew-accessment-WL').show();
        $('#cho-accessment-workflow, #distribution-workflowlist, #chooseaccessmentCompany').slideUp();
        $('#accessment-workflowlist').slideDown();
        swal({
            title: "Your New Workflow has been SET",
            icon: "success",
          });
        $('#choose-accessment-company').hide()
        $('#workflow_table').append(text);
        $('#no_workflow_notice').hide();
        workflow_k ++;
    });
//TODODDDDDDD
    $(document).ready(function(){
        $("#advsearch").click(function(){
            $("#advsearchfield").slideDown();
        });
    });
    // Defaultworkflow and Custworkflow button control

    $('#default_accessment_btn').click(function() {
        $('#confirm_accessment_activities').show();
        $('#default_accessment_workflow').slideDown();
        $('#customize_accessment_workflow').slideUp();
    });
    $('#default_distribution_btn').click(function() {
        $('#confirm_distribution_activities').show();
        $('#default_distribution_workflow').slideDown();
        $('#customize_distribution_workflow').slideUp();
    });
    $('#cust_accessment_btn').click(function() {
        $('#confirm_accessment_activities').show();
        $('#customize_accessment_workflow').slideDown();
        $('#default_accessment_workflow').slideUp();
    });
    $('#cust_distribution_btn').click(function() {
        $('#confirm_distribution_activities').show();
        $('#customize_distribution_workflow').slideDown();
        $('#default_distribution_workflow').slideUp();
    });

    // Close customworkflow step
    $('.closewf').click(function() {
        $(this).closest('li').remove();
    });
    // Custworkflow Close icon
    $('.close').click(function() {
        $(this).parents('li.custworkflowstep').fadeOut();
    });

// "Confirm Assignment" button message
    $('#conass').click(function() {
        swal({
            title: "Your Assignment has been saved!",
            icon: "success"
          })
    })
    // Custworkflow draggable effect
    $( function() {
        $( "#accessment-sortable" ).sortable({
            revert: true,
            cancel: ".fixed,input,textarea",
            delay: 100,
            placeholder: "ui-state-highlight",
            start  : function(event, ui){
                $(ui.helper).addClass("w-100 h-50");
            },
            // Remove Custworkflow Step
            update: function (event,ui) {
                $('.close').click(function() {
                    $(this).parents('li.custworkflowstep').remove();
                });
            }
        });
        // $('#comfirm_activity').click(function(){
        //     $(this).hide();
        //     $('#new_accessment_activity_name').replaceWith('<b>' + $('#new_accessment_activity_name').val() + '</b>');
        //     $('#new_activit-description').replaceWith($('#new_activit-description').val());
        //     $('#customize_activity').attr('id','draggable');
        //     $( "#draggable" ).draggable({
        //         connectToSortable: "#accessment-sortable",
        //         cursor: "pointer",
        //         helper: "clone",
        //         opacity: 0.6,
        //         revert: "invalid",
        //         start  : function(event, ui){
        //             $(this).find('.card-body').append( '<button class="close closewf">' +  '&times;' +  '</button>');
        //             $(this).find('.input-group').append('<i class="fas fa-arrow-up gobackstep"></i><input type="text" class="step_backward form-control form-control-sm" aria-label="Back Steps" aria-describedby="backSteps">');

        //                 // $(ui.helper).addClass("w-100 h-75");
        //                 // $(this).find('h5').replaceWith('<h5><input type="text" placeholder="Type your step name here" class="font-weight-bold" /></h5>');
        //         },
        //         // Add "close icon" when drag into new place
        //         create :  function (event, ui) {
        //                 $(this).find('.card-body').append( '<button class="close closewf">' +  '&times;' +  '</button>');
        //                 $(this).find('.input-group').append('<i class="fas fa-arrow-up gobackstep"></i><input type="text" class="step_backward form-control form-control-sm" aria-label="Back Steps" aria-describedby="backSteps">');
        //                 // $(this).change(function() {
        //                 //     $('#new_accessment_activity_name').replaceWith('<h5 id>' + $('#new_accessment_activity_name').val() + '</h5>');
        //                 // });
        //                 },
        //         // Remove all inputs in original when drag into new place
        //         stop : function (event,ui) {
        //             $(ui.helper).addClass("w-100 h-75");
        //             $(this).find('h5').replaceWith('<h5><input id="new_accessment_activity_name" type="text" placeholder="Type your step name here" class="font-weight-bold" /></h5>');
        //             $(this).find('p').replaceWith('<p class="card-text"><textarea type="text" id="new_activit-description" class="form-control" placeholder="Type your step description here" aria-label="With textarea"></textarea></p>')
        //             $(this).attr('id','customize_activity');
        //             $('#comfirm_activity').show();
        //             $('#customize_activity').find('.close').remove();
        //             $('#customize_activity').find('.gobackstep').remove();
        //             $('#customize_activity').find('.step_backward').remove();
        //         }
        //     });
      });

          // Add CRO, triggered by "Add" button for adding CRO button and CRO resource list
    $('[id^=add][id$=cro]').click(function(){
        var workflowType = $(this).attr('id').split('-')[1];
        $('[id$=croadd').attr('id','accessment-croadd');
    });

    $('[id$=croadd]').click(function() {
        var workflowType = $(this).attr('id').split('-')[0];
        var cro_name = $('#'+workflowType+'-croname option:selected').text();
        var cro_id = $('#'+workflowType+'-croname').val();
        
        // var newcro = $('<button type="button"class="btn btn-outline-primary"  onclick="selectCro(' + cro_id + ')" data-toggle="modal" data-target=".bd-example-modal-lg">' + cro_name + '</button>');
        var text = '<tr>';
        text += '<th id = "'+workflowType+'-crocompany-'+cro_id+'">' + cro_name + '</th>';
        text += '<td id = "'+workflowType+'-cromanager-'+cro_id+'"></td>';
        text += '<td id = "'+workflowType+'-crostaff-'+cro_id+'"></td>';
        text += '<td><button class="'+workflowType+'Btn btn btn-sm btn-outline-info" onclick="selectCro(' + cro_id
        if(workflowType=="accessment") text +=',1)" '; else text += ',2)" ';
        text += ' data-toggle="modal" data-target="#addper">Edit</button>';
        text += '<button class="'+workflowType+'Btn btn btn-sm btn-danger ml-3" id="removeCRO-' + cro_id + '" onclick="removeCro(' + cro_id;
        if(workflowType=="accessment") text +=',1)" '; else text += ',2)" ';
        text += '>Delete</button></td>';
        text +='</tr>';
        $('#'+workflowType+'-crotable').append(text);
        // $('#addcroarea').append(newcro);
        var request = {'id':cro_id};
        //TODO
        $("#"+workflowType+"-croname option:selected").remove();
        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            type:'POST',
            url:'/sd-users/searchResource',
            data:request,
            success:function(response){
                console.log(response);
                var result = $.parseJSON(response);
                var text = "";
                var cro_info = {};
                cro_info.member_list = result;
                cro_info.name = cro_name;
                cro_id.team_resources = [];
                cro_id.workflow_manager = [];
                if(workflowType=="accessment") accessment_resource_list[workflow_k][cro_id]=cro_info;
                else distribution_resource_list[workflow_k][cro_id] = cro_info;
            },
            error:function(response){

            }
        });
     });
});

function sectionPermission(activity_id, readonly, workflowTypeFlag){
    var workflowType = "accessment";
    if(workflowTypeFlag == "1") workflowType = "distribution";
    if(readonly==1){
        $('#permissionFooter').find('button').remove();
        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            type:'POST',
            url:'/sd-activity-section-permissions/searchActivityPermission/'+activity_id,
            success:function(response){
                console.log(response);
                var result = $.parseJSON(response);
                $("div[id^=section-]").each(function(){
                    var id = $(this).attr('id').split('-');
                    if(typeof result[id[1]]!="undefined" && result[id[1]]!="0"){
                            if(result[id[1]] == 1) {
                                $(this).find("input[id^=write][id$="+id[1]+"]").prop('checked',true);
                                $(this).find("input[id^=read][id$="+id[1]+"]").prop('checked',true);
                                flag = 1;
                            }
                            else if(result[id[1]] == 2) {
                                flag = 1;
                                $(this).find("input[id^=write][id$="+id[1]+"]").prop('checked',false);
                                $(this).find("input[id^=read][id$="+id[1]+"]").prop('checked',true);
                            }
                            return true;  
                    }else{
                        $(this).find("input[id^=write][id$="+id[1]+"]").prop('checked',false);
                        $(this).find("input[id^=read][id$="+id[1]+"]").prop('checked',false);
                    }
                });
            },
            error:function(response){

            },
        });
        // $("[id^=write]").each(function(){
        //     $(this).prop('disabled',true);}
        // );
        // $("[id^=read]").each(function(){
        //     $(this).prop('disabled',true);});
    }else{
        if(readonly==2){
            if(!($('#permissionFooter').find('button').length > 0)){
                var text ="<button type=\"button\" class=\"btn btn-primary\" onclick=\"savePermission("+activity_id+","+workflowTypeFlag+")\" data-dismiss=\"modal\">Save</button>";
                $('#permissionFooter').prepend(text);
            }else $('#permissionFooter').find('button').attr('onclick',"savePermission("+activity_id+","+workflowTypeFlag+")");
        }
        $("div[id^=section]").each(function(){
            var flag = 0;
            var id = $(this).attr('id').split('-');
            $("[id^=write]").each(function(){
                $(this).prop('disabled',false);}
            );
            $("[id^=read]").each(function(){
                $(this).prop('disabled',false);});
            // if((typeof permission_list[workflow_k]=="undefined"))
            // {
            //     $(this).find("input[id^=write]").prop('checked',false);
            //     $(this).find("input[id^=read]").prop('checked',false);
            //     return true;
            // }else if((typeof permission_list[workflow_k][activity_id]=="undefined"))
            // {
                // $(this).find("input[id^=write]").prop('checked',false);
                // $(this).find("input[id^=read]").prop('checked',false);
                // return true;
            // }else 
            var permission_key = 0;
            if(workflowTypeFlag==0)
            {
                permission_list = accessment_permission_list;
                permission_key = workflow_k
            } 
            else {
                permission_list = distribution_permission_list;
                permission_key = distribution_No;
            }
            if((typeof permission_list[permission_key]=="undefined")||(typeof permission_list[permission_key][activity_id]=="undefined")
                    ||(typeof permission_list[permission_key][activity_id][id[1]]=="undefined")){
                        $(this).find("input[id^=write]").prop('checked',false);
                        $(this).find("input[id^=read]").prop('checked',false);
                        return true;
            }else if(permission_list[permission_key][activity_id][id[1]] == 1) {
                        $(this).find("input[id^=write]").prop('checked',true);
                        $(this).find("input[id^=read]").prop('checked',true);
                        flag = 1;
                    }
                    else if(permission_list[permission_key][activity_id][id[1]] == 2) {
                        flag = 1;
                        $(this).find("input[id^=write]").prop('checked',false);
                        $(this).find("input[id^=read]").prop('checked',true);
                    }else{
                        $(this).find("input[id^=write]").prop('checked',false);
                        $(this).find("input[id^=read]").prop('checked',false);
            }
        });

    }
}
function savePermission(activity_id, workflowTypeFlag){
    var workflowType = "";
    if(workflowTypeFlag == 0)
    {
        workflowType = "accessment";
        permission_list = accessment_permission_list;
        permission_key = workflow_k;
    }else{
        workflowType = "distribution";
        permission_list = distribution_permission_list;
        permission_key = distribution_No;
    } 
    if(typeof permission_list[permission_key]=='undefined') permission_list[permission_key]=[];
    if(typeof permission_list[permission_key][activity_id]=='undefined') permission_list[permission_key][activity_id]=[];
    
    $('[id^=section]').each(function(){
        var id = $(this).attr('id').split('-');
        if($(this).find("input[id^=write]").prop('checked')==true){
            permission_list[permission_key][activity_id][id[1]]=1;
            write=1;
        }else if($(this).find("input[id^=read]").prop('checked') == true){
            permission_list[permission_key][activity_id][id[1]]=2;
            write=1;
        }else {
            permission_list[permission_key][activity_id][id[1]] = 0;
        }
    });
    $('#cust-'+workflowType+'-permission-'+activity_id).text('View Permission');
}

function iterateWorkflow(wkfl_name)
{
    var steps = [];
    var listItems = $("."+wkfl_name+" li");
    //console.log(listItems);
    listItems.each(function(idx, li) {
        var display_order = idx+1;
        var activity_description = $(li).find(".card-text").text()
        var step_backward = $(li).find(".step_backward").val()
        var step_name = $(li).find("h5").text().replace(/ /g,'');
        steps.push({
            display_order: display_order,
            activity_name: step_name,
            activity_description: activity_description,
            step_backward: step_backward
        });
        // console.log(display_order);
        // console.log(step_name);

    })
    //console.log(steps);
    return steps;
}
function croDroppableArea(){
    $(".personnel").draggable({
        cursor: "pointer",
        helper: "clone",
        opacity: 0.6,
        revert: "invalid",
        zIndex: 100
    });

    $("#personnelDraggable").droppable({
        tolerance: "intersect",
        accept: ".personnel",
        activeClass: "ui-state-default",
        hoverClass: "ui-state-hover",
        drop: function(event, ui) {
            $("#personnelDraggable").append($(ui.draggable));
        }
    });

    $(".stackDrop1").droppable({
        tolerance: "intersect",
        accept: ".personnel",
        activeClass: "ui-state-default",
        hoverClass: "ui-state-hover",
        drop: function(event, ui) {
            $(this).append($(ui.draggable));
        }
    });

    $(".stackDrop2").droppable({
        tolerance: "intersect",
        accept: ".personnel",
        activeClass: "ui-state-default",
        hoverClass: "ui-state-hover",
        drop: function(event, ui) {
            $(this).append($(ui.draggable));
        }
    });
}
function view_workflow(workflow_k){
    $('#viewWFname').text(workflow_list[workflow_k]['workflow_name']);
    $('#viewCC').text(call_center_list[workflow_list[workflow_k]['sd_company_id']]);
    $('#viewCountry').text(workflow_list[workflow_k]['country']);
    $('#viewDesc').text(workflow_list[workflow_k]['workflow_description']);
    var team_resources_text="";
    $.each(accessment_resource_list[workflow_k], function(company_id, company_detail){
        console.log(company_detail);
        $.each(company_detail['team_resources'],function(k,v){
            console.log(v);
            team_resources_text += "<div><b>"+v['firstname']+" "+v['lastname']+"</b> FROM "+company_detail['name']+"</div>";
        });
        $.each(company_detail['workflow_manager'],function(k,v){
            console.log(v);
            $('#viewMan').html("<b>"+v['firstname']+" "+v['lastname']+"</b> FROM "+company_detail['name']);
        })
    });
    $('#viewRes').html(team_resources_text);
    var activities_text="";
    $(workflow_list[workflow_k]['activities']).each(function(k,activity_detail){
        activities_text +="<span class=\"badge badge-info px-5 py-3 m-3\"><h5>"+activity_detail['activity_name']+"</h5><h8>"+activity_detail['activity_description']+"</h8></span><i class=\"fas fa-long-arrow-alt-right\"></i>";
    })
    activities_text+="<span class=\"badge badge-info px-5 py-3 m-3\"><h5>Complete</h5><h8>End of the case</h8></span>"
    $('#view_activities').html(activities_text);
}
function confirm_cust_activity(type=0){
    // $('#draggable').addClass('ui-draggable ui-draggable-handle');
    var typestr = "";
    if(type) typestr="accessment";
    else typestr="distribution";
    $('#new_'+typestr+'_activity_name').replaceWith('<b>'+$('#new_'+typestr+'_activity_name').val()+'</b>');
    $('#new_'+typestr+'_activity_description').replaceWith($('#new_'+typestr+'_activity_description').val());
    $( "#draggable" ).draggable( {disabled: false} )
    $('#new_'+typestr+'_activity_description').remove();
    $( "#draggable" ).draggable({
        connectToSortable: "#accessment-sortable",
        cursor: "pointer",
        helper: "clone",
        opacity: 0.6,
        revert: "invalid",
        start  : function(event, ui){
            $(ui.helper).addClass("w-100 h-75");
            $(this).find('h5').replaceWith('<h5 class=\"card-title\"><input type="text" id="new_'+typestr+'_activity_name" placeholder="Type step name here FIRST" class="font-weight-bold" /></h5>');
            $(this).find('p').replaceWith('<p class="card-text"><textarea type="text"  id="new_'+typestr+'_activity_description" class="form-control" placeholder="Type your step description here" aria-label="With textarea"></textarea></p>');
            $(this).find('.card').append("<button id=\"new_'+typestr+'_activity_description\" onclick=\"confirm_cust_activity("+type+")\" class=\"btn btn-primary w-25 mx-auto my-2\">Confirm</button>");
        },
        // Add "close icon" when drag into new place
        create: function( event, ui ) {
            console.log('Here');
            $(this).find('.card-body').prepend( '<button class="close closewf">' +  '&times;' +  '</button>');
        },
        // Remove all inputs in original when drag into new place
        stop : function (event,ui) {
            $( "#draggable" ).draggable( {disabled: true} );
        }
    });
}
$(document).ready(function(){
    $(".checkAll").click(function () {
        $('.checkboxContent').find('input:checkbox').not(this).prop('checked', this.checked);
    });
});