// For popover effect on comments
var $popover = jQuery.noConflict(); // This line is required if call more than 1 jQuery function from library
$popover(document).ready(function(){
    $popover('[data-toggle="popover"]').popover({
        html: true,
        trigger: 'hover focus',
        delay: { show: 100, hide: 500 }
    });
});

// refresh function

// var refreshSn = function ()
// {
//     var time = 100; // 10 mins
//     setTimeout(
//         function ()
//         {
//         $.ajax({
//            url: '/sd-tabs/refresh_session',
//            cache: false,
//            complete: function () {refreshSn();}
//         });
//     },
//     time
// );
// };

// // Call in page
// refreshSn()

jQuery(function($) {  // In case of jQuery conflict
  // TO DO: This line should have deleted, BUT will not work if delete directly
// Date Input Validation ("Latest received date (A.1.7.b)" MUST Greater than "Initial Received date (A.1.6.b)")
    $("#section-1-date-12").change(function () {
        var startDate = $('#section-1-date-10').val();
        var endDate = $('#section-1-date-12').val();

        if (Date.parse(endDate) <= Date.parse(startDate)) {
            alert("End date should be greater than Start date");
            document.getElementById("section-1-date-12").value = "";
        }
    });


$("#searchSMQ").keyup(function(){
    $('#SMQoptions').show();
    $('#meddra_smq').val("");
    if($(this).val().length >=3)
        searchSMQ($(this).val());
    else $("#SMQoptions").hide();
});

function searchSMQ(keyword){
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/med-dra/searchSMQ/'+keyword,
        success:function(response){
            console.log(response);
            var result = $.parseJSON(response);
            var html = "<ul>";
            $.each(result, function(k, v){
                html = html+"<li id=\"smq_options-"+v['smq_code']+"\">"+v['smq_name']+"</li>";
            });
            html = html+"</ul>";
            $('#SMQoptions').html(html);
            $('[id^=smq_options]').click(function(){
                console.log("here");
                var smq_code = $(this).attr('id').split('-')[1];
                var smq_name = $(this).text();
                $('#searchSMQ').val(smq_name);
                $('#meddra_smq').val(smq_code);
                $("#SMQoptions").hide();
            });
        },
        error:function(response){
            console.log(response.responseText);
        $("#textHint").html(i18n.gettext("Sorry, no case matches"));
        }
    });
}

// Control the topNav and leftNav running with the scroll
    $(window).scroll(function() {
        if ($(window).scrollTop() > 176) {
            $('#topbar').addClass('topbarchange');
            $('#sidenav').addClass('sidenavchange');
            $('.dataentry').addClass('dataentrychange');
            $('#searchArea').addClass('searchAreaChange');
        }
        else {
            $('#topbar').removeClass('topbarchange');
            $('#sidenav').removeClass('sidenavchange');
            $('.dataentry').removeClass('dataentrychange');
            $('#searchArea').removeClass('searchAreaChange');
        }
    })

// To uncheck a radio box if it had been checked
    $(function(){
        $('input[type="radio"]').click(function(){
            var $radio = $(this);

            // if this was previously checked
            if ($radio.data('waschecked') == true)
            {
                $radio.prop('checked', false);
                $radio.data('waschecked', false);
            }
            else
                $radio.data('waschecked', true);

            // remove was checked from other radios
            $radio.siblings('input[type="radio"]').data('waschecked', false);
        });
    });


// Dashboard "Case search modal" for clearing inputs
    $(".clearsearch").click(function(){
        $(':input').val('');
    });

// Dashboard "Search case" button of shadow effect
    $('#searchBtn').hover(
        function(){ $(this).addClass('shadow') },
        function(){ $(this).removeClass('shadow')
    });

// Dashboard "Print" button
$('a#printPage').click(function(){
    window.print();
    return false;
});

// Data Entry Search Area
$("#DeSearch").click(function() {
    $("#searchArea").toggle();
})

