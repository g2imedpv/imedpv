// For popover effect on comments
var $popover = jQuery.noConflict(); // This line is required if call more than 1 jQuery function from library
$popover(document).ready(function(){
    $popover('[data-toggle="popover"]').popover({
        html: true,
        trigger: 'hover focus',
        delay: { show: 100, hide: 500 }
    });

    $('#addNewWL').click(function() {
        $(this).hide();
        $('#workflowlist').slideUp();
        $('#choworkflow').slideDown();
        // swal({
        //     title: "You are adding a New Workflow",
        //     icon: "success",
        //   })
    });

    $('#confirmWFlist').click(function() {
        $('#addNewWL').show();
        $('#choworkflow, #choosecro').slideUp();
        $('#workflowlist').slideDown();
        swal({
            title: "Your New Workflow has been SET",
            icon: "success",
          })
    });

});


function selectCro(id){
    $('[id^=conass]').attr('id', 'conass-'+id);
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
    // Add CRO, triggered by "Add" button for adding CRO button and CRO resource list
    $('#croadd').click(function() {
        var cro_name = $('#croname option:selected').text();
        var cro_id = $("#croname").val();
        // var newcro = $('<button type="button"class="btn btn-outline-primary"  onclick="selectCro(' + cro_id + ')" data-toggle="modal" data-target=".bd-example-modal-lg">' + cro_name + '</button>');
        $('#crotable').append('<tr id="cro_id_list-'+cro_id+' "><th id = "crocompany-'+cro_id+'">' + cro_name + '</th><td id = "cromanager-'+cro_id+'"></td><td id = "crostaff-'+cro_id+'"></td><td><button class="btn btn-sm btn-outline-info" onclick="selectCro(' + cro_id + ')" data-toggle="modal" data-target="#addper">Edit</button><button class="btn btn-sm btn-danger ml-3" id="removeCRO-' + cro_id + '" onclick="removeCro(' + cro_id + ')">Delete</button></td></tr>');
        // $('#addcroarea').append(newcro);
     });

    // Select workflow manager and staff to CRO
    $('[id^=conass]').click(function() {
        var cro_id = $(this).attr('id').split('-')[1];
        var textmanager  = "";
        var textstaff = "";
        var managerChosed = $(".stackDrop1 > .personnel").text().match(/[A-Z][a-z]+/g);
        var staffChosed = $(".stackDrop2 > .personnel").text().match(/[A-Z][a-z]+/g);
        $.each(managerChosed, function(k,manager){
            textmanager += manager + "; ";
        });
        $("#cromanager-"+cro_id).html(textmanager);
        $.each(staffChosed, function(k,staff){
            textstaff += staff + "; ";
        });
        $("#crostaff-"+cro_id).html(textstaff);

    });

    // Disable selected option if chosed
    $(document).ready(function(){
        $("#croadd").click(function(){
            $("#croname option:selected").remove();
        });
    });


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

// Dashboard popup Advance Search
jQuery(function($) {
    $(document).ready(function(){
        $("#advsearch").click(function(){
            $(this).parent().hide();
            $("#advsearchfield").slideDown();
        });
    });

// Defaultworkflow and Custworkflow button control

    $('#defbtn').click(function() {
        $('#submitworkflow').show();
        $('.defworkflow').slideDown();
        $('.custworkflow').slideUp();
    });

    $('#custbtn').click(function() {
        $('#submitworkflow').show();
        $('.custworkflow').slideDown();
        $('.defworkflow').slideUp();
    });

    // Close customworkflow step
    $('.closewf').click(function() {
        $(this).closest('li').remove();
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
            },
        });
        $( "#draggable" ).draggable({
            connectToSortable: "#sortable",
            cursor: "pointer",
            helper: "clone",
            opacity: 0.6,
            revert: "invalid",
            start  : function(event, ui){
                $(ui.helper).addClass("w-100 h-75");
                $(this).find('h4').replaceWith('<h4><input type="text" placeholder="Type step name here FIRST" class="font-weight-bold" /></h4>');
            },
            // Add "close icon" when drag into new place
            create :  function (event, ui) {
                $(this).find('.card-body').prepend( '<button class="close closewf">' +  '&times;' +  '</button>');
                $(this).change(function() {
                    $(this).find('h4 > input').replaceWith('<h5>' + $('#draggable').find('input').val() + '</h5>');
                });
            },
            // Remove all inputs in original when drag into new place
            stop : function (event,ui) {
                $(this).find('input, textarea').val('');
            }
        });


        // CRO Droppable Area
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

});


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

