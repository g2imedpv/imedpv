<button type="button" class="btn btn-info btn-sm mx-3" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="fas fa-search"></i> MedDRA Browser</button>
<input type="text" id="llt-searchbar">
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
                    <!-- <div class="form-group col-md-4">
                        <label>Search LLT Term</label>
                        <input type="text" class="form-control" id="llt_term"  placeholder="Search LLT Term">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Search PT Term</label>
                        <input type="text" class="form-control" id="pt_term"  placeholder="Search PT Term">
                    </div> -->
                    <div class="form-group col-md-4">
                        <label>SMQ:</label>
                        <input type="text" class="form-control" id="wildcard_search"  placeholder="Search by Key Word">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="meddra-full_text">full text</label>
                        <input type="checkbox" class="form-control" id="meddra-full_text">
                    </div>
                </div>
                <!-- <div class="form-row justify-content-center">
                    <div class="form-group col-sm-3">
                        <div id="meddrasea" onclick="searchMedDra(<?= $fieldId ?>)" class="form-control btn btn-primary w-100"><i class="fas fa-search"></i> Search</div>
                    </div>
                    <div class="form-group col-sm-1">
                        <div class="clearsearch form-control btn btn-outline-danger w-100"><i class="fas fa-eraser"></i> Clear</div>
                    </div>
                </div> -->
            </div>
            <div class="container">
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-2">
                            <label for="">SOC Name</label>
                            <input type="text" class="form-control" id="typed-soc-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">HLGT Name</label>
                            <input type="text" class="form-control" id="typed-hlgt-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">HLT Name</label>
                            <input type="text" class="form-control" id="typed-hlt-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">PT Name</label>
                            <input type="text" class="form-control" id="typed-pt-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="">LLT Name</label>
                            <input type="text" class="form-control" id="typed-llt-name">
                        </div>
                    </div>
                    <div style="height: 300px;" class="form-row justify-content-center">
                        <div class="form-group border h-100 col-md-2" id="field-soc_name"></div>
                        <div class="form-group border h-100 col-md-2" id="field-hlgt_name"></div>
                        <div class="form-group border h-100 col-md-2" id="field-hlt_name"></div>
                        <div class="form-group border h-100 col-md-2" id="field-pt_name"></div>
                        <div class="form-group border h-100 col-md-2" id="field-llt_name"></div>
                    </div>
            </div>
            <!-- Table field (Should be hidden before search) -->
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
<?php 
    if($fieldId!=null)
    echo "var fieldId =".$fieldId." ;";
    else     echo "var fieldId =0 ;";
