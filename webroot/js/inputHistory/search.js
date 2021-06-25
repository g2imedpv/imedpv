//for contact list table dateTable layout
$(document).ready(function(){
    $('#contact_list').DataTable();
});
jQuery(function($) {
    $(document).ready(searchHistory());

});
//for search function
function searchHistory(){
    var request = {
        'tab_name':$("tab_name").val(),
        'section_name':$("#section_name").val(),
        'user_name':$("#user_name").val(),
        'modified_date_start':$("#modified_date_start").val(),
        'modified_date_end':$("#modified_date_end").val(),
    };
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-input-histories/search/'+caseId,
        data:request,
        success:function(response){
            console.log(response);
            var result = $.parseJSON(response);
            var text = "";
            text +="<table class=\"table table-hover table-bordered\" id=\"search_result\">";
            text += "<thead>";
            text +="<tr>";
            text +="<th scope=\"col\">"+i18n.gettext("Field Name")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("User Name")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("User Email")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Activity Name")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Input Data")+"</th>";
            text +="<th scope=\"col\">"+i18n.gettext("Modified Time")+"</th>";
            text +="</tr>";
            text +="</thead>";
            text +="<tbody>";
            $.each(result, function(k,caseDetail){
                text += "<tr>";
                text += "<td>" + caseDetail.field.field_label + "</td>";
                text += "<td>" + caseDetail.user.firstname+" "+ caseDetail.user.lastname + "</td>";
                text += "<td>" + caseDetail.user.email + "</td>";
                text += "<td>" + caseDetail.activity.activity_name + "</td>";
                text += "<td>" + caseDetail.input + "</td>";
                var datestr = new Date(caseDetail.time_changed);
                text += "<td>" + caseDetail.time_changed + "</td>";
                text += "</tr>";
            });
            text +="</tbody>";
            text +="</table>";
            $("#searchInputHistoryList").html(text);
            $('#search_result').DataTable();
        },
        error:function(response){
                console.log(response.responseText);
            $("#searchInputHistoryList").html(i18n.gettext("Sorry, no case matches"));

        }
    });
}