// TO DO: make nav button has "active" effect
    $(document).ready(function($) {
        $("#navbarSupportedContent > ul > li").click(function() {
            $(this).removeClass('active');
            $(this).addClass('active');
        });
    });

// Add Product card
    $(document).ready(function($){
        $('#addprobtn').click(function() {
            var request = {'product_name': $("#product_name").val(), 'study_no':$("#study_no").val(),
                    'sd_sponsor_company_id':$("#sd_sponsor_company_id").val(),
                    'sd_product_type_id':$("#sd_product_type_id").val(),
                    'status':$("#status").val()
                };
            console.log(request);

            $.ajax({
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                type:'POST',
                url:'/sd-products/create',
                data:request,
                success:function(response){
                    console.log(response);
                    var result = $.parseJSON(response);
                    console.log(result);
                    if (result.result == 1)
                    {
                        $('#product_id').val(result.product_id);
                        $('#choosecon').show();
                        $("#addpro > div > input, #addpro > div > select").prop("disabled", true);
                    }
                    else{
                        swal({
                            title: "Failed to add a new product",
                            text: "All the Fields are REQUIRED",
                            icon: "warning",
                            button: "OK",
                          });
                        // $("#errorMsg").show();
                        // $("#errorMsg").html("Failed to add a new product!");
                    }

                },
                error:function(response){
                        console.log(response);
                }
            });


        });
        $('#submitchocountry').click(function() {
            $(this).hide();
            $('#undochocon').show();
            $("#country, #callCen").prop("disabled", true);
            $('#choosewf').show();
        });
        $('#undochocon').click(function() {
            $(this).hide();
            $('#choosewf, #choosecro').hide();
            $('#submitchocountry, #submitworkflow').show();
            $("#country, #callCen").prop("disabled", false);
        });
        $('#submitworkflow').click(function() {
            var workflowname;
            //Make sure which workflow is selected
            if ($('.defworkflow').is(':visible') && $('.custworkflow').is(':hidden'))
            {
                $(this).hide();
                $('#cusworkflow, #defbtn').hide();
                $('#defT, #undochoWF').show();
                $('#ifdef').addClass("mx-auto w-50");
                $('#undochoWF').click(function() {
                    $(this).hide();
                    $('#defT').hide();
                    $('#submitworkflow, #cusworkflow, #defbtn').show();
                    $('#ifdef').removeClass("mx-auto w-50");
                })
                // alert("default workflow selected");
                swal({
                    title: "Default Workflow SET",
                    icon: "success",
                    button: "OK",
                  });
                workflowname = "Default Workflow";
                var wkflsteps = iterateWorkflow("defworkflow");
            }
            if (($('.defworkflow').is(':hidden') && $('.custworkflow').is(':visible')))
            {
                $(this).hide();
                $('#defworkflow, #custbtn, .closewf').hide();
                $('#cusT, #undochoWF').show();
                $('#sortable, #draggable').addClass("mx-auto w-50");
                $('#cusworkflow').find('ul').hide();
                $('#custworkflowname').attr('disabled',true);
                $('li.custworkflowstep').find('button').hide();
                // TO DO: After confirm the custworkflow, the list shouldn`t be draggable or sortable
                    // $("#sortable").sortable({ disabled: true });
                    // $( "#draggable" ).draggable("destroy");
                $('#undochoWF').click(function() {
                    $(this).hide();
                    $('#sortable').find('.input-group').remove();
                    $('#cusT, .closewf').hide();
                    $('#submitworkflow,#defworkflow, #custbtn').show();
                    $('#sortable, #draggable').removeClass("mx-auto w-50");
                    $('#custworkflowname').attr('disabled',false);
                    $('li.custworkflowstep').find('button').show();
                    $('#cusworkflow').find('ul').show();
                })
                //alert("custworkflow selected");
                workflowname = $('#custworkflowname').val();
                swal({
                    title: "Customization Workflow Selected",
                    text: "The Workflow Name is: " + workflowname,
                    icon: "success",
                    button: "OK",
                  });
                if(workflowname.length == 0){
                    $(this).show();
                    $('#custworkflowname').attr('disabled',false);
                    $('li.custworkflowstep').find('button').show();
                    $('#cusworkflow').find('ul').show();
                    // $('#custworkflowname').after('<div id="errWorkflow" class="alert alert-danger" role="alert">Workflow name is required!</div>');
                    $('#custworkflowname').after($("#errWorkflow").show());
                    swal({
                        title: "Failed to choose Workflow",
                        text: "Workflow name is REQUIRED",
                        icon: "warning",
                        button: "OK",
                      });
                    return false;
                }
                else {
                    $(this).hide();
                $('#sortable').find('.card-body').append( '<div class="input-group w-25 mx-auto"><i class="fas fa-arrow-up gobackstep"></i><input type="text" class="form-control form-control-sm" aria-label="Back Steps" aria-describedby="backSteps"><div class="input-group-append"><button class="btn btn-sm btn-outline-info" type="button" id="backSteps">OK</button></div></div>');
                    $('#custworkflowname').next('#errWorkflow').remove(); // *** this line have been added ***
                    var wkflsteps = iterateWorkflow("cust");
                }
            }
            console.log(wkflsteps);
            var request = {'product_id': $("#product_id").val(),'country':$("#country").val(),
                    'workflow_name':workflowname,
                    'workflow_steps':wkflsteps
            };
            console.log(request);
            //add into product workflow
            $.ajax({
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                type:'POST',
                url:'/sd-workflow-activities/create',
                data:request,
                success:function(response){
                    console.log(response);
                    var result = $.parseJSON(response);
                    console.log(result);
                    if (result.result == 1)
                    {
                        $('#workflow_id').val(result.sd_workflow_id);
                        $('#choosecon').show();
                    }
                    else{
                        swal({
                            title: "Failed to add the workflow",
                            text: "All the Fields are REQUIRED",
                            icon: "warning",
                            button: "OK",
                          });
                        // $("#errorMsg").show();
                        // $("#errorMsg").html("Failed to add the workflow!");
                    }

                },
                error:function(response){
                        console.log(response);
                }
            });

            $('#choosecro').show();
        });
    });

    function iterateWorkflow(wkfl_name)
    {
        var steps = [];
        var listItems = $("."+wkfl_name+" li");
        //console.log(listItems);
        listItems.each(function(idx, li) {
            var display_order = idx+1;
            var step_name = $(li).find("h5").text().replace(/ /g,'');
            steps.push({
                display_order: display_order,
                step_name: step_name
            });
            // console.log(display_order);
            // console.log(step_name);

        })
        //console.log(steps);
        return steps;
    }


});



