<div id="whodrabrowser">
<button type="button" class="btn btn-outline-info float-left mr-3" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fas fa-search"></i> <?php echo __('WHO-DD Browser')?></button>
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 1175px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id=""><?php echo __("WHO-DD Browser")?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <!-- Search field -->
            <div class="container">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for=""><?php echo __("ATC Code")?></label>
                        <input type="text" class="form-control" id="atc" placeholder="<?php echo __("ATC code")?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for=""><?php echo __("Drug Code")?></label>
                        <input type="text" class="form-control" id="drugcode" placeholder="<?php echo __("Drug code")?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for=""><?php echo __("Medicinal Prod ID")?></label>
                        <input type="text" class="form-control" id="medicalProd" placeholder="<?php echo __("Medicinal Prod ID")?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for=""><?php echo __("Trade Name")?></label>
                        <input type="text" class="form-control" id="tradename" placeholder="<?php echo __("Trade Name")?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for=""><?php echo __("Ingredient")?></label>
                        <input type="text" class="form-control" id="ingredient" placeholder="<?php echo __("Ingredient")?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for=""><?php echo __("Formulation")?></label>
                        <input type="text" class="form-control" id="formulation" placeholder="<?php echo __("Formulation")?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for=""><?php echo __("Strength")?></label>
                        <input type="text" class="form-control" id="strength" placeholder="<?php echo __("Strength")?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for=""><?php echo __("Choose Country")?></label>
                        <select id="country" class="form-control chocon" style="width: 100%">
                            <option value="null" selected><?php echo __("Choose Country")?></option>
                            <?php foreach($contryList as $key => $contry)
                            echo "<option value=".$contry->CountryCode.">".__($contry->CountryName)."</option>";
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-row justify-content-center">
                    <div class="form-group col-sm-3">
                        <div id="whoddsea" onclick="searchWhoDra()" class="form-control btn btn-primary"><i class="fas fa-search"></i> <?php echo __("Search")?></div>
                    </div>
                    <div class="form-group col-sm-1">
                        <div class="clearsearch form-control btn btn-outline-danger w-100"><i class="fas fa-eraser"></i> <?php echo __("Clear")?></div>
                    </div>
                </div>
            </div>

            <!-- Table field (Should be hidden before search) -->
            <div id="whodd_result"></div>


            <!-- Detail field (Should be hidden before search) -->
            <h4 class="text-center"><?php echo __("Drug Details")?></h4> <hr>
            <div class="container">
                <fieldset disabled>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for=""><?php echo __("Trade Name")?></label>
                            <input type="text" class="form-control" id="select-trade-name">
                        </div>
                        <div class="form-group col-md-3">
                            <label for=""><?php echo __("MAH")?></label>
                            <input type="text" class="form-control" id="select-mah">
                        </div>
                        <div class="form-group col-md-3">
                            <label for=""><?php echo __("Drug Code")?></label>
                            <input type="text" class="form-control" id="select-drug-code">
                        </div>
                        <div class="form-group col-md-3">
                            <label for=""><?php echo __("ATC Code")?></label>
                            <input type="text" class="form-control" id="select-atc-code">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for=""><?php echo __("Medicinal Product ID")?></label>
                            <input type="text" class="form-control" id="select-med-pro">
                        </div>
                        <div class="form-group col-md-9">
                            <label for=""><?php echo __("Ingredients")?></label>
                            <input type="text" class="form-control" id="select-ingredient">
                        </div>
                    </div>
                    <div class="form-row">
                        <label for=""><?php echo __("ATC Description")?></label>
                        <textarea class="form-control" id="select-atc-description" rows="3"></textarea>
                    </div>
                </fieldset>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success w-25 mx-auto" onclick="selectWhoDDButton(<?php echo $fieldId?>)" data-dismiss="modal"><?php echo __("Select")?></button>
      </div>
    </div>
  </div>
</div>
</div>


<script type="text/javascript">
function selectWhoDDButton(fieldId){
    console.log($('#select-drug-code').val());
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
        text +="<h4 class=\"text-center\">"+i18n.gettext("Search Results")+"</h4>";
        text +="<table class=\"table table-hover table-striped\" id=\"whodd_table\">";

        text += "<thead>";
        text +="<tr class=\"table-secondary\">";
        text +="<th scope=\"col\">"+i18n.gettext("Trade Name")+"</th>";
        text +="<th scope=\"col\">"+i18n.gettext("Formulation / Strength")+"</th>";
        text +="<th scope=\"col\">"+i18n.gettext("Sales Country")+"</th>";
        text +="<th scope=\"col\">"+i18n.gettext("Generic?")+"</th>";

        text +="</tr>";
        text +="</thead>";
        text +="<tbody>";
        $.each(result, function(k,v){
            text += "<tr onclick=\"selectDrug("+k+")\">";
            text += "<td>" + v[0]+ "</td>";
            text += "<td>" + v[12]+" / " + v[13] + "</td>";
            text += "<td>" + v[14] + "</td>";
            text += "<td>" + v[8] + "</td>";
            text += "<div id=\"drugdetail"+k+"\" hidden>"+JSON.stringify(v)+"</div> ";
            text += "</tr>";
        })
        text +="</tbody>";
        text +="</table>";
        $("#whodd_result").html(text);
        $('#whodd_table').DataTable();
        },
        error:function(response){
                console.log(response.responseText);

            $("#textHint").html(i18n.gettext("Sorry, no case matches"));

        }
    });
}
// function dataTable(){
//     $('#whodd_table').each(function() {
//         var currentPage = 0;
//         var numPerPage = 10;
//         var $table = $(this);
//         $table.bind('repaginate', function() {
//             $table.find('tbody tr').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
//         });
//         $table.trigger('repaginate');
//         var numRows = $table.find('tbody tr').length;
//         var numPages = Math.ceil(numRows / numPerPage);
//         var $pager = $('<div class="pager"></div>');
//         for (var page = 0; page < numPages; page++) {
//             $('<span class="page-number"></span>').text(page + 1).bind('click', {
//                 newPage: page
//             }, function(event) {
//                 currentPage = event.data['newPage'];
//                 $table.trigger('repaginate');
//                 $(this).addClass('active').siblings().removeClass('active');
//             }).appendTo($pager).addClass('clickable');
//         }
//         $pager.insertBefore($table).find('span.page-number:first').addClass('active');
//     });
// }

    $(document).ready(function(){
        $(".chocon").select2({
            width: 'resolve'
        });
    });
</script>