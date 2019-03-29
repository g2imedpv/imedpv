var resource_list={};
var workflow_k = 0;
var workflow_list =[];
var cro_list=[];
var call_center_list = {};
var permission_list = {}
var distri_list =[];
var distriNo = 1;
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
});
function selectCro(id){
    $('[id^=conass]').attr('id', 'conass-'+id);
    var member_text = "";
    $(resource_list[workflow_k][id]['member_list']).each(function(k,v){
        member_text +="<div class=\"personnel\" id=\"userid_"+v.id+"\">"+v.firstname+" "+v.lastname+"</div>";

    });
    $('#personnelDraggable').html(member_text);

    var workflow_manager_text = "";
    $(resource_list[workflow_k][id]['workflow_manager']).each(function(k,v){
        workflow_manager_text +="<div class=\"personnel\" id=\"userid_"+v.id+"\">"+v.firstname+" "+v.lastname+"</div>";
    });
    $('#workflow_manager-add').html(workflow_manager_text);

    var team_resources_text = "";
    $(resource_list[workflow_k][id]['team_resources']).each(function(k,v){
        team_resources_text +="<div class=\"personnel\" id=\"userid_"+v.id+"\">"+v.firstname+" "+v.lastname+"</div>";
    });
    $('#team_resources-add').html(team_resources_text);
    croDroppableArea();
}
function removeCro(id){
    swal({
        title: "Are you sure?",
        text: "This record would be removed permanently once deleted",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
            var crocaption = $('#crocompany-' + id).text();
            $('#crocompany-' + id).closest('tr').remove();
            $("#croname").append($("<option></option>").attr("value",id).text(crocaption));
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
        $('#addNewDistri').click(function(){
            $('#noDistriLabel').hide();
            var text ='';
            text +='<div id="newDistri-'+ distriNo + '">';
            console.log(text);
                text +='<div class="form-group col-md-3 d-inline-block">';
                    text +='<label for="">Select Country</label>';
                    text +='<select class="form-control" id="" name="">';
                        text +='<option value="">Select Country</option>';
                        text +='<option value="USA">Unitied States</option>';
                        text +='<option value="JPN">Japan</option>';
                        text +='<option value="EU">Europe</option>';
                    text +='</select>';
                text +='</div>';
                text +='<div class="my-2">';
                text +='<button type="button" id="defDistriBtn-'+ distriNo +'" class="btn btn-success workflow w-25">';
                text +='<span>Default Distribution</span>';
                text +='</button>';
                text +='<div id="defDistriContent-'+ distriNo +'" style="display:none;">';
                    text +='<div class="d-flex justify-content-center">';
                        text +='<div class="card m-2" style="width: 18rem;">';
                            text +='<div class="card-body">';
                                text +='<h5 class="card-title">Generate Report</h5>';
                                text +='<p class="card-text">Output a report from system</p>';
                            text +='</div>';
                        text +='</div>';
                        text +='<div class="card m-2" style="width: 18rem;">';
                            text +='<div class="card-body">';
                                text +='<h5 class="card-title">Submission</h5>';
                                text +='<p class="card-text">Submit report to regulator</p>';
                            text +='</div>';
                        text +='</div>';
                    text +='</div>';
                text +='</div>';
            text +='</div>';
            text +='<div class="my-2">';
                text +='<button type="button" id="custDistriBtn-'+ distriNo +'" class="btn btn-success workflow w-25">';
                text +='<span>Customize Distribution</span>';
                text +='</button>';
                text +='<div id="custDistriContent-'+ distriNo +'" class="my-3" style="display:none;">';
                    text +='<div class="d-flex justify-content-center">';
                        text +='<div class="card m-2" style="width: 18rem;">';
                            text +='<div class="card-body">';
                                text +='<h5 class="card-title">Generate Report</h5>';
                                text +='<p class="card-text">Output a report from system</p>';
                            text +='</div>';
                        text +='</div>';
                        text +='<div class="card m-2" style="width: 18rem;">';
                            text +='<div class="card-body">';
                                text +='<h5 class="card-title">Submission</h5>';
                                text +='<p class="card-text">Submit report to regulator</p>';
                            text +='</div>';
                        text +='</div>';
                    text +='</div>';
                text +='</div>';
            text +='</div>';
            text +='<button type="button" class="btn btn-sm btn-outline-danger float-right distRmBtn" onclick="$(this).parent().remove();">';
            text +='<i class="fas fa-trash-alt"></i>Remove</button><br><hr></div>';
            $( ".newDistrictArea" ).append(text);
            // $( ".newDistrictArea" ).append('<div id="newDistri-'+ distriNo + '"><div class="form-group col-md-3 d-inline-block"><label for="">Select Country</label><select class="form-control" id="" name=""><option value="">Select Country</option><option value="USA">Unitied States</option><option value="JPN">Japan</option><option value="EU">Europe</option></select></div><div class="my-2"><button type="button" id="defDistriBtn-'+ distriNo +'" class="btn btn-success workflow w-25"><span>Default Distribution</span></button><div id="defDistriContent-'+ distriNo +'" style="display:none;"><div class="d-flex justify-content-center"><div class="card m-2" style="width: 18rem;"><div class="card-body"><h5 class="card-title">Generate Report</h5><p class="card-text">Output a report from system</p></div></div><div class="card m-2" style="width: 18rem;"><div class="card-body"><h5 class="card-title">Submission</h5><p class="card-text">Submit report to regulator</p></div></div></div></div></div><div class="my-2"><button type="button" id="custDistriBtn-'+ distriNo +'" class="btn btn-success workflow w-25"><span>Customize Distribution</span></button><div id="custDistriContent-'+ distriNo +'" class="my-3" style="display:none;"><div class="d-flex justify-content-center"><div class="card m-2" style="width: 18rem;"><div class="card-body"><h5 class="card-title">Generate Report</h5><p class="card-text">Output a report from system</p></div> </div><div class="card m-2" style="width: 18rem;"><div class="card-body"><h5 class="card-title">Submission</h5><p class="card-text">Submit report to regulator</p></div></div></div></div></div><button type="button" class="btn btn-sm btn-outline-danger float-right distRmBtn" onclick="$(this).parent().remove();"><i class="fas fa-trash-alt"></i> Remove</button><br><hr></div>');
            distriNo++;
        });
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
        $('#submitDistri').click(function(){
            $('.distRmBtn, #addNewDistri').hide();
            $(this).hide();
        });
        
        $('[id^=write]').change(function(){
            var id = $(this).attr('id').split('-');
            if($(this).is(':checked')) $('[id=read-'+id[1]+'-'+id[2]+']').prop('checked',true);
        });


        // $('#submit_accessment_country').click(function(){
        //     var default_text = "<p>This is default workflow and cannot be changed</p>";
        //     var customize_text = "";
        //     var country = $('#select-accessment-country').val();
        //     $(accessmentWorkflowInfo[country]['sd_workflow_activities']).each(function(k,v){

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
        //     $('#sortable').html(customize_text);
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
        var cro_id = $(this).attr('id').split('-')[1];

        var textmanager  = "";
        var textstaff = "";
        var member_list = [];
        var team_resources = [];
        var workflow_manager = [];
        // var managerChosed = $(".stackDrop1 > .personnel").text().match(/[A-Z][a-z]+/g);
        // var staffChosed = $(".stackDrop2 > .personnel").text().match(/[A-Z][a-z]+/g);
        $("#personnelDraggable").children("div").each(function(){
            var user_info = {};
            user_info.id = $(this).attr('id').split('_')[1];
            user_info.firstname = $(this).text().split(' ')[0];
            user_info.lastname = $(this).text().split(' ')[1];
            member_list.push(user_info);
        });
        resource_list[workflow_k][cro_id].member_list = member_list;
        $("#team_resources-add").children("div").each(function(){
            var user_info = {};
            user_info.id = $(this).attr('id').split('_')[1];
            user_info.firstname = $(this).text().split(' ')[0];
            user_info.lastname = $(this).text().split(' ')[1];
            team_resources.push(user_info);
            textstaff += "<div class=\"personnel\">"+$(this).text()+"</div>";
        });
        resource_list[workflow_k][cro_id].team_resources = team_resources;
        $("#workflow_manager-add").children("div").each(function(){
            var user_info = {};
            user_info.id = $(this).attr('id').split('_')[1];
            user_info.firstname = $(this).text().split(' ')[0];
            user_info.lastname = $(this).text().split(' ')[1];
            workflow_manager.push(user_info);
            textmanager += "<div class=\"personnel\">"+$(this).text()+"</div>";
        });
        resource_list[workflow_k][cro_id].workflow_manager = workflow_manager;
        // $.each(managerChosed, function(k,manager){
        //     textmanager += manager + "; ";
        // });
        $("#cromanager-"+cro_id).html(textmanager);
        // $.each(staffChosed, function(k,staff){
        //     textstaff += "<div id=>"+staff + "; </div>";
        // });
        $("#crostaff-"+cro_id).html(textstaff);
    });

    $('#confirm_accessment_activities').click(function(){

        $('.step_backward').each(function(){
            $(this).prop("disabled", false);
        });

        if ($('.defworkflow').is(':visible') && $('.custworkflow').is(':hidden'))
        {
            $('#customize_accessment_workflow_div, #default_accessment_btn').hide();
            $('#default_accessment_T, #undochoAccessmentWF').show();
            $('#ifdef').addClass("mx-auto w-50");

        }else
        if (($('.defworkflow').is(':hidden') && $('.custworkflow').is(':visible')))
        {
            if(!$('#custom_accessment_workflow_name').val()  ) {
                $('#custom_accessment_workflow_name-validate').show().delay(2000).fadeOut();
                return false;
            }
            else if (
                !$('#custom_accessment_workflow_description').val()  ) {
                $('html,body').animate({
                    scrollTop: $("#customize_accessment_workflow").offset().top
                    });
                $('#custom_accessment_workflow_description-validate').show().delay(2000).fadeOut();
                return false;
            }
            else {
                $('#default_accessment_workflow_div, #cust_accessment_btn, .closewf').hide();
                $('#customize_accessment_T, #undochoAccessmentWF').show();
                $('#sortable, #draggable').addClass("mx-auto w-50");
                $('#customize_accessment_workflow').find('ul').hide();
                $('#custom_accessment_workflow_name').attr('disabled',true);
                $('#custom_accessment_workflow_description').attr('disabled',true);
                $('li.custworkflowstep').find('button').hide();
                var order = 1;
                $('#sortable').find('.card-body').each(function(){
                    console.log($(this).children('.input-group'));
                    var text ="<button type=\"button\" id=\"custAccessmentPermission-"+order+"\" onclick=\"sectionPermission("+order+",2)\" class=\"btn btn-primary btn-sm mx-2\" data-toggle=\"modal\" data-target=\"#selectPermission\">Set Permission</button>";
                    $(this).append(text);
                    order ++;
                });
                $(this).hide();
                $('#sortable').find('.card-body').append( '<div class="input-group w-25 mx-auto"><i class="fas fa-arrow-up gobackstep"></i><input type="text" class="step_backward form-control form-control-sm backstep_input" aria-label="Back Steps" aria-describedby="backSteps"></div>');
                $('#custworkflowname').next('#errAccessmentWorkflow').remove(); // *** this line have been added ***
                $("#sortable").sortable({ disabled: true });
            }
        };
        $(this).hide();
        $('#submit_accessment_workflow').show();
        $('#undochoaccessmentcon').hide();
        $('#undo_accessment_activities').show();

    });
    $('#undo_accessment_activities').click(function(){
        if ($('.defworkflow').is(':visible') && $('.custworkflow').is(':hidden'))
        {
            $('#customize_accessment_workflow_div').show();
            $('#default_accessment_btn').show();
            $('#default_accessment_T, #undochoAccessmentWF').hide();
        }
        if (($('.defworkflow').is(':hidden') && $('.custworkflow').is(':visible')))
        {
            $('[id^=custAccessmentPermission]').each(function(){
                $(this).remove();
            });
            $("#sortable").sortable({ disabled: false });
            $('#defworkflowstep').hide()
            $('#default_accessment_workflow_div, #cust_accessment_btn, .closewf').show();
            $('#customize_accessment_T, #undochoAccessmentWF').hide();
            $('#sortable, #draggable').removeClass("mx-auto w-50");
            $('#customize_accessment_workflow').find('ul').show();
            $('#custom_accessment_workflow_name').attr('disabled',false);
            $('#custom_accessment_workflow_description').attr('disabled',false);
            $('li.custworkflowstep').find('button').show();
            $('#sortable').find('.input-group').remove();

        }
        $('#confirm_accessment_activities').show();
        $('#submit_accessment_workflow').hide();
        $(this).hide();
        $('#undochoaccessmentcon').show();
    })
    $('#submit_accessment_workflow').click(function() {
        var finished = 1;
        $('[id^=custAccessmentPermission]').each(function(){
            if($(this).text() != "View Permission"){
                finished = 0;
                // TODO mention that THIS activity's permission not finished
                return false;
            }
            var activity_Id = $(this).attr('id').split('-')[1];
            $(this).prop('onclick','sectionPermission('+activity_Id+',3)');
        });
        if(!finished){
            console.log('111');
            return false;
        }
        $(this).hide();
        $('#undo_accessment_activities').hide();
        $('#chooseAccessmentCompany').show();
        // $('#undochoAccessmentWF, #chooseDistri').show();
        var text ="";
        if(distri_list.length>0){
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
            $(distri_list).each(function(k,distri_detail){
                text +="<tr>";
                text +="<td><input type=\"checkbox\" id=\"selected-distri-"+k+"\"></td>";
                text +="<td>"+distri_detail.country+"</td>";
                text +="<td>"+distri_detail.name+"</td>";
                text +="<td>";
                $(distri_detail.members).each(function(key, member_detial){
                    text += member_detial.firstname+"/"+member_detial.lastname+"; ";
                });
                text +="</td>";
                text +="</tr>";
            });
        }else{
            text +="<div id=\"noDistriLabel\">Please Create a new Distribution Workflow</div>";
        }
        console.log('create ');
        $('#distriList').html(text);
        //$('#chooseAccessmentCompany').show();
        // var cro_text = "";
        // $.each(cro_list, function(k,cro){
        //     cro_text +="<option value=\""+cro.id+"\">"+cro.name+"</option>";
        //     });
        // $('#croname').html(cro_text);
        workflow_list[workflow_k].activities = [];
        $('#crotable').html("");
        if ($('.defworkflow').is(':visible') && $('.custworkflow').is(':hidden'))
        {
            var order_no = 1;
            $('#default_accessment_workflow').find(".card-body").each(function(){
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
                workflow_list[workflow_k].activities.push(activities_list);
                order_no++;
            });
            workflow_list[workflow_k].id = $('#default_accessment_workflow_id').val();
            workflow_list[workflow_k].workflow_type = 0;
            workflow_list[workflow_k].workflow_name = $('#default_accessment_workflow_name').val();
            workflow_list[workflow_k].workflow_description= $('#default_accessment_workflow_description').val();
        }
        if (($('.defworkflow').is(':hidden') && $('.custworkflow').is(':visible')))
        {
            $('.step_backward').each(function(){
                $(this).prop("disabled", true);
            })
            var order_no = 1;
            $('#sortable').find(".card-body").each(function(){
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
                workflow_list[workflow_k].activities.push(activities_list);
                order_no++;
            });
            workflow_list[workflow_k].workflow_type = 1;
            workflow_list[workflow_k].workflow_name = $('#custom_accessment_workflow_name').val();
            workflow_list[workflow_k].workflow_description= $('#custom_accessment_workflow_description').val();
        }
        //TODO While customized
    });
    $('#addNewWL').click(function() {
        // function addNewWorkflow() {
            resource_list[workflow_k] = {};
            workflow_list[workflow_k] = {};
            // var call_center_text = "<option value=\"\">Select Call Center</option>";
            // $.each(call_center_list, function(k,call_center){
            //     call_center_text +="<option value=\""+k+"\">"+call_center.name+"</option>";
            //     });
            $("#select-accessment-country, #callCenter").prop("disabled", false);
            $("#select-accessment-country").val("");
            // $('#callCenter').html(call_center_text);
            $('#addNewWL').hide();
            $('#workflowlist').slideUp();
            $('#choworkflow').slideDown();
            $('#exit_accessment_workflow').show();
            $('#submit_accessment_country').show();
            $('#choose_accessment_wf').hide();
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
        //             $('#choworkflow, #chooseDistri, #chooseAccessmentCompany').find('select,input').val('');
        //             }
        //         });
        //     }


    });
    $('#exit_accessment_workflow').click(function(){
        $('#choworkflow').slideUp();
        $('#workflowlist').slideDown();
        $('#addNewWL').show();
    });
    $('#submit_accessment_country').click(function() {
        // if(
        //     !$('#select-accessment-country').val()  ) {
        //     $('#select-accessment-country-validate').show().delay(2000).fadeOut();;
        // }
        // else
        if (!$('#callCenter').val()) {
            $('#callCenter-validate').show().delay(2000).fadeOut();;
        }
        else
        {
            $('#defworkflowstep').hide();
            $('#cust_accessment_btn, .closewf').show();
            $('#customize_accessment_T, #undochoAccessmentWF').hide();
            $('#customize_accessment_workflow').find('ul').show();
            $('#custom_accessment_workflow_name').attr('disabled',false);
            $('#custom_accessment_workflow_description').attr('disabled',false);
            $('li.custworkflowstep').find('button').show();
            $('#sortable').find('.input-group').remove();

            $('#customize_accessment_workflow').find('input').prop("disabled", false);
            $("#sortable").sortable({ disabled: false });
            $('#sortable, #draggable').removeClass("mx-auto w-50");
            $('#ifdef').removeClass("mx-auto w-50");
            $('.default_accessment_workflow').hide();
            $('.custworkflow').hide();
            $('#submit_accessment_workflow').hide();
            $('#exit_accessment_workflow').hide();
            workflow_list[workflow_k].country = $('#select-accessment-country').val();
            workflow_list[workflow_k].sd_company_id = $('#callCenter').val();
            $(this).hide();
            $('#undochoaccessmentcon').show();
            $("#select-accessment-country, #callCenter ").prop("disabled", true);
            $('#choose_accessment_wf').show();
            $('#default_accessment_T').hide();
            $('#customize_accessment_T').hide();
            $('#default_accessment_btn').show();
            $('#cust_accessment_btn').show();
            var default_text = "<p>This is default workflow and cannot be changed</p>";
            var customize_text = "";
            var country = $('#select-accessment-country').val();
            default_text +="<h4>Name: "+ accessmentWorkflowInfo[country]['name']+"</h4>";
            default_text +="<h5>Description: "+accessmentWorkflowInfo[country]['description']+"</h5>";
            $('#default_accessment_workflow_description').val(accessmentWorkflowInfo[country]['description']);
            $('#default_accessment_workflow_name').val(accessmentWorkflowInfo[country]['name']);
            $('#custom_accessment_workflow_name').val('customize-'+accessmentWorkflowInfo[country]['name']);
            $('#default_accessment_workflow_id').val(accessmentWorkflowInfo[country]['id']);
            $('#default_accessment_workflow').html('');
            $(accessmentWorkflowInfo[country]['sd_workflow_activities']).each(function(k,v){
                default_text +="<li class=\"defworkflowstep\">";
                    default_text +="<div class=\"card w-100 h-25 my-2\">";
                        default_text +="<div class=\"card-body p-3\">";
                            default_text +="<h5 class=\"card-title\"><b>"+v.activity_name+"</b></h5>";
                            default_text +="<p class=\"card-text\">"+v.description+"</p>";
                            default_text +="<div class=\"input-group w-25 mx-auto\">";
                                default_text +="<i class=\"fas fa-arrow-up gobackstep\"></i>";
                                default_text +="<input type=\"text\" readonly=\"readonly\" value="+v.step_backward+" class=\"step_backward form-control form-control-sm\" aria-label=\"Back Steps\" aria-describedby=\"backSteps\">"
                                default_text +="<button type=\"button\" onclick=\"sectionPermission("+v.id+",1)\" class=\"btn btn-primary btn-sm mx-2\" data-toggle=\"modal\" data-target=\"#selectPermission\">View Permission</button>";
                            default_text +="</div>";
                        default_text +="</div>";
                    default_text +="</div>";
                default_text +="</li>"  ;
                customize_text +="<li class=\"custworkflowstep\">";
                    customize_text +="<div class=\"card w-100 h-25 my-2\">";
                        customize_text +="<div class=\"card-body p-3\">";
                            customize_text +="<button class=\"close closewf\">&times;</button>";
                            customize_text +="<h5 class=\"card-title\"><b>"+v.activity_name+"</b></h5>";
                            customize_text +="<p class=\"card-text\">"+v.description+"</p>";
                        customize_text +="</div>";
                    customize_text +="</div>";
                customize_text +="</li>"
            });
            $('#default_accessment_workflow').html(default_text);
            $('#sortable').html(customize_text);
        };

    });


    $('#undochoAccessmentWF').click(function() {
        $('.step_backward').each(function(){
            $(this).prop("disabled", false);
        });
        $('#undo_accessment_activities').show();
        $('#chooseAccessmentCompany').hide();
        $('#submit_accessment_workflow').show();
    });
    $('#undochoaccessmentcon').click(function() {
        $('#exit_accessment_workflow').show();
        $('#choose_accessment_wf').hide();
        $('#chooseAccessmentCompany').hide();
        $('#undo_accessment_activities').hide();
        $('#submit_accessment_country').show();
        $("#select-accessment-country, #callCenter").prop("disabled", false);
    });
    $('#confirmAccessmentWFlist').click(function() {
        $(this).hide();
        $('#undochoAccessmentWF').hide(); 
        $('#chooseDistri').show();
    });
    $('#backDistri').click(function(){
        $('#chooseDistri').hide();
        $('#undochoAccessmentWF').show();
        $('#confirmAccessmentWFlist').show();
    })
    $('#confirmDistriCro').click(function(){
        var text = "";
        var cro_text = "";
        $('[id^=crocompany-').each(function(){
            cro_text +=$(this).text();
            cro_text += " ; "
        });
        if ($('.default_accessment_workflow').is(':visible') && $('.custworkflow').is(':hidden'))
        {
            text +="<tr>";
            text +="<td>"+$('#default_accessment_workflow_name').val()+"</td>";
            text +="<td>"+$('#default_accessment_workflow_description').val()+"</td>";
            text +="<td>"+$('#callCenter option:selected').text()+"</td>";
            text +="<td>"+$('#select-accessment-country option:selected').text()+"</td>";
            text +="<td>"+cro_text+"</td>";
            text +="<td>";
            text +="<div class=\"btn btn-sm btn-primary m-1\" onclick=\"view_workflow("+workflow_k+")\" data-toggle=\"modal\" data-target=\".WFlistView\">View</div>"
            text +="<button class=\"btn btn-sm btn-outline-danger\" onclick=\"$(this).closest('tr').remove();\">Delete</button>";
            text +="</td>";
            text +="<input name=\"workflow["+workflow_k+"][id]\" value="+workflow_list[workflow_k].id+" type=\"hidden\">";
            text +="<input name=\"product_accessment_workflow["+workflow_k+"][sd_company_id]\" value="+workflow_list[workflow_k].sd_company_id+" type=\"hidden\">";
            text +="<input name=\"product_accessment_workflow["+workflow_k+"][status]\" value=\"1\" type=\"hidden\">";
            $.each(resource_list,function(k,workflow){
                $.each(workflow,function(k,company){
                    $.each(company.team_resources,function(k,personDetail){
                        text +="<input name=\"user_assignment["+workflow_k+"]["+personDetail.id+"][sd_user_id]\" value="+personDetail.id+" type=\"hidden\">";
                    })
                    $.each(company.workflow_manager,function(k,personDetail){
                        text +="<input name=\"product_accessment_workflow["+workflow_k+"][sd_user_id]\" value="+personDetail.id+" type=\"hidden\">";
                        workflow_list[workflow_k].sd_user_id = personDetail.id;
                    })
                })
            });

            // text +="<input name=\"user_assignment["+workflow_k+"][sd_user_assignments]\" value="+$('#default_accessment_workflow_id').val()+" type=\"hidden\">";
            text +="</tr>";
        }
        if (($('.default_accessment_workflow').is(':hidden') && $('.custworkflow').is(':visible')))
        {
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

            text +="<input name=\"workflow["+workflow_k+"][name]\" value="+workflow_list[workflow_k].workflow_name+" type=\"hidden\">";
            text +="<input name=\"workflow["+workflow_k+"][description]\" value="+workflow_list[workflow_k].workflow_description+" type=\"hidden\">";
            text +="<input name=\"workflow["+workflow_k+"][country]\" value="+workflow_list[workflow_k].country+" type=\"hidden\">";
            text +="<input name=\"workflow["+workflow_k+"][status]\" value=\"1\" type=\"hidden\">";
            text +="<input name=\"workflow["+workflow_k+"][workflow_type]\" value=\"1\" type=\"hidden\">";
            text +="<input name=\"product_accessment_workflow["+workflow_k+"][sd_company_id]\" value="+workflow_list[workflow_k].sd_company_id+" type=\"hidden\">";
            text +="<input name=\"product_accessment_workflow["+workflow_k+"][sd_user_id]\" value="+workflow_list[workflow_k].sd_user_id+" type=\"hidden\">";//TODO
            text +="<input name=\"product_accessment_workflow["+workflow_k+"][status]\" value=\"1\" type=\"hidden\">";
            $.each(resource_list,function(k,workflow){
                $.each(workflow,function(k,company){
                    $.each(company.team_resources,function(k,personDetail){
                        text +="<input name=\"user_assignment["+workflow_k+"]["+personDetail.id+"][sd_user_id]\" value="+personDetail.id+" type=\"hidden\">";
                    })
                    $.each(company.workflow_manager,function(k,personDetail){
                        text +="<input name=\"product_accessment_workflow["+workflow_k+"][sd_user_id]\" value="+personDetail.id+" type=\"hidden\">";
                        workflow_list[workflow_k].sd_user_id = personDetail.id;
                    })
                })
            });
            $.each(workflow_list[workflow_k]['activities'], function(k, activity_detail){
                console.log(activity_detail['activity_name']);
                text +="<input name=\"workflow_activity["+workflow_k+"]["+k+"][activity_name]\" value=\""+activity_detail['activity_name']+"\" type=\"hidden\">";
                text +="<input name=\"workflow_activity["+workflow_k+"]["+k+"][description]\" value=\""+activity_detail['activity_description']+"\" type=\"hidden\">";
                text +="<input name=\"workflow_activity["+workflow_k+"]["+k+"][step_backward]\" value=\""+activity_detail['step_backward']+"\" type=\"hidden\">";
                text +="<input name=\"workflow_activity["+workflow_k+"]["+k+"][order_no]\"  value=\""+activity_detail['order_no']+"\" type=\"hidden\">";

            });
        };
        text +="</tr>";
        $('#addNewWL').show();
        $('#choworkflow, #chooseDistri, #chooseAccessmentCompany').slideUp();
        $('#workflowlist').slideDown();
        swal({
            title: "Your New Workflow has been SET",
            icon: "success",
          });
        $('#workflow_table').append(text);
        $('#no_workflow_notice').hide();
        workflow_k ++;
    });
//TODODDDDDDD
    // Disable selected option if chosed
    $(document).ready(function(){
        $("#croadd").click(function(){
            $("#croname option:selected").remove();
        });
    });
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

    $('#cust_accessment_btn').click(function() {
        $('#confirm_accessment_activities').show();
        $('#customize_accessment_workflow').slideDown();
        $('#default_accessment_workflow').slideUp();
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
        $( "#sortable" ).sortable({
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
        //         connectToSortable: "#sortable",
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
    $('#croadd').click(function() {
        var cro_name = $('#croname option:selected').text();
        var cro_id = $("#croname").val();
        // var newcro = $('<button type="button"class="btn btn-outline-primary"  onclick="selectCro(' + cro_id + ')" data-toggle="modal" data-target=".bd-example-modal-lg">' + cro_name + '</button>');
        $('#crotable').append('<tr><th id = "crocompany-'+cro_id+'">' + cro_name + '</th><td id = "cromanager-'+cro_id+'"></td><td id = "crostaff-'+cro_id+'"></td><td><button class="btn btn-sm btn-outline-info" onclick="selectCro(' + cro_id + ')" data-toggle="modal" data-target="#addper">Edit</button><button class="btn btn-sm btn-danger ml-3" id="removeCRO-' + cro_id + '" onclick="removeCro(' + cro_id + ')">Delete</button></td></tr>');
        // $('#addcroarea').append(newcro);
        var request = {'id':cro_id};
        //TODO
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
                resource_list[workflow_k][cro_id]=cro_info;
                // $.each(result, function(k,personDetail){
                //     console.log(personDetail);
                //         text +="<div class=\"personnel\" id=\"userid_"+personDetail.id+">"+personDetail.firstname+"</div>";
                //     });
                // $('#personnelDraggable').html(text);
                // croDroppableArea();
            },
            error:function(response){

            }
        });
     });
});

