//for contact list table dateTable layout
$(document).ready(function(){
    $('#contact_list').DataTable();
});
jQuery(function($) {
    $(document).ready(searchContact());

});
//for search function
function searchContact(){
    var request = {
        'keyWord':$("key_word").val(),
        'contactID':$("#Contact_ID").val(),
        'contactRoute':$("#Contact_Route").val(),
        'contactType':$("#Contact_Type").val(),
        //'userId':userId
    };
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-contacts/search',
        data:request,
        success:function(response){
            console.log(response);
            var result = $.parseJSON(response);
            var text = "";
            text +="<p class=\"pageTitle\">"+i18n.gettext("Product List")+"</p>";
            text +="<table class=\"table table-hover table-bordered\" id=\"search_result\">";
            text += "<thead>";
            text +="<tr>";
            text +="<th scope=\"col\">"+i18n.gettext("Contact ID")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Contact Type")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Contact Route")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Contact Format")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Phone")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Email")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Address")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("City")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("State/Province")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Country")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Website")+"</th>";
            text +="</tr>";
            text +="</thead>";
            text +="<tbody>";
            var $comtact_format=["","E2B", "CIOMS","MedWatch"];
            var $comtact_type=["","Pharmaceutical Company","Regulatory Authority","Health professional","Regional Pharmacovigilance Center","WHO Collaborating Center for International Drug Monitoring","Other","CRO","Call Center"];
            var $comtact_route=["","Email","ESTRI Gateway","Manual"];
            $.each(result, function(k,caseDetail){
                text += "<tr>";
                text += "<td>" + caseDetail.contactId + "</td>";
                text += "<td>" + i18n.gettext($comtact_type[caseDetail.contact_type])+"</td>";
                text += "<td>" + i18n.gettext($comtact_route[caseDetail.preferred_oute])+"</td>";
                text += "<td>" + i18n.gettext($comtact_format[caseDetail.format_type])+"</td>";
                text += "<td>"+caseDetail.phone+ "</td>";
                text += "<td>"+caseDetail.email+address+"</td>";
                text += "<td>"+caseDetail.address+"</td>";
                text += "<td>"+caseDetail.city+"</td>";
                text += "<td>"+caseDetail.state_province+"</td>";
                text += "<td>"+caseDetail.country+"</td>";
                text += "<td>"+caseDetail.websites+"</td>";
                //text += "<td>";
                // $.each(caseDetail.sd_product_workflows, function(k,product_workflowdetail){
                //     text += "<div class=\"btn btn-sm btn-outline-info mx-1\" data-toggle=\"modal\" onclick=\"view_workflow("+product_workflowdetail.id+")\" data-target=\".WFlistView\">"+product_workflowdetail.sd_workflow.name+" / "+i18n.gettext(product_workflowdetail.sd_workflow.country+"")+"</div>";
                // });
                // text += "</td>";
                // text += "<td><div class=\"btn btn-sm btn-outline-info\" data-toggle=\"modal\" onclick=\"view_product("+caseDetail.id+")\" data-target=\".product_detail\">"+i18n.gettext("View Detail")+"</div></td>";
                // text +="<div id=\"product_"+caseDetail.id+"\" style=\"display:none\">"+JSON.stringify(caseDetail)+"</div>";
                // text +="<td><a class=\"btn btn-outline-info btn-sm\"  role=\"button\" href=\"/sd-products/edit/"+caseDetail.id+"\"><i class=\"fas fa-edit\"></i></a></td>";
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