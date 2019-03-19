<button type="button" class="btn btn-info btn-sm mx-3" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="fas fa-search"></i> MedDRA Browser</button>

<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 1175px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">MedDRA Browser (Version: MedDRA 18.1)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <!-- Search field -->
            <div class="container">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Search LLT Term</label>
                        <input type="text" class="form-control" id="llt_term"  placeholder="Search LLT Term">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Search PT Term</label>
                        <input type="text" class="form-control" id="pt_term"  placeholder="Search PT Term">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Search by Key Word</label>
                        <input type="text" class="form-control" id="wildcard_search"  placeholder="Search by Key Word">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Search HLT Term</label>
                        <input type="text" class="form-control" id="hlt_term" placeholder="Search HLT Term">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Search HLGT Term</label>
                        <input type="text" class="form-control" id="hlgt_term" placeholder="Search HLGT Term">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Search SOC Term</label>
                        <input type="text" class="form-control" id="soc_term"  placeholder="Search SOC Term">
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <div class="form-group col-sm-3">
                        <div id="meddrasea" onclick="searchMedDra(<?= $fieldId ?>)" class="form-control btn btn-primary w-100"><i class="fas fa-search"></i> Search</div>
                    </div>
                    <div class="form-group col-sm-1">
                        <div class="clearsearch form-control btn btn-outline-danger w-100"><i class="fas fa-eraser"></i> Clear</div>
                    </div>
                </div>
            </div>

            <!-- Table field (Should be hidden before search) -->
            <div id="meddra_result"></div>

            <h4 class="text-center">MedDra Details</h4> <hr>
            <div class="container">
                <fieldset disabled>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">LLT Name</label>
                            <input type="text" class="form-control" id="select-llt-name">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">LLT Code</label>
                            <input type="text" class="form-control" id="select-llt-code">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">PT Name</label>
                            <input type="text" class="form-control" id="select-pt-name">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">PT Code</label>
                            <input type="text" class="form-control" id="select-pt-code">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">HLT Name</label>
                            <input type="text" class="form-control" id="select-hlt-name">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">HLT Code</label>
                            <input type="text" class="form-control" id="select-hlt-code">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">HLGT Name</label>
                            <input type="text" class="form-control" id="select-hlgt-name">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">HLGT Code</label>
                            <input type="text" class="form-control" id="select-hlgt-code">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">SOC Name</label>
                            <input type="text" class="form-control" id="select-soc-name">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">SOC Code</label>
                            <input type="text" class="form-control" id="select-soc-code">
                        </div>
                    </div>
                </fieldset>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success w-25 mx-auto"  onclick="selectMeddraButton(<?php echo $fieldId?>)" data-dismiss="modal">Select</button>
      </div>
    </div>
  </div>
</div>


<script>
    function searchMedDra(fieldId) {
    var request = {
        'wildcard_search':$('#wildcard_search').val(),
        'llt_term': $("#llt_term").val(),
        'pt_term':$("#pt_term").val(),
        'hlt_term':$('#hlt_term').val(),
        'hlgt_term':$('#hlgt_term').val(),
        'soc_term':$('#soc_term').val(),
    };
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/med-dra/search',
        data:request,
        success:function(response){        console.log(response);
        var result = $.parseJSON(response);
        var text = "";
        text +="<h4 class=\"text-center\">Search Results</h4>";
        text +="<table class=\"table table-hover table-striped\" id=\"meddra_table\">";

        text += "<thead>";
        text +="<tr class=\"table-secondary\">";
        text +="<th scope=\"col\">Primary SOC Term</th>";
        text +="<th scope=\"col\">SOC Term</th>";
        text +="<th scope=\"col\">HLGT Term</th>";
        text +="<th scope=\"col\">HLT Term</th>";
        text +="<th scope=\"col\">PT Term</th>";
        text +="<th scope=\"col\">LLT Term</th>";
        text +="</tr>";
        text +="</thead>";
        text +="<tbody>";
        $.each(result, function(k,v){
            text += "<tr onclick=\"selectMedDra("+k+")\">";
            text += "<td>" + v[10] + "</td>";
            text += "<td>" + v[9] + "</td>";
            text += "<td>" + v[6] + "</td>";
            text += "<td>" + v[4] + "</td>";
            text += "<td>" + v[2] + "</td>";
            text += "<td>" + v[0] + "</td>";
            text += "<div id=\"meddra_detail"+k+"\" hidden>"+JSON.stringify(v)+"</div> ";
            text += "</tr>";
        })
        text +="</tbody>";
        text +="</table>";
        $("#meddra_result").html(text);
        $('#meddra_table').DataTable();
        },
        error:function(response){
                console.log(response.responseText);

            $("#textHint").html("Sorry, no case matches");

        }
    });
    }
function selectMeddraButton(fieldId){
    console.log($('#whodrug-code'+fieldId));
    $('[id*=meddralltname]').val($('#select-llt-name').val());
    $('[id*=reactionmeddrallt]').val($('#select-llt-code').val());
    $('[id*=patientdeathreport').val($('#select-llt-code').val());
    $('[id*=meddralltcode]').val($('#select-llt-code').val());
    $('[id*=meddraptname]').val($('#select-pt-name').val());
    $('[id*=meddraptcode]').val($('#select-pt-code').val());
    $('[id*=reactionmeddrapt]').val($('#select-hlt-code').val());
    $('[id*=meddrahltname]').val($('#select-hlt-name').val());
    $('[id*=meddrahltcode]').val($('#select-hlt-code').val());
    $('[id*=meddrahlgtname]').val($('#select-hlgt-name').val());
    $('[id*=meddrahlgtcode]').val($('#select-hlgt-code').val());
    $('[id*=meddrasocname]').val($('#select-soc-name').val());
    $('[id*=meddrasoccode]').val($('#select-soc-code').val());
    $('[id*=meddraversion]').val('18.1');
}
function selectMedDra(key){
    var meddra_detail = $('#meddra_detail'+key).html();
    var result = $.parseJSON(meddra_detail);
    $('#select-llt-name').val(result[0]);
    $('#select-llt-code').val(result[1]);
    $('#select-pt-name').val(result[2]);
    $('#select-pt-code').val(result[3]);
    $('#select-hlt-name').val(result[4]);
    $('#select-hlt-code').val(result[5]);
    $('#select-hlgt-name').val(result[6]);
    $('#select-hlgt-code').val(result[7]);
    $('#select-soc-name').val(result[9]);
    $('#select-soc-code').val(result[8]);
};
</script>