function sectionPermission(activity_id, readonly){
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
                $("div[id^=l1section]").each(function(){
                    var flag = 0;
                    var id = $(this).attr('id').split('-');
                    if((typeof result[id[1]]!="undefined")||(result[id[1]]!="0")){
                            if(result[id[1]] == 1) {
                                $(this).find("input[id^=write]").prop('checked',true);
                                $(this).find("input[id^=read]").prop('checked',true);
                                flag = 1;
                            }
                            else if(result[id[1]] == 2) {
                                flag = 1;
                                $(this).find("input[id^=write]").prop('checked',false);
                                $(this).find("input[id^=read]").prop('checked',true);
                            }
                            return true;  
                    }else{
                        $(this).find("input[id^=write]").prop('checked',false);
                        $(this).find("input[id^=read]").prop('checked',false);
                    }
                });
            },
            error:function(response){

            },
        });
        $("[id^=write]").each(function(){
            $(this).prop('disabled',true);}
        );
        $("[id^=read]").each(function(){
            $(this).prop('disabled',true);});
    }else{
        if(readonly==2){
            if(!($('#permissionFooter').find('button').length > 0)){
                var text ="<button type=\"button\" class=\"btn btn-primary\" onclick=\"savePermission("+activity_id+")\" data-dismiss=\"modal\">Save</button>";
                $('#permissionFooter').prepend(text);
            }else $('#permissionFooter').find('button').attr('onclick',"savePermission("+activity_id+")");
        }
        $("div[id^=l1section]").each(function(){
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
            
            if((typeof permission_list[workflow_k]=="undefined")||(typeof permission_list[workflow_k][activity_id]=="undefined")
                    ||(typeof permission_list[workflow_k][activity_id][id[1]]=="undefined")){
                        $(this).find("input[id^=write]").prop('checked',false);
                        $(this).find("input[id^=read]").prop('checked',false);
                        return true;
            }else if(permission_list[workflow_k][activity_id][id[1]] == 1) {
                        $(this).find("input[id^=write]").prop('checked',true);
                        $(this).find("input[id^=read]").prop('checked',true);
                        flag = 1;
                    }
                    else if(permission_list[workflow_k][activity_id][id[1]] == 2) {
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
function savePermission(activity_id){
    if(typeof permission_list[workflow_k]=='undefined') permission_list[workflow_k]=[];
    if(typeof permission_list[workflow_k][activity_id]=='undefined') permission_list[workflow_k][activity_id]=[];
    $("div[id^=l2section]").each(function(){
        var write = 0;
        var pid = $(this).attr('id').split('-');
        $(this).find('[id^=l1section]').each(function(){
            
            var id = $(this).attr('id').split('-');
            if($(this).find("input[id^=write]").prop('checked')==true){
                permission_list[workflow_k][activity_id][id[1]]=1;
                write=1;
            }else if($(this).find("input[id^=read]").prop('checked') == true){
                permission_list[workflow_k][activity_id][id[1]]=2;
                write=1;
            }else {
                permission_list[workflow_k][activity_id][id[1]] = 0;
            }
        })
        if(write) permission_list[workflow_k][activity_id][pid[1]] = 1;
        else permission_list[workflow_k][activity_id][pid[1]] = 0;
    });
    $('#custAccessmentPermission-'+activity_id).text('View Permission');
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
    $('#viewCC').text(call_center_list[workflow_list[workflow_k]['sd_company_id']]['name']);
    $('#viewCountry').text(workflow_list[workflow_k]['country']);
    $('#viewDesc').text(workflow_list[workflow_k]['workflow_description']);
    var team_resources_text="";
    $.each(resource_list[workflow_k], function(company_id, company_detail){
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
        connectToSortable: "#sortable",
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