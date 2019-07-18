$popover('[data-toggle="popover"]').popover({
    html: true,
    trigger: 'hover focus',
    delay: { show: 100, hide: 500 }
});

$(document).ready(function($){
    // Workflow Resouces
    $(".wfres").draggable({
        appendTo: "body",
        cursor: "grab",
        helper: "clone",
        revert: "invalid",
        snapTolerance: 30,
        stop: function(event, ui) {
            $('.resourceDropzone').find(".wfres").addClass("pr-5").prepend('<button class="close closewa" onclick=\"$(this).parent().remove();\">' +  '&times;' +  '</button>');
        }
    });
    // Workflow Resouces Container
    $("#workflowResources").droppable({
        tolerance: "intersect",
        accept: ".wfres",
        activeClass: "ui-state-default",
        hoverClass: "ui-state-hover",
        drop: function(event, ui) {
            $(this).append($(ui.draggable));
        }
    });

    $(".resourceDropzone").droppable({
        tolerance: "intersect",
        accept: ".wfres",
        activeClass: "ui-state-default",
        hoverClass: "ui-state-hover",
        drop: function(event, ui) {
            $(this).append($(ui.draggable).clone());
            var workactivityID = ui.draggable.attr("id");
            console.log(workactivityID);
            // if ($(workactivityID).length > 0) {
            //     //$(this).droppable( "disable" );
            // }
        }
    });

});
function confirmAllocation(){
    var request ={};
    var text = "";
    $('[id^=activity_card]').each(function(){
        if($(this).find('[id^=personal]').length>0){
            var counter = 0;
            var activity_div = $(this);
            var activity_set = {};
            $(this).find('[id^=personal]').each(function()
            {
                activity_set[counter] = $(this).attr('id').split('-')[1]
                counter ++;
            });
            request[activity_div.attr('id').split('-')[1]] = activity_set;
        }
    });
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/sd-user-assignments/allocateTeam/'+workflowId,
        data:request,
        success:function(response){
            // console.log(response);
            window.location.href = "/sd-products/search";
        },
        error:function(response){
            console.log(response.responseText);
        }
    });
}
