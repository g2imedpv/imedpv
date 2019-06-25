jQuery(function($) {
    $(document).ready(searchProd());

    $('#advsearch').click(function () {
        $(this).hide();
        $('#advsearchfield').show();
    });

});
function searchProd(){
    var request = {
        'searchName': $("#key_word").val(),
        'productName':$("#product_name").val(),
        'studyName':$("#study_no").val(),
        'userId':userId
    };
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-products/search',
        data:request,
        success:function(response){
            console.log(response);
            var result = $.parseJSON(response);
            var text = "";
            text +="<p class=\"pageTitle\">"+i18n.gettext("Product List")+"</p>";
            text +="<table class=\"table table-hover table-bordered\" id=\"search_result\">";
            text += "<thead>";
            text +="<tr>";
            text +="<th scope=\"col\">"+i18n.gettext("Product Name")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Study Number")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Study Type")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Sponsor")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("mfr name")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Status")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Workflows")+" / "+i18n.gettext("Country")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Product Detail")+"</th>";
            text +="</tr>";
            text +="</thead>";
            text +="<tbody>";
            var study_type=["","Clinical trials", "Individual patient use","Other studies"];
            $.each(result, function(k,caseDetail){
                text += "<tr>";
                text += "<td>" + caseDetail.product_name + "</td>";
                text += "<td>" + caseDetail.study_no +"</td>";
                text += "<td>" + i18n.gettext(study_type[caseDetail.study_type])+ "</td>";
                text += "<td>"+caseDetail.sd_company.company_name+"</td>";
                text += "<td>"+caseDetail.mfr_name+"</td>";
                text += "<td>"+i18n.gettext("new")+"</td>";
                text += "<td>";
                $.each(caseDetail.sd_product_workflows, function(k,product_workflowdetail){
                    text += "<div class=\"btn btn-sm btn-outline-info mx-1\" data-toggle=\"modal\" onclick=\"view_workflow("+product_workflowdetail.id+")\" data-target=\".WFlistView\">"+product_workflowdetail.sd_workflow.name+" / "+i18n.gettext(product_workflowdetail.sd_workflow.country+"")+"</div>";
                });
                text += "</td>";
                text += "<td><div class=\"btn btn-sm btn-outline-info\" data-toggle=\"modal\" onclick=\"view_product("+caseDetail.id+")\" data-target=\".product_detail\">"+i18n.gettext("View Detail")+"</div></td>";
                text +="<div id=\"product_"+caseDetail.id+"\" style=\"display:none\">"+JSON.stringify(caseDetail)+"</div>";
                text += "</tr>";
            });
            text +="</tbody>";
            text +="</table>";
            $("#searchProductlist").html(text);
            $('#search_result').DataTable();
        },
        error:function(response){
                console.log(response.responseText);
            $("#textHint").html(i18n.gettext("Sorry, no case matches"));

        }
    });
}
function view_product(product_id){
    var study_type=["",i18n.gettext("Clinical trials"), i18n.gettext("Individual patient use"),i18n.gettext("Other studies")];
    var status = ["",i18n.gettext("Active"),i18n.gettext("Close")];
    var blinding_tech = ["",i18n.gettext("Single blind"), i18n.gettext("Open-label")]
    var product_flag = ["",i18n.gettext("Suspect"),i18n.gettext("Concomitant"),i18n.gettext("Interacting")];
    product_detail = $.parseJSON($('#product_'+product_id).text());
    $('#detail_product_name').val(product_detail['product_name']);
    $('#detail_sponsor_company').val(product_detail['sd_company']['company_name']);
    $('#detail_sd_product_flag').val(product_flag[product_detail['sd_product_flag']]);
    $('#detail_blinding_tech').val(blinding_tech[product_detail['blinding_tech']]);
    $('#detail_study_name').val(product_detail['study_name']);
    $('#detail_study_no').val(product_detail['study_no']);
    $('#detail_mfr_name').val(product_detail['mfr_name']);
    $('#detail_study_type').val(study_type[product_detail['study_type']]);
    $('#detail_whodracode').val(product_detail['WHODD_code']);
    $('#detail_whodraname').val(product_detail['WHODD_name']);
    $('#detail_WHODD_decode').val(product_detail['WHODD_decode']);
    $('#detail_start_date').val(product_detail['start_date']);
    $('#detail_end_date').val(product_detail['end_date']);
    $('#detail_status').val(i18n.gettext(status[product_detail['status']]));
    $('#detail_short_desc').val(product_detail['short_desc']);
    $('#detail_product_desc').val(product_detail['product_desc']);

}
function view_workflow(workflow_k){
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-product-workflows/view/'+workflow_k,
        success:function(response){
            console.log(response);
            var result=$.parseJSON(response);
            var workflow_info = result['sd_workflow'];
            $('#viewWFname').text(workflow_info['name']);
            $('#viewCC').text(result['sd_company']['company_name']);
            $('#viewCountry').text(i18n.gettext(workflow_info['country']));
            $('#viewDesc').text(workflow_info['description']);
            $('#allocate_workflow').attr('href','/sd-product-workflows/allocateWorkflow/'+workflow_k);
            $('#viewMan').html("<b>"+result['sd_user']['firstname']+" "+result['sd_user']['lastname']+"</b> FROM "+result['sd_user']['sd_company']['company_name']);
            var team_resources_text="";
            $.each(result['sd_user_assignments'], function(k, v){
                    console.log(v);
                    team_resources_text += "<div><b>"+v['urc']['firstname']+" "+v['urc']['lastname']+"</b> From"+v['urc']['company_name']+"</div>";
            });
            $('#viewRes').html(team_resources_text);
            var activities_text="";
            $(workflow_info['sd_workflow_activities']).each(function(k,activity_detail){
                activities_text +="<span class=\"badge badge-info px-5 py-3 m-3\"><h5>"+i18n.gettext(activity_detail['activity_name']+"")+"</h5><h8>"+activity_detail['description']+"</h8></span><i class=\"fas fa-long-arrow-alt-right\"></i>";
            });
            activities_text+="<span class=\"badge badge-info px-5 py-3 m-3\"><h5>Complete</h5><h8>"+i18n.gettext("End of the case")+"</h8></span>"
            $('#view_activities').html(activities_text);
        },
        error:function(response){
                console.log(response.responseText);

            $("#textHint").html(i18n.gettext("Sorry, no case matches"));

        }
    });
}