function level2setPageChange(section_id, pageNo, addFlag=null){
    var child_section =  $("[id^=child_section][id$=section-"+section_id+"]").attr('id').split('-');
    child_section_id = child_section[1].split(/[\[\]]/);
    child_section_id = jQuery.grep(child_section_id, function(n, i){
        return (n !== "" && n != null);
    });
    var max_set_no  = 0 ;
    $(child_section_id).each(function(k, v){
        var sectionKey = $("[id^=add_set-"+v+"]").attr('id').split('-')[3];
        $(section[sectionKey].sd_section_structures).each(function(k,v){
            $.each(v.sd_field.sd_field_values,function(key, value){console.log("v:");console.log(v);console.log(value);console.log(value.set_number);
                max_set_no = Math.max(value.set_number, max_set_no);
            })
        })
    });
    if ((pageNo <= 0)||(pageNo>max_set_no)) {console.log("set_no not avaiable");console.log(max_set_no); return;};
     if(addFlag==1)
    {
        pageNo = max_set_no+1;
        $("[id^=child_section][id$=section-"+section_id+"]").hide();
    }else{$("[id^=child_section][id$=section-"+section_id+"]").show()}
    $(child_section_id).each(function(k,v){
        setPageChange(v, pageNo, addFlag, 1);
    });

    $("[id^=left_set-"+section_id+"]").attr('id', 'left_set-'+section_id+'-setNo-'+pageNo);
    $("[id^=left_set-"+section_id+"]").attr('onclick','level2setPageChange('+section_id+','+Number(Number(pageNo)-1)+')');
    $("[id^=right_set-"+section_id+"]").attr('id', 'right_set-'+section_id+'-setNo-'+pageNo);
    $("[id^=right_set-"+section_id+"]").attr('onclick','level2setPageChange('+section_id+','+Number(Number(pageNo)+1)+')');
    $("[id=section-"+section_id+"-page_number-"+child_section[5]+"]").css('font-weight', 'normal');
    $("[id=section-"+section_id+"-page_number-"+pageNo+"]").css('font-weight', 'bold');
    $("[id^=child_section][id$=section-"+section_id+"]").attr('id',child_section[0]+'-'+child_section[1]+'-'+child_section[2]+'-'+child_section[3]+'-'+child_section[4]+'-'+pageNo+'-'+child_section[6]+'-'+child_section[7]);
}