// Make Query Box left nav button has "active" effect
$(function(){
    // If clicked the first level menu
    $('ul.queryBoxLeft > a').each(function(){
        if (
            $(this).prop('href').split('/').slice(5,6).toString() == (window.location.href).split('/').slice(5,6).toString()) {
                $(this).addClass('queryBoxActive');
        }
    });

    // Make nav button has "active" effect
    // If clicked the first level menu
    $('#navbarNavDropdown > ul > li > a').each(function(){
        if (
            $(this).prop('href') == window.location.href) {
                $(this).addClass('active');
        }
    });

    // If clicked the second (submenu) level
    $('#navbarNavDropdown > ul > li > ul > a').each(function(){
        if (
            ($(this).prop('href').split('/').slice(3,4)).toString() == ((window.location.pathname).split('/').slice(1,2)).toString() ) {
                $(this).parent().siblings('a').addClass('active');
        }
    });

});

});
function checkFieldsDetail(){
    var request = {};
    var count = 0;
    $('[id^=result-caseNo-]').each(function(){
        var singleRequest = {
            'caseNo': $(this).html(),
            'version':  $("#version-"+$(this).html()).html()
        }
        console.log(singleRequest);
        request[count++] = singleRequest;
    });
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/checkfieldsdetail',
        data:request,
        beforeSend:function () {
            $('.loadingSpinner').show();
        },
        success:function(response){
        var w = window.open('about:blank');
        w.document.write(response);},
        error:function(response){
            console.log(response.responseText);
        }
    });
}

