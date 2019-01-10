<div id="whodrabrowser">
<button type="button" class="btn btn-outline-info btn-sm" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fas fa-search"></i> WHODD Browser</button>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 1175px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">WHODD Browser</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <!-- Search field -->
            <div class="container">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <input type="text" class="form-control" id="atc" name="atc" placeholder="ATC code">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" class="form-control" id="drugcode" name="drugcode" placeholder="Drug code">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" class="form-control" id="medicalProd" placeholder="Medicinal Prod ID">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" class="form-control" id="tradename" placeholder="Trade Name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <input type="text" class="form-control" id="ingredient" placeholder="Ingredient">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" class="form-control" id="formulation" placeholder="Formulation">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" class="form-control" id="strength" placeholder="Strength">
                    </div>
                    <div class="form-group col-md-3">
                        <select id="country" class="form-control">
                            <option value="null" selected>Choose Country</option>
                            <?php foreach($contryList as $key => $contry)
                            echo "<option value=".$contry->CountryCode.">".$contry->CountryName."</option>";
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <div class="form-group col-sm-2">
                        <div id="whoddsea" onclick="searchWhoDra()" class="form-control btn btn-primary w-100"><i class="fas fa-search"></i> Search</div>
                    </div>
                    <div class="form-group col-sm-1">
                        <div class="clearsearch form-control btn btn-outline-danger w-100"><i class="fas fa-eraser"></i> Clear</div>
                    </div>
                </div>
            </div>

            <!-- Table field (Should be hidden before search) -->
            <div id="whoddtable"></div>


            <!-- Detail field (Should be hidden before search) -->
            <h4 class="text-center">Drug Details</h4> <hr>
            <div class="container">
                <fieldset disabled>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="">Trade Name</label>
                            <input type="text" class="form-control" id="select-trade-name">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">MAH</label>
                            <input type="text" class="form-control" id="select-mah">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">Drug Code</label>
                            <input type="text" class="form-control" id="select-drug-code">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="">ATC Code</label>
                            <input type="text" class="form-control" id="select-atc-code">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="">Medicinal Product ID</label>
                            <input type="text" class="form-control" id="select-med-pro">
                        </div>
                        <div class="form-group col-md-9">
                            <label for="">Ingredients</label>
                            <input type="text" class="form-control" id="select-ingredient">
                        </div>
                    </div>
                    <div class="form-row">
                        <label for="">ATC Description</label>
                        <textarea class="form-control" id="select-atc-description" rows="3"></textarea>
                    </div>
                </fieldset>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="selectButton(<?php echo $fieldId?>)" data-dismiss="modal">Select</button>
      </div>
    </div>
  </div>
</div>
</div>


<script>
function selectButton(fieldId){
    console.log($('#whodrug-code'+fieldId));
    $('[id$=whodracode-'+fieldId+']').val($('#select-drug-code').val());
    $('[id*=whodraname]').val($('#select-trade-name').val());
}
function selectDrug(key){
    var drugdetail = $('#drugdetail'+key).html();
    var result = $.parseJSON(drugdetail);
    $('#select-trade-name').val(result[0]);
    $('#select-mah').val(result[14]);
    $('#select-drug-code').val(result[4]+'.'+result[5]+'.'+result[6]);
    $('#select-med-pro').val(result[2]);
    $('#select-atc-code').val(result[1]);
    $('#select-atc-description').val(result[16]);
    $('#select-ingredient').val(result[11]);
};
function searchWhoDra(){
    var request = {
        'atc-code': $("#atc").val(),
        'drug-code':$("#drugcode").val(),
        'medicinal-prod-id':$('#medicalProd').val(),
        'trade-name':$('#tradename').val(),
        'ingredient':$('#ingredient').val(),
        'formulation':$('#formulation').val(),
        'country':$('#country').val(),
        'strength':$('#strength').val(),
    };
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/who-dra/search',
        data:request,
        success:function(response){        console.log(response);
            var result = $.parseJSON(response);



        var text = "";
        text +="<h4 class=\"text-center\">Search Results</h4>";
        text +="<table class=\"table table-hover table-striped\" id=\"demo\">";

        text += "<thead>";
        text +="<tr class=\"table-secondary\">";
        text +="<th scope=\"col\">Trade Name</th>";
        text +="<th scope=\"col\">Formulation / Strength</th>";
        text +="<th scope=\"col\">Sales Country</th>";
        text +="<th scope=\"col\">Generic?</th>";

        text +="</tr>";
        text +="</thead>";
        text +="<tbody>";
        $.each(result, function(k,v){
            text += "<tr onclick=\"selectDrug("+k+")\">";
            text += "<td>" + v[0]+ "</td>";
            text += "<td>" + v[12]+" / " + v[13] + "</td>";
            text += "<td>" + v[14] + "</td>";
            text += "<td>" + v[8] + "</td>";
            text += "<td id=\"drugdetail"+k+"\" style=\"display:none\">"+JSON.stringify(v)+"</td>";
            text += "</tr>";
        })
        text +="</tbody>";
        text +="</table>";
        $("#whoddtable").html(text);
        $('#demo').DataTable();
        },
        error:function(response){
                console.log(response.responseText);

            $("#textHint").html("Sorry, no case matches");

        }
    });
}
</script>