function setPageChange(section_id, pageNo, addFlag=null, pFlag) {
    $("[id^=save-btn"+section_id+"]").hide();
    if($("[id^=right_set-"+section_id+"]").length){

        var sectionIdOriginal =  $("[id^=right_set-"+section_id+"]").attr('id');
        var sectionId = sectionIdOriginal.split('-');
        var setNo = sectionId[5];

        var max_set_no = 0;
        $("[id^=section-"+sectionId[1]+"-page_number").each(function() {
            max_set_no = Math.max(Number($(this).attr('id').split('-')[3]), max_set_no);
        });
        // if ((Number(setNo)+Number(steps) <= 0)||(Number(setNo)+Number(steps)>max_set_no)) {console.log("set_no not avaiable"); return;};

        if ((((pageNo <= 0)&&(addFlag!=1))||pageNo>max_set_no)&&pFlag!=1) {console.log("set_no not avaiable"); return;};
        if(addFlag == 1){
            $("[id=addbtnalert-"+sectionId[1]+"]").show();
        }else{
            $("[id=addbtnalert-"+sectionId[1]+"]").hide()
        }
        if((addFlag == 1)&&(pFlag!=1)) {
            pageNo = max_set_no+1;
            $("[id^=add_set-"+sectionId[1]+"]").hide();
            }else {$("[id^=add_set-"+sectionId[1]+"]").show();}
        $("[id^=section-"+sectionId[1]+"][name$=\\[id\\]]").each(function(){
            var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
            var valueFlag = false;
            var thisElement = $(this);
            var idholder = thisElement.attr('id').split('-');
            var maxindex=0;
            if (section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){
                $.each(section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                    if ((typeof value != 'undefined')&&value.set_number== pageNo){
                        thisElement.val(value.id);
                        thisElement.attr('id',idholder[0]+'-'+idholder[1]+'-'+idholder[2]+'-'+idholder[3]+'-'+idholder[4]+'-'+index+'-'+idholder[6]);
                        valueFlag = true;
                    }
                    maxindex = maxindex+1;
                });
            }
            if(valueFlag == false) {
                $(this).val(null);
                var idholder = thisElement.attr('id').split('-');
                thisElement.attr('id',idholder[0]+'-'+idholder[1]+'-'+idholder[2]+'-'+idholder[3]+'-'+idholder[4]+'-'+maxindex+'-'+idholder[6]);
            };
        });
        $("[id^=section-"+sectionId[1]+"][name$=\\[set_number\\]]").each(function(){
            var newSetNumber = pageNo;
            $(this).val(newSetNumber);
        });
        $("[id^=section-"+sectionId[1]+"][name$=\\[field_value\\]]").each(function(){
            var sectionStructureK = $(this).attr('name').split(/[\[\]]/)[3];
            var thisId = $(this).attr('id').split('-');
            var valueFlag = false;
            var thisElement = $(this);
            if (section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values.length>=1){
                $.each(section[sectionId[3]].sd_section_structures[sectionStructureK].sd_field.sd_field_values, function(index, value){
                    if ((typeof value != 'undefined')&&(value.set_number== pageNo)){
                        if(thisElement.attr('id').split('-')[2] == 'date'||thisElement.attr('id').split('-')[2] == 'select'||thisElement.attr('id').split('-')[2] == 'whodraname'||thisElement.attr('id').split('-')[2] == 'whodracode'||thisElement.attr('id').split('-')[2] == 'text'||thisElement.attr('id').split('-')[2] == 'textarea'){
                            thisElement.val(value.field_value);
                            valueFlag = true;
                        }else{
                            if(thisElement.attr('id').split('-')[2]=='radio'){
                                if(thisElement.val()==value.field_value) {
                                    thisElement.prop('checked',true);
                                    valueFlag = true;
                                }else thisElement.prop('checked',false);
                            }if(thisElement.attr('id').split('-')[2]=='checkbox'){
                                valueFlag = true;
                                if(value.field_value.charAt(Number(thisElement.val())-1) == 1){
                                    thisElement.prop('checked',true);
                                }else thisElement.prop('checked',false);
                                if((typeof thisId[5] != 'undefined')&&(thisId[5]=="final")) {thisElement.val(value.field_value); }
                            }
                        }
                    }
                });
            }
            if(valueFlag == false) {
                if(thisElement.attr('id').split('-')[2] == 'select'||thisElement.attr('id').split('-')[2] == 'text'||thisElement.attr('id').split('-')[2] == 'textarea'){
                    thisElement.val(null);
                }else{
                    thisElement.prop('checked',false);
                    if((typeof thisId[5] != 'undefined')&&(thisId[5]=="final")) {
                        val = "";
                        for (i = 0; i < thisId[4]; i++){
                            val = val+"0";
                        }
                        thisElement.val(val);
                    }
                }
            };
        });
        $("[id^=left_set-"+sectionId[1]+"]").attr('id', 'left_set-'+sectionId[1]+'-'+sectionId[2]+'-'+sectionId[3]+'-'+sectionId[4]+'-'+pageNo);
        $("[id^=left_set-"+sectionId[1]+"]").attr('onclick','setPageChange('+sectionId[1]+','+Number(Number(pageNo)-1)+')');
        $("[id^=right_set-"+sectionId[1]+"]").attr('id', 'right_set-'+sectionId[1]+'-'+sectionId[2]+'-'+sectionId[3]+'-'+sectionId[4]+'-'+pageNo);
        $("[id^=right_set-"+sectionId[1]+"]").attr('onclick','setPageChange('+sectionId[1]+','+Number(Number(pageNo)+1)+')');
        $("[id=section-"+sectionId[1]+"-page_number-"+sectionId[5]+"]").css('font-weight', 'normal');
        $("[id=section-"+sectionId[1]+"-page_number-"+pageNo+"]").css('font-weight', 'bold');
    }
};