function onQueryClicked(preferrenceId = null){
    //TODO enhance search
    let $searchProductName;
    if($("#searchProductNameMin").val() =="")
        $searchProductName = $("#searchProductName").val();
    else $searchProductName = $("#searchProductNameMin").val();
    var request = {
        'activity_due_date_start':$("#activity_due_date_start").val(),
        'activity_due_date_end':$("#activity_due_date_end").val(),
        'submission_due_date_start':$("#submission_due_date_start").val(),
        'submission_due_date_end':$("#submission_due_date_end").val(),
        'case_received_date_start':$("#case_received_date_start").val(),
        'case_received_date_end':$("#case_received_date_end").val(),
        'caseNo': $("#caseNo").val(),
        'searchProductName':$searchProductName,
        'userId':userId,
        'caseStatus':$("#caseStatus").val(),
        'patient_id':$("#patient_id").val(),
        'patient_dob':$('#patient_dob').val(),
        'patient_gender':$('#patient_gender').val(),
        'meddra_smq':$('#meddra_smq').val(),
        'meddra_smq_scope':$('#meddra_smq_scope').val(),
        'meddraResult':$("#meddraResult-496").val(),
    };
    if (preferrenceId!=null)
    request['preferrenceId'] = preferrenceId;
    var today = new Date();
    console.log('request :>> ', request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/search',
        data:request,
        beforeSend:function () {
            $('.loadingSpinner').show();
        },
        success:function(response){
            $("#textHint").html("");
            console.log(response);
            if (response==false) {
                $("#textHint").html(i18n.gettext("Sorry, no case matches"));
                return}
            var result = $.parseJSON(response);
            var text = "";
            text +="<form method=\"post\" target=\"_blank\" action=\"/sd-cases/checkfieldsdetail\" id=\"checkdetail_form\">";
            text +="<input type=\"hidden\" name=\"_csrfToken\" value=\""+csrfToken+"\">"
            $.each(result, function(k,caseDetail){
                text +="<input name=\"cases["+k+"][caseNo]\" value=\""+caseDetail.caseNo+"\" type=\"hidden\">"
                text +="<input name=\"cases["+k+"][version]\" value=\""+caseDetail.versions+"\" type=\"hidden\">";
            });
            text +="</form>";
            text +="<button class=\"caseDetail btn btn-info\" type=\"submit\" value=\"Submit\" form=\"checkdetail_form\">Open Line Listing</button>";
            text +="<table id=\"caseTable\" class=\"table table-striped table-bordered table-hover\">";
            text +="<thead>";
            text +="<tr style=\"cursor: pointer;\">";
            text +="<th class=\"align-middle\" scope=\"col\">"+i18n.gettext("Priority SUSAR")+"</th>";
            text +="<th class=\"align-middle\" scope=\"col\">"+i18n.gettext("AER No.")+"</th>";
            // text +="<th scope=\"col\">Documents</th>";
            text +="<th class=\"align-middle\" scope=\"col\">"+i18n.gettext("Version")+"</th>";
            text +="<th class=\"align-middle\" scope=\"col\">"+i18n.gettext("Activity")+"</th>";
            text +="<th class=\"align-middle\" scope=\"col\">"+i18n.gettext("Country")+"</th>";
            text +="<th class=\"align-middle\" scope=\"col\">"+i18n.gettext("Project No.")+"</th>";
            text +="<th class=\"align-middle\" scope=\"col\">"+i18n.gettext("Product Type")+"</th>";
            text +="<th class=\"align-middle\" scope=\"col\">"+i18n.gettext("Activity Due Date")+"</th>";
            text +="<th class=\"align-middle\" scope=\"col\">"+i18n.gettext("Submission Due Date")+"</th>";
            text +="<th class=\"align-middle\" scope=\"col\">"+i18n.gettext("Status")+"</th>";
            text +="<th class=\"align-middle\" scope=\"col\">"+i18n.gettext("Action")+"</th>";
            text +="</tr>";
            text +="</thead>";
            text +="<tbody>";
            var product_type_id=[i18n.gettext("Clinical trials"), i18n.gettext("Individual patient use"),i18n.gettext("Other studies")];
            var previous_case = "";
            var monthes = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $.each(result, function(k,caseDetail){
                var ad_type = 0;
                if(caseDetail.activity_due_date!=null){
                    var ad_time = new Date(caseDetail.activity_due_date.substring(2,4)+" "+caseDetail.activity_due_date.substring(0,2)+" "+caseDetail.activity_due_date.substring(4,8));
                    if (ad_time.getTime() < today.getTime() ) ad_type = 1; //out of date
                    else if (ad_time.getTime()+1000*60*60*24 - today.getTime() < 0) ad_type = 2; // emergency case
                    else ad_type = 3;
                }
                text += "<tr ";
                if (ad_type == 1) {
                    text += "class=\"outOfDateCase\"";
                } else if (ad_type == 2) {
                    text += "class=\"emergencyCase\""
                }
                text += ">";
                text += "<td class=\"align-middle\">";
                if(ad_type == 1) text +=" <i class=\"fas fa-flag\" data-toggle=\"tooltip\" title=\"7 Days Report\" style=\"color:red;\"></i>\n";
                else if(ad_type == 2) text +=" <i class=\"fas fa-flag\" data-toggle=\"tooltip\" title=\"15 Days Report\"style=\"color:yellow;\"></i>\n";
                if(caseDetail.serious_case.id!=null) text +=" <i class=\"fas fa-exclamation-triangle\"data-toggle=\"tooltip\" title=\"Serious Case\" style=\"color:red;\"></i>\n";
                if(caseDetail.clinical_trial.id!=null) text +=" <i class=\"fas fa-user-md\" data-toggle=\"tooltip\" title=\"Clinical Trial\" style=\"color:#845ef7;\"></i>\n";
                text +="</td>";
                text += "<td class=\"align-middle\" id=\"result-caseNo-"+k+"\">" + caseDetail.caseNo + "</td>";
                // text += "<td></td>";
                text += "<td class=\"align-middle\" id=\"version-"+caseDetail.caseNo+"\">"+ caseDetail.versions + "</td>";
                text += "<td id=\"activity-"+caseDetail.caseNo+"\" class=\"align-middle\">";
                text += i18n.gettext(caseDetail.wa.activity_name+"")
                text += "</td>";
                text += "<td class=\"align-middle\">"+caseDetail.country+"</td>";
                text += "<td class=\"align-middle\">" + caseDetail.pd.product_name + "</td>";
                text += "<td class=\"align-middle\">";
                if(caseDetail.product_type_label!=null) text += i18n.gettext(caseDetail.product_type_label+"");
                text += "</td>";
                text += "<td class=\"align-middle\">";
                if((caseDetail.activity_due_date!=null)&&(typeof caseDetail.activity_due_date !="undefined")&&(caseDetail.activity_due_date !="")){
                    var datestr = caseDetail.activity_due_date;
                    var year = datestr.substring(4,8);
                    var month = monthes[Number(datestr.substring(2,4)) - 1];
                    var day = datestr.substring(0,2);
                    text+= day+"-"+month+"-"+year;
                }
                text += "</td>";
                text += "<td class=\"align-middle\">";
                if((caseDetail.submission_due_date!=null)&&(typeof caseDetail.submission_due_date !="undefined")&&(caseDetail.submission_due_date !="")){
                    var datestr = caseDetail.submission_due_date;
                    var year = datestr.substring(4,8);
                    var month = monthes[Number(datestr.substring(2,4)) - 1];
                    var day = datestr.substring(0,2);
                    text+= day+"-"+month+"-"+year;
                }
                text +="</td>";
                text += "<td class=\"align-middle\">";
                if(caseDetail.status==0) text+=i18n.gettext("Inactive");
                else if(caseDetail.status==1) text+=i18n.gettext("Active");
                else if(caseDetail.status==2) text+=i18n.gettext("Distributed");
                else text+=i18n.gettext("closed");
                text+="</td>";
                text += "<td class=\"align-middle\">";
                if(caseDetail.sd_user_id == userId && caseDetail.status != '3') {
                    if(caseDetail.wa.activity_name=="Triage")text += "<a class=\"btn btn-outline-info m-1\" href=\"/sd-cases/triage/"+caseDetail.caseNo+"/"+caseDetail.versions+"\" role=\"button\">"+i18n.gettext("Continue Triage")+"</a>";
                    else text += "<a class=\"btn btn-outline-info m-1\" href=\"/sd-tabs/showdetails/"+caseDetail.caseNo+"/"+caseDetail.versions+"\" role=\"button\">"+i18n.gettext("Enter")+"</a>";
                }else text += "<a class=\"btn btn-info m-1\" href=\"/sd-tabs/showdetails/"+caseDetail.caseNo+"/"+caseDetail.versions+"\" role=\"button\">"+i18n.gettext("Check Detail")+"</a>";
                if((caseDetail.status!='1')&&(previous_case!=caseDetail.caseNo))
                    text += "<button class=\"btn btn-warning m-1\" data-toggle=\"modal\" data-target=\".versionUpFrame\" onclick=\"versionUp(\'"+caseDetail.caseNo+"\')\">"+i18n.gettext("Version Up")+"</button>";
                else if(caseDetail.status != '3')
                    text += "<button class=\"btn btn-warning m-1\" data-toggle=\"modal\" data-target=\".versionUpFrame\" onclick=\"closeCase(\'"+caseDetail.caseNo+"\')\">"+i18n.gettext("Close Case")+"</button>";
                text +="</td>";
                text += "</tr>";
                previous_case = caseDetail.caseNo;
            })
            text +="</tbody>";
            text +="</table>";
            $("#textHint").html(text);
            $('#caseTable').DataTable();
        },
        complete: function () {
            $('.loadingSpinner').hide();
            $('a').click(function (){
                $(this).append(` <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`);
            });

        },
        error:function(response){
                console.log(response.responseText);
            $("#textHint").html(i18n.gettext("Sorry, no case matches"));
        }
    });
}
function versionUp(caseNo){
    var versionNo = $('#version-'+caseNo).text();
    var request={
        "caseNo":caseNo,
        "version_no":versionNo,
        "userId":userId
    };
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/versionUp',
        data:request,
        success:function(response){
            console.log(response);
            window.location.href = "/sd-cases/triage/"+caseNo+'/'+Number(Number(versionNo)+1);
        },
        error:function(response){
            console.log(response.responseText);
        }
    });
}

function closeCase(caseNo){
    var versionNo = $('#version-'+caseNo).text();
    var request={
        "caseNo":caseNo,
        "version_no":versionNo,
        "userId":userId
    };
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/closeCase',
        data:request,
        success:function(response){
            console.log(response);
            location.reload();
        },
        error:function(response){
            console.log(response.responseText);
            location.reload();
        }
    });
}

jQuery(document).ready(function($) {
    $(".queryBoxTable").click(function() {
        window.location = $(this).data("href");
    });

    // Session timeout notice
    setTimeout(function(){
        swal(
            {
                icon:'warning',
                title:'Session Expired',
                text:'Please login for further operations',
                closeOnClickOutside: false,
                closeOnEsc: false,
            }
        )
        .then(() => {
            location.href = '/sd-users/logout';
          });
    },60*60*1000);
});
