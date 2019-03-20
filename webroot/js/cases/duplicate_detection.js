// $(document).ready(function() {
//     var unsaved = false;

//     $("input:not(:button,:submit),textarea,select").change(function(){   //triggers change in all input fields including text type
//         unsaved = true;
//     });

//     window.onbeforeunload = function (){
//         if(unsaved){
//             return 'Your data is changed, are you sure you want to complete?';
//         }
//     };
// });

$(document).ready(function(){
    /**
     *
     * change Workflow Name according to Product selection
     */
    $('#product_id').change(function(){
        var text = "<option value=\"\">Select Country:</option>";
        var product_id = $(this).val();
        $(productInfo).each(function(k,v){
            if(v.id == $('#product_id').val()){
                $(v.sd_product_workflows).each(function(k,v){
                    text +="<option value=\""+v.id+"\">"+v.sd_workflow.country+"</option>"
                });
            }
        });
        $('#sd_product_workflow_id').html(text);
        $('#input_product_id').val(product_id);
    });

    $('#sd_product_workflow_id').change(function(){
        var product_workflow_id = $(this).val();
        $('#input_product_workflow_id').val(product_workflow_id);
    });


    /**
     *show caseNo
     */
    // $('#no_of_sae').change(function(){console.log($('#no_of_sae').val());
    //     var text = "";
    //     for(i = 1;i <= $('#no_of_sae').val();i++){
    //         text += "<input class=\"form-control\" type=\"text\" readonly=\"readonly\" id=\"caseNO-"+i+"\" name=\"case[caseNo]["+i+"]\"value=\""+randCaseNo+str_pad(i,5)+"\">";
    //     }
    //     $('#show_selected_sae_name').html(text);
    // })
});

