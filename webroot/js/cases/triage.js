$(document).ready(function(){
        // IF invalid case
        var patient_element = false;
        $('#patientInfo :input').each(function(){
            if(($(this).val()!=null)&&($(this).val()!= ""))
            patient_element = true;
        });
        var reporter_element = false;
        $('[id^=reporterField]').each(function(){
            if(($(this).val()!=null)&&($(this).val()!= ""))
            reporter_element = true;
        });
        var event_element = false;
        $('[id^=eventField]').each(function(){
            if(($(this).val()!=null)&&($(this).val()!= ""))
            event_element = true;
        });
        var validCase = patient_element + reporter_element + event_element;
    $("#confirmElements").click(function(){
        if (validCase <= 1) {
            swal("This is an invalid case and it will be inactivated. Are you sure you want to continue?","","warning", {
                buttons: {
                    continue: true,
                    cancel: "Cancel"
                },
            })
            .then((value) => {
                if (value) {
                    var request ={};
                    $("[name^=field_value]").each(function(){
                        if($(this).val()!="")
                        {
                            var field_id = $(this).attr('name').split('[')[1];
                            console.log(field_id.split(']')[0]);
                            request[field_id.split(']')[0]] = $(this).val();
                        }
                    });
                    if($('[id=patientField_sex]').val()!="")
                        request['93'] = $('[id=patientField_sex]').val();
                    if($('[id=patientField_ageunit]').val()!="")
                        request['87'] = $('[id=patientField_ageunit]').val();
                    console.log(request);
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': csrfToken
                        },
                        type:'POST',
                        url:'/sd-cases/deactivate/'+caseNo+'/'+versionNo,
                        data:request,
                        success:function(response){
                            swal("Your case has been inactivated","", "warning",{
                                buttons: {
                                    continue: true,
                                },
                            }).then((value) => {
                                if (value) {
                                    window.location.href = "/sd-cases/caselist";    
                                }
                            });              
                        },
                        error:function(response){
                            console.log(response);
                        }
                    });
                }
            });
        }else if(validCase == 2) {
            swal("This is an invalid case. Do you want to continue this case?","","warning", {
                buttons: {
                    Yes: true,
                    No: true,
                    cancel: "Cancel"
                },
            })
            .then((value) => {
                switch (value) {
                    case "Yes":
                        swal("Please Select Reasons in following step","", "success");
                        $("#basicInfo :input").each(function(){
                            $(this).prop("readonly", true);
                        });
                        $('#confirmElements').hide();
                        $('#selRea').show();
                        $('[id^=reason]').each(function(){
                            $(this).prop('disabled',false)
                        });
                        $("select").each(function(){
                            $(this).prop("disabled", true);
                        });
                        break;
                    case "No":
                        var request ={};
                        $("[name^=field_value]").each(function(){
                            if($(this).val()!="")
                            {
                                var field_id = $(this).attr('name').split('[')[1];
                                console.log(field_id.split(']')[0]);
                                request[field_id.split(']')[0]] = $(this).val();
                            }
                        });
                        if($('[id=patientField_sex]').val()!="")
                            request['93'] = $('[id=patientField_sex]').val();
                        if($('[id=patientField_ageunit]').val()!="")
                            request['87'] = $('[id=patientField_ageunit]').val();
                        console.log(request);
                        $.ajax({
                            headers: {
                                'X-CSRF-Token': csrfToken
                            },
                            type:'POST',
                            url:'/sd-cases/deactivate/'+caseNo+'/'+versionNo,
                            data:request,
                            success:function(response){
                                swal("Your case has been inactivated","", "warning");
                                window.location.href = "/sd-cases/caseList";                     
                            },
                            error:function(response){

                            }
                        });
                        break;
                }
            });
        }
        // ELSE valid case
        else {
            $("#basicInfo :input").each(function(){
                $(this).prop("readonly", true);
            });
            $("select").each(function(){
                $(this).prop("disabled", true);
            });
            $('#confirmElements').hide();
            $('#prioritize').show();}
    });

    $("#checkbtn").click(function(){
        //$(this).hide();
        $('#clear').show();
    });

    $("#clear").click(function(){
        $(this).hide();
    });

    $("#caseRegAdvBtn").click(function(){
        $(this).hide();
        $('#caseRegAdvFields').show();
    });

    $("#reason-3 ").click(function(){
        $('#othersInput').toggle();
    });

    $("#selReaBack").click(function(){
        $('[id^=reason]').each(function(){
            $(this).prop('disabled',true)
        });
        $('select').each(function(){$(this).prop('disabled',false);})
        $('#selRea').hide();
        $('#confirmElements').show();
        $("#basicInfo :input").each(function(){
            $(this).prop("readonly", false);
        });
    });
    $("#confirmRea").click(function(){
        $(this).hide();
        $('#selReaBack').hide();
        $('#prioritize').show();
        $('#prioritize :input').prop('disabled',false);
    });
    $("#prioritizeBack").click(function(){
        $('#prioritize').hide();
        $('#prioritize :input').prop('disabled',true);
        if(validCase == 2) {
            $('#selReaBack').show();
            $('#confirmRea').show();
        }
    });

    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
    var prioritizeType = 0;
    $('[id^=prioritize]').change(function(){
        var text="";
        if($('#prioritize-seriousness-1').prop('checked')&&$('#prioritize-related-1').prop('checked')&&$('#prioritize-unlabelled-1').prop('checked')) prioritizeType = 1;
        if($('#prioritize-seriousness-2').prop('checked')&&$('#prioritize-related-1').prop('checked')&&$('#prioritize-unlabelled-1').prop('checked')) prioritizeType = 2;
        if($('#prioritize-seriousness-4').prop('checked')) prioritizeType = 3;
        if(prioritizeType == 1){
            text +="7 Days Report, Priority: High, Due Date: "
        }
        if(prioritizeType == 2){
            text +="15 Days Report, Priority: High, Due Date: "
        }
        if(prioritizeType == 0){
            text +="15 Days Case, Priority: Medium, Due Date: "
        }
        if(prioritizeType == 1){
            text +="90 Days Case, Priority: Low, Due Date: "
        }
        $('#prioritizeType').html(text);
    });
});
function savenexit(){
    $('#triageForm').attr('action','/sd-cases/triage/'+caseNo);
    document.getElementById("triageForm").submit();
}

function endTriage(){
    var request ={};
    $("[name^=field_value]").each(function(){
        if($(this).val()!="")
        {
            console.log($(this).attr('name'));
            request[$(this).attr('name')] = $(this).val();
        }
    });
    request['endTriage'] = 1;
    if($('[id=patientField_sex]').val()!="")
        request['field_value[93]'] = $('[id=patientField_sex]').val();
    if($('[id=patientField_ageunit]').val()!="")
        request['field_value[87]'] = $('[id=patientField_ageunit]').val();
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/triage/'+caseNo+'/'+versionNo,
        data:request,
        success:function(response){

            console.log(response);
        },
        error:function(response){
            console.log(response);
        },
    });
    var text ="";
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-users/searchNextAvailable/'+caseNo+'/'+versionNo,
        success:function(response){
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
            text +="<div class=\"text-center\"><div class=\"btn btn-primary w-25\" onclick=\"confirmEndTriage()\">Confirm</div></div>";
            text +="</div>";
            $('#action-text-hint').html(text);
        },
        error:function(response){
            console.log(response.responseText);
        },
    });
}
function confirmEndTriage(){
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
        url:'/sd-cases/forward/'+caseNo+'/'+versionNo+'/0',
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