function onQueryClicked(){
    var request = {'searchName': $("#searchName").val(), 'searchProductName':$("#searchProductName").val()};
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/dashboards/search',
        data:request,
        success:function(response){
            console.log(response);
            var result = $.parseJSON(response);
            var text = "";
            text +="<h3>Search Results</h3>";
            text +="<table class=\"table table-hover\">";

            text += "<thead>";
            text +="<tr class=\"table-secondary\">";
            text +="<th scope=\"col\">AER No.</th>";
            text +="<th scope=\"col\">Documents</th>";
            text +="<th scope=\"col\">Version</th>";
            text +="<th scope=\"col\">Activity</th>";
            text +="<th scope=\"col\">Country</th>";
            text +="<th scope=\"col\">Project No.</th>";
            text +="<th scope=\"col\">Product Type</th>";
            text +="<th scope=\"col\">Activity Due Date</th>";
            text +="<th scope=\"col\">Submission Due Date</th>";
            text +="<th scope=\"col\">Action</th>";
            text +="</tr>";
            text +="</thead>";
            text +="<tbody>";
            $.each(result, function(k,caseDetail){
                text += "<tr>";
                text += "<td>" + caseDetail.caseNo + "</td>";
                text += "<td></td>";
                text += "<td></td>";
                text += "<td>" + caseDetail.start_date + "</td>";
                text += "<td></td>";
                text += "<td>" + caseDetail.sd_product_workflow.sd_product.study_no + "<td>";
                text += "<td></td>";
                text += "<td>" + caseDetail.end_date + "</td>";
                text += "<td><a class=\"btn btn-outline-info\" href=\"/sd-tabs/showdetails/1?caseId="+caseDetail.id+"\">Data Entry</a> <a class=\"btn btn-outline-info\" href=\"#\">More</a></td>";
                text += "</tr>";
            })
            text +="</tbody>";
            text +="</table>";
            $("#textHint").html(text);
        },
        error:function(response){
                console.log(response.responseText);

            $("#textHint").html("Sorry, no case matches");

        }
    });
}