// For popover effect on comments
var $popover = jQuery.noConflict(); // This line is required if call more than 1 jQuery function from library
$popover(document).ready(function(){
    $popover('[data-toggle="popover"]').popover({
        html: true,
        trigger: 'hover focus',
        delay: { show: 100, hide: 500 }
    });
});

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

// jQuery(function($) {
//     $(document).ready(function(){
//         // Dashboard popup Advance Search
//         $("#advsearch").click(function(){
//             if($("#advsearchfield").is(':hidden'))
//                 $("#advsearchfield").slideDown();
//                 else $("#advsearchfield").slideUp();
//         });

//     });
// });


// Control the topNav and leftNav running with the scroll
    $(window).scroll(function() {
        if ($(window).scrollTop() > 176) {
            $('#topbar').addClass('topbarchange');
            $('#sidenav').addClass('sidenavchange');
            $('.dataentry').addClass('dataentrychange');
        }
        else {
            $('#topbar').removeClass('topbarchange');
            $('#sidenav').removeClass('sidenavchange');
            $('.dataentry').removeClass('dataentrychange');
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

// Make nav button has "active" effect
    $(function(){
        // If clicked the first level menu
        $('#navbarSupportedContent > ul > li > a').each(function(){
            if (
                $(this).prop('href') == window.location.href) {
                    $(this).addClass('active');
            }
        });
        // If clicked the second (submenu) level
        $('#navbarSupportedContent > ul > li > div > a').each(function(){
            if (
                ($(this).prop('href').split('/').slice(3,4)).toString() == ((window.location.pathname).split('/').slice(1,2)).toString() ) {
                    $(this).parent().siblings('a').addClass('active');
            }
        });
    });

// Make Query Box left nav button has "active" effect
$(function(){
    // If clicked the first level menu
    $('ul.queryBoxLeft > a').each(function(){
        if (
            $(this).prop('href').split('/').slice(5,6).toString() == (window.location.href).split('/').slice(5,6).toString()) {
                $(this).addClass('queryBoxActive');
        }
    });
});

});

function onQueryClicked(preferrenceId = null){
    var request = {'searchName': $("#searchName").val(), 'searchProductName':$("#searchProductName").val(),'userId':userId};
    if (preferrenceId!=null)
    request['preferrenceId'] = preferrenceId;
    var today = new Date();
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/search',
        data:request,
        success:function(response){
            console.log(response);
            if (response==false) {
                $("#textHint").html("Sorry, no case matches");
                return}
            var result = $.parseJSON(response);
            var text = "";
            text +="<table id=\"caseTable\" class=\"table table-striped table-bordered table-hover\">";
            text += "<thead>";
            text +="<tr style=\"cursor: pointer;\">";
            text +="<th class=\"align-middle\" scope=\"col\">Priority SUSAR</th>";
            text +="<th class=\"align-middle\" scope=\"col\">AER No.</th>";
            // text +="<th scope=\"col\">Documents</th>";
            text +="<th class=\"align-middle\" scope=\"col\">Version</th>";
            text +="<th class=\"align-middle\" scope=\"col\">Activity</th>";
            text +="<th class=\"align-middle\" scope=\"col\">Country</th>";
            text +="<th class=\"align-middle\" scope=\"col\">Project No.</th>";
            text +="<th class=\"align-middle\" scope=\"col\">Product Type</th>";
            text +="<th class=\"align-middle\" scope=\"col\">Activity Due Date</th>";
            text +="<th class=\"align-middle\" scope=\"col\">Submission Due Date</th>";
            text +="<th class=\"align-middle\" scope=\"col\">Action</th>";
            text +="</tr>";
            text +="</thead>";
            text +="<tbody>";
            var product_type_id=["clinical trials", "individual patient use","other studies"];
            var previous_case = "";
            $.each(result, function(k,caseDetail){
                var ad_time = new Date(caseDetail.activity_due_date);
                text += "<tr>";
                text += "<td class=\"align-middle\">";
                if((caseDetail.activity_due_date!=null)&&(ad_time.getTime()+1000*60*60*24 - today.getTime() < 0)) text +=" <i class=\"fas fa-flag\" style=\"color:red;\"></i>\n";
                else if((caseDetail.activity_due_date!=null)&&(ad_time.getTime() - today.getTime() < 0)) text +=" <i class=\"fas fa-flag\" style=\"color:yellow;\"></i>\n";
                if(caseDetail.serious_case.id!=null) text +=" <i class=\"fas fa-exclamation-triangle\" style=\"color:red;\"></i>\n";
                if(caseDetail.clinical_trial.id!=null) text +=" <i class=\"fas fa-user-md\" style=\"color:#845ef7;\"></i>\n";
                text +="</td>";
                text += "<td class=\"align-middle\">" + caseDetail.caseNo + "</td>";
                // text += "<td></td>";
                text += "<td class=\"align-middle\">"+ caseDetail.versions + "</td>";
                text += "<td id=\"activity-"+caseDetail.caseNo+"\" class=\"align-middle\">";
                if(caseDetail.sd_workflow_activity_id!='9999') text += caseDetail.wa.activity_name;
                else text += "Finished Data Accessment"
                text += "</td>";
                text += "<td></td>";
                text += "<td class=\"align-middle\">" + caseDetail.pd.product_name + "</td>";
                text += "<td class=\"align-middle\">";
                if(caseDetail.product_type_label!=null) text += caseDetail.product_type_label;
                text += "</td>";
                text += "<td class=\"align-middle\">"+caseDetail.activity_due_date+"</td>";
                text += "<td class=\"align-middle\">" + caseDetail.submission_due_date + "</td>";
                text += "<td class=\"align-middle\">";
                if(caseDetail.sd_user_id == userId)
                    text += "<a href=\"/sd-tabs/showdetails/"+caseDetail.caseNo+"/"+caseDetail.versions+"\"><div class=\"btn btn-outline-info m-1\">Enter</div></a>";
                else text += "<a href=\"/sd-tabs/showdetails/"+caseDetail.caseNo+"/"+caseDetail.versions+"\"><div class=\"btn btn-info m-1\">Check Detail</div></a>";
                if((caseDetail.sd_workflow_activity_id=='9999')&&(previous_case!=caseDetail.caseNo))
                    text += "<div class=\"btn btn-warning m-1\" data-toggle=\"modal\" data-target=\".versionUpFrame\" onclick=\"versionUp(\'"+caseDetail.caseNo+"\')\">Version Up</div>";
                text +="</td>";
                text += "</tr>";
                previous_case = caseDetail.caseNo;
            })
            text +="</tbody>";
            text +="</table>";
            $("#textHint").html(text);
            $('#caseTable').DataTable();
        },
        error:function(response){
                console.log(response.responseText);
            $("#textHint").html("Sorry, no case matches");
        }
    });
}
function versionUp(caseNo){
    $('#confirmVersionUp').attr("onclick","confirmVersionUp(\'"+caseNo+"\')");
}
function confirmVersionUp(caseNo){
    var request={
        "caseNo":caseNo,
        "version_no":$('#version-'+caseNo).val()
    };
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/versionUp',
        data:request,
        success:function(response){
            console.log(response);
        },
        error:function(response){
            console.log(response.responseText);
        }
    });
}
function closeCase(caseNo){
    var request={
        "caseNo":caseNo,
        "version_no":$('#version-'+caseNo).val()
    };
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-cases/closeCase',
        data:request,
        success:function(response){
            console.log(response);
        },
        error:function(response){
            console.log(response.responseText);
        }
    });
}

jQuery(document).ready(function($) {
    $(".queryBoxTable").click(function() {
        window.location = $(this).data("href");
    });
});