?>
$(document).ready(function(){
    $('[id^=typed]').change(function(){
        var type = $(this).attr('id').split('-')[1];
        searchMedDra(fieldId,1);
        console.log('typed')
    });
    $('#llt-searchbar').change(function(){
        searchMedDra(fieldId, 3, $(this).val());
    });
});
function searchMedDra(fieldId, type, llt_name=null) {
    var request ={};
    var formats = ['llt_name','pt_name','hlt_name','hlgt_name','soc_name'];
    if(type==1){
        request ={};
        $('[id^=typed]').each(function(){
            var thisField = $(this).attr('id').split('-')[1];
            if($(this).val()!=""){
                request[thisField+'_name'] = $.trim($(this).val());
            }
        });
        if($('#meddra-full_text').prop('checked'))
            request['full_text'] = 1;
        else request['full_text'] = 0;
        request['type'] = 1;
    }else if(type==2){
        request ={};
        $(formats).each(function(k, format){
            var hasP = 0
            $('#field-'+format).find('.bg-primary').each(function(){
                request[format] = $(this).find('label').text();
                hasP = 1;
                return false;
            })
            if(!hasP) $('#typed-'+format.split('_')[0]+"-name").val("");
            // $('[id^=meddradiv].bg-primary').each(function(){
            //     var thisField = $(this).attr('id').split('-');
            //     request[thisField[1]] = $(this).find('label').text();
            // });
        })
        if($('#meddra-full_text').prop('checked'))
            request['full_text'] = 1;
        else request['full_text'] = 0;
        request['type'] = 2;
    }else{
        request['llt_name'] = llt_name;
        request['type'] = 3;
    }
    console.log(request);
    $.ajax({
        headers: {
            'X-CSRF-Token': csrfToken
        },
        type:'POST',
        url:'/med-dra/search',
        data:request,
        success:function(response){   
            console.log('su');   
            console.log(response);
            var result = $.parseJSON(response);
            if(result['type']==3){
                $('[id*=reactionmeddrallt]').val(result['primary'][0][1]);
                $('[id*=meddralltname]').val(result['primary'][0][0]);
                $('[id*=meddralltcode]').val(result['primary'][0][1]);
                $('[id*=reactionmeddrapt]').val(result['primary'][0][3]);
                $('[id*=meddraptname]').val(result['primary'][0][2]);
                $('[id*=meddraptcode]').val(result['primary'][0][3]);
                $('[id*=meddrahltname]').val(result['primary'][0][4]);
                $('[id*=meddrahltcode]').val(result['primary'][0][5]);
                $('[id*=meddrahlgtname]').val(result['primary'][0][6]);
                $('[id*=meddrahlgtcode]').val(result['primary'][0][7]);
                $('[id*=meddrasoccode]').val(result['primary'][0][8]);
                $('[id*=meddrasoccode]').val(result['primary'][0][9]);
                $('[id*=meddraversion]').val('18.1');
                return false;
            }
            $(formats).each(function(formatK,formatV){
                var text = "";
                if(!((typeof request[formatV]!='undefined')&&(result['type']==2)))
                {
                    if(typeof result[formatV]!='undefined'){
                        $.each(result[formatV]['codes'], function(k,v){
                            text +="<div";
                            if((typeof result[formatV]['primary']!='undefined')&&(v[1]==result[formatV]['primary'][0][0])) text +=" class=\"bg-warning\"";
                            text +=" onclick=\"clickOption('"+formatV+"',"+k+")\" id=\"meddradiv-"+formatV+"-"+k+"\"";
                            text +=">";
                            text +="<label id=\"meddralabel-"+formatV+"-"+k+"\" for=\"meddraoption-"+formatV+"-"+k+"\">";
                            text +=v[0];
                            text +="</label>"; 
                            // if((typeof result[formatV]['primary']!='undefined')&&(v[1]==result[formatV]['primary'][0][0])) 
                            // text +="<input id=\"primary-"+formatV+"\" type=\"hidden\" > ***";
                            text +="<input type=\"hidden\" id=\"meddracode-"+formatV+"-"+k+"\" value=\""+v[1]+"\" >";
                            // text +="<input onclick=\"searchMedDra("+fieldId+",2)\" type=\"checkbox\" id=\"meddraoption-"+formatV+"-"+k+"\" >";
                            text +="</div>"
                        });
                        $('#field-'+formatV).html(text);
                    }else $('#field-'+formatV).html("");
                }
                $('[id^=meddradiv-'+formatV+']').each(function(){
                    var hasP = 0;
                    if($(this).hasClass('bg-primary')){
                        console.log($('#select-'+formatV.split('_')[0]+'-name'));
                        console.log($('#select-'+formatV.split('_')[0]+'-code'));
                        $('#select-'+formatV.split('_')[0]+'-name').val($(this).find('label').text());
                        $('#select-'+formatV.split('_')[0]+'-code').val($(this).find('[id^=meddracode]').val());
                        hasP = 1;
                        return false;
                    }
                    if(hasP) return true;
                    else if($(this).hasClass('bg-warning')){
                        $('#select-'+formatV.split('_')[0]+'-name').val($(this).find('label').text());
                        $('#select-'+formatV.split('_')[0]+'-code').val($(this).find('[id^=meddracode]').val());
                        return false;
                    }
                });
            });

            //     $('#select-llt-name').val();
            // });
            // $('#select-llt-name').val();
            // $('#select-llt-code').val($('#primary-llt_name').next().val());
            // $('#select-pt-name').val($('#primary-pt_name').prev().text());
            // $('#select-pt-code').val($('#primary-pt_name').next().val());
            // $('#select-hlt-name').val($('#primary-hlt_name').prev().text());
            // $('#select-hlt-code').val($('#primary-hlt_name').next().val());
            // $('#select-hlgt-name').val($('#primary-hlgt_name').prev().text());
            // $('#select-hlgt-code').val($('#primary-hlgt_name').next().val());
            // $('#select-soc-name').val($('#primary-soc_name').prev().text());
            // $('#select-soc-code').val($('#primary-soc_name').next().val());
        // $('[id^=meddradiv]').click(function(){
        //     console.log('clicked');
        //     var term = $(this).attr('id').split('-');
        //     console.log(term);
        //     if($(this).hasClass('bg-primary')){
        //         console.log('has primary');
        //         $(this).removeClass('bg-primary'); 
        //         searchMedDra("+fieldId+",2);
        //         return false;
        //     }else{
        //         $('[id^=meddradiv-'+term[1]+']').each(function(){
        //             if($(this).hasClass('bg-primary')) {$(this).removeClass('bg-primary'); return true;}
        //         });
        //         $('[id^=meddradiv-'+term[1]+']').each(function(){
        //             if($(this).hasClass('bg-warning')) {$(this).removeClass('bg-warning'); return true;}
        //         });
        //         $(this).attr('class','bg-primary');
            
        //     searchMedDra("+fieldId+",2);return false;
        // }
        // });
        },
        error:function(response){
                console.log(response.responseText);

            $("#textHint").html("Sorry, no case matches");

        }
    });
}
function clickOption(term, key){
    if($('#meddradiv-'+term+'-'+key).hasClass('bg-primary')){
        console.log('has primary');
        $('#meddradiv-'+term+'-'+key).removeClass('bg-primary'); 
    }else{
        $('[id^=meddradiv-'+term+']').each(function(){
            if($(this).hasClass('bg-primary')) {$(this).removeClass('bg-primary'); return true;}
        });
        $('[id^=meddradiv-'+term+']').each(function(){
            if($(this).hasClass('bg-warning')) {$(this).removeClass('bg-warning'); return true;}
        });
        $('#meddradiv-'+term+'-'+key).attr('class','bg-primary');
    }
    var hasP = 0
    $('[id^=meddradiv]').each(function(){
        if($(this).hasClass('bg-primary')) {
            hasP = 1;
            return false;
        }
    });
    if(hasP) searchMedDra("+fieldId+",2);
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