// function str_pad(str, max){
//     return str.length>=max?str:str_pad("0"+str, max);
// }
function searchWhoDra(){
    var request = {
        'atc-name': $("#atc").val(),
        'drug-name':$("#drugname").val(),
        'medicinal-prod-id':$('#medicalProd').val(),
        'trade-name':$('#tradename').val(),
        'ingredient':$('#ingredient').val(),
        'formulation':$('#formulation').val(),
        'country':$('#inputState').val(),
    };
    console.log(request);

}
function checkDuplicate(){
    $("[id=checkbutton]").hide();
    var fields =[
        'product_id',
        'sd_product_workflow_id',
        'patient_initial',
        'patient_age',
        'patient_age_unit',
        'patient_gender',
        'patient_dob',
        'reporter_firstname',
        'reporter_lastname',
        'report_term',
        'event_onset_date',
        'patient_ethnic_origin',
        'patient_age_group',
        'meddraptname',
        'meddralltname',
        'meddrahltname',
    ];
    var request={
        'userId':userId
    };
    $.each(fields,function(k,field_label){
        if(!(($('#'+field_label).val()=="")||($('#'+field_label).val()=="null"))) request[field_label] = $('#'+field_label).val();
    });
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/duplicate-detection',
        data:request,
        success:function(response){
            console.log(response);
            var result = $.parseJSON(response);
            var text = "";
            if(response!="[]"){
                text +="<h3>Search Results</h3>";
                text +="<table class=\"table table-hover\">";
                text +="<thead>";
                text +="<tr>";
                text +="<th class=\"align-middle text-center\" scope=\"col\">Caes No.</th>";
                text +="<th scope=\"col\">Patient Initial</th>";
                text +="<th scope=\"col\">Patient Age</th>";
                text +="<th scope=\"col\">Patient Gender</th>";
                text +="<th scope=\"col\">Patient Date of Birth</th>";
                text +="<th scope=\"col\">Reporter Name</th>";
                text +="<th scope=\"col\">Event Report Term</th>";
                text +="<th scope=\"col\">Meddra Pt Term</th>";
                text +="</tr>";
                text +="</thead>";
                text +="<tbody>";
                var age_unit={"800":"decade(s)","801":"year(s)","802":"Month(s)","803":"Week(s)","804":"Day(s)","805":"Hour(s)"};
                var gender=["","male","female","Unknown","not specified"];
                $.each(result, function(k,caseDetail){
                    text += "<tr>";
                    text += "<td><button type=\"button\" class=\"btn btn-outline-info\" onclick=\"caseDetail(\'"+caseDetail.caseNo+"\')\" data-toggle=\"modal\" data-target=\".CaseDetail\">" + caseDetail.caseNo;
                    text += "<div id=\"version-"+ caseDetail.caseNo+"\"></b>(ver:"+caseDetail.versions+")";
                    text +="</button></td>";
                    text += "<td>";
                    if(!jQuery.isEmptyObject(caseDetail.patient_initial)) text +=caseDetail.patient_initial;
                    text +=  "</td>";
                    text += "<td>";
                    if(!jQuery.isEmptyObject(caseDetail.patient_age)) {text +=caseDetail.patient_age+" "+age_unit[caseDetail.patient_age_unit]}
                    text += "</td>";
                    text += "<td>";
                    if(!jQuery.isEmptyObject(caseDetail.patient_gender)) text +=gender[caseDetail.patient_gender];
                    text += "</td>";
                    text += "<td>";
                    if(!jQuery.isEmptyObject(caseDetail.patient_dob)) text += caseDetail.patient_dob;
                    text += "</td>";
                    text += "<td>";
                    if(!jQuery.isEmptyObject(caseDetail.reporter_firstname)) text +=caseDetail.reporter_firstname+" ";
                    if(!jQuery.isEmptyObject(caseDetail.reporter_lastname)) text += caseDetail.reporter_lastname;
                    text += "</td>";
                    text += "<td>";
                    if(!jQuery.isEmptyObject(caseDetail.event_report_term)) text +=caseDetail.event_report_term;
                    text += "</td>";
                    text += "<td>";
                    if(!jQuery.isEmptyObject(caseDetail.meddra_pt)) text +=caseDetail.meddra_pt;
                    text += "</td>";
                    text += "</tr>";
                })
                text +="</tbody>";
                text +="</table>";
            }else text+="<div class=\"my-3 text-center\"><h3>No Duplicate AER(s) Found</h3></div>"
            //text +="<div class=\"text-center\"> <button onclick=\"clearResult()\" class=\"btn btn-outline-warning mx-2 w-25\">Search Again</button>";
            text +="<div onclick=\"createCase()\" class=\"btn btn-primary float-right w-25 my-3\" style=\"cursor:pointer;\">Create This Case</div> </div>";
            $("#caseTable").html(text);
        },
        error:function(response){
                console.log(response.responseText);

            $("#textHint").html("Sorry, no case matches");

        }
    });
    $("input").each(function(){
        $(this).prop('readonly', true);
    });
    $("select").each(function(){
        $(this).prop("disabled", true);
    });

}
function createCase(){
    var confirmFlag = 0;
    swal({
        title: "Is your duplicate search completed?",
        text: "",
        icon: "warning",
        buttons: ["No", "Yes - Continue"],
        dangerMode: true,
        closeOnClickOutside: false,
      })
      .then((value) => {
           $("select").each(function(){
            $(this).prop("disabled", false);
            
        });
        document.getElementById("caseRegistrationForm").submit();
      });
}
function clearResult(){
    $('#caseTable').html("");
    $("input").each(function(){
        $(this).prop("readonly", false);;
    });
    $("select").each(function(){
        $(this).prop("disabled", false);;
    });
    $("[id=checkbutton]").show();
}
function caseDetail(caseNo)
{
    $('#caseLabel').text("Case Detail:"+caseNo);
    $('#iframeDiv').attr('src','/sd-tabs/showdetails/'+caseNo+'/'+$('#version-'+caseNo).val()+'/1?readonly=1');
}