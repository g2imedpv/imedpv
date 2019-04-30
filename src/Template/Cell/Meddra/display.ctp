<button type="button" class="btn btn-info btn-sm mx-3 d-block" id="meddraBtn_<?php echo $meddraFieldId;?>" data-toggle="modal" data-target=".bd-example-modal-sm"><i class="fas fa-search"></i> MedDRA Browser</button>
<div class="lltQuickSearch form-group col-md-6">
    <label>LLT Term Encode</label>
    <input class="form-control" type="text" id="llt-searchbar_<?php echo $meddraFieldId;?>" >
    <div id="meddraQuickSearch_<?php echo $meddraFieldId;?>"></div>
    <input class="form-control" type="hidden" id="descriptor_<?php echo $meddraFieldId;?>" value="<?php echo $descriptor;?>">
</div>

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
                <div class="form-row mx-2">
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
                        <input type="text" class="form-control" id="wildcard_search"  placeholder="Search by SMQ">
                    </div>
                    <div class="form-group">
                        <label for="meddra-full_text">Full Search</label>
                        <input type="checkbox" class="form-control" id="meddra-full_text">
                    </div>
                </div>
                <!-- <div class="form-row justify-content-center">
                    <div class="form-group col-sm-3">
                        <div id="meddrasea" onclick="searchMedDra()" class="form-control btn btn-primary w-100"><i class="fas fa-search"></i> Search</div>
                    </div>
                    <div class="form-group col-sm-1">
                        <div class="clearsearch form-control btn btn-outline-danger w-100"><i class="fas fa-eraser"></i> Clear</div>
                    </div>
                </div> -->
            </div>
            <div class="container mb-5">
                    <div class="form-row justify-content-around">
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
                    <div style="height: 300px;" class="form-row justify-content-around">
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-soc_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-hlgt_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-hlt_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-pt_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-llt_name"></div>
                    </div>
            </div>
            <!-- Table field (Should be hidden before search) -->
            <h4 class="text-center">MedDra Details</h4> <hr>
            <div class="container">
                <fieldset disabled>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">LLT Name</label>
                            <input type="text" class="form-control" id="select-llt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">LLT Code</label>
                            <input type="text" class="form-control" id="select-llt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">PT Name</label>
                            <input type="text" class="form-control" id="select-pt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">PT Code</label>
                            <input type="text" class="form-control" id="select-pt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">HLT Name</label>
                            <input type="text" class="form-control" id="select-hlt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">HLT Code</label>
                            <input type="text" class="form-control" id="select-hlt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">HLGT Name</label>
                            <input type="text" class="form-control" id="select-hlgt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">HLGT Code</label>
                            <input type="text" class="form-control" id="select-hlgt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for="">SOC Name</label>
                            <input type="text" class="form-control" id="select-soc-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for="">SOC Code</label>
                            <input type="text" class="form-control" id="select-soc-c">
                        </div>
                    </div>
                </fieldset>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success w-25 mx-auto"  onclick="selectMeddraButton(<?php echo $meddraFieldId?>)" data-dismiss="modal">Select</button>
      </div>
    </div>
  </div>
</div>


<script>
var meddraFieldId = 0;
var formats = ['llt_name','pt_name','hlt_name','hlgt_name','soc_name'];
$(document).ready(function(){
    $('[id^=meddraBtn_]').click(function(){
        console.log("clicked");
        if(meddraFieldId!=$(this).attr('id').split('_')[1]){
            $(formats).each(function(formatK,formatV){
                $('#field-'+formatV).html("");
            });
        }
        meddraFieldId = $(this).attr('id').split('_')[1];
        if($('[id$=meddraResult-'+meddraFieldId+']').val()!=""){
            $.each($('[id$=meddraResult-'+meddraFieldId+']').val().split(','), function(k,fieldDetail){
                console.log(k);
                console.log(fieldDetail);
                switch(k){
                    case 0:
                        $('#select-llt-n').val(fieldDetail);
                        return true;
                    case 1:
                        $('#select-llt-c').val(fieldDetail);
                        return true;
                    case 2:
                        $('#select-pt-n').val(fieldDetail);
                        return true;
                    case 3:
                        $('#select-pt-c').val(fieldDetail);
                        return true;
                    case 4:
                        $('#select-hlt-n').val(fieldDetail);
                        return true;
                    case 5:
                        $('#select-hlt-c').val(fieldDetail);
                        return true;
                    case 6:
                        $('#select-hlgt-n').val(fieldDetail);
                        return true;
                    case 7:
                        $('#select-hlgt-c').val(fieldDetail);
                        return true;
                    case 8:
                        $('#select-soc-n').val(fieldDetail);
                        return true;
                    case 9:
                        $('#select-soc-c').val(fieldDetail);
                        return true;
                }
            });
        }else{
            $('#select-llt-n').val("");
            $('#select-llt-c').val("");
            $('#select-pt-n').val("");
            $('#select-pt-c').val("");
            $('#select-hlt-n').val("");
            $('#select-hlt-c').val("");
            $('#select-hlgt-n').val("");
            $('#select-hlgt-c').val("");
            $('#select-soc-n').val("");
            $('#select-soc-c').val("");
        }
    });
    $('[id^=typed]').change(function(){
        searchMedDra(meddraFieldId,1);//TODO 
    });
    $('[id^=llt-searchbar]').keyup(function(){
        meddraFieldId = $(this).attr('id').split('_')[1];
        $('#meddraQuickSearch_'+meddraFieldId).show();
        if($(this).val().length >=3)
            searchMedDra(meddraFieldId, 3, $(this).val());
        else $('#meddraQuickSearch_'+meddraFieldId).hide();
    });
});
function searchMedDra(meddraFieldId, type, llt_name=null) {
    var request ={};
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
        request['full_text'] = 0;
        request['type'] = type;
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
            console.log(response);
            var result = $.parseJSON(response);
            console.log(meddraFieldId);
            $('[id$=-meddraResult-'+meddraFieldId+']').val(result['primary']);
            if(result['type']==3){
                let text = "<ul>";
                $.each(result['llt_name']['codes'],function(k,details){
                    text +="<li id=\"quickSearchResult-"+meddraFieldId+"-"+k+"\">"+details[0]+"-"+details[1]+"</li>";
                })
                text +="</ul>";
                $("#meddraQuickSearch_"+meddraFieldId).html(text);
                $('[id*=quickSearchResult]').click(function(){
                    let meddraFieldId = $(this).attr('id').split('-')[1];
                    searchMedDra(meddraFieldId, 4, $(this).text().split('-')[0]);
                    $('#meddraQuickSearch_'+meddraFieldId).hide();
                });
                return false;
            }
            if(result['type']==4){
                let descriptor = $('#descriptor_'+meddraFieldId).val().split(',');
                $.each(descriptor, function(k,fieldDetail){
                    let mappedId = fieldDetail.split(':')[1];
                    let mappedLabel = fieldDetail.split(':')[0].split('-');
                    if(mappedLabel=="ver") {
                        $('[id$='+mappedId+'][name$=\\[field_value\\]]').val('18.1');
                        return true;
                    }
                    switch(mappedLabel[0]){
                        case "llt":
                            if(mappedLabel[1] == "c") $('[id$='+mappedId+'][name$=\\[field_value\\]]').val(result['primary'][0][1]);
                            else $('[id$='+mappedId+'][name$=\\[field_value\\]]').val(result['primary'][0][0]);
                            return true;
                        case "pt":
                            if(mappedLabel[1] == "c") $('[id$='+mappedId+'][name$=\\[field_value\\]]').val(result['primary'][0][3]);
                            else $('[id$='+mappedId+'][name$=\\[field_value\\]]').val(result['primary'][0][2]);
                            return true;
                        case "hlt":
                            if(mappedLabel[1] == "c") $('[id$='+mappedId+'][name$=\\[field_value\\]]').val(result['primary'][0][5]);
                            else $('[id$='+mappedId+'][name$=\\[field_value\\]]').val(result['primary'][0][4]);
                            return true;
                        case "hlgt":
                            if(mappedLabel[1] == "c") $('[id$='+mappedId+'][name$=\\[field_value\\]]').val(result['primary'][0][7]);
                            else $('[id$='+mappedId+'][name$=\\[field_value\\]]').val(result['primary'][0][6]);
                            return true;
                        case "soc":
                            if(mappedLabel[1] == "c") $('[id$='+mappedId+'][name$=\\[field_value\\]]').val(result['primary'][0][9]);
                            else $('[id$='+mappedId+'][name$=\\[field_value\\]]').val(result['primary'][8][0]);
                            return true;
                    }
                });
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
                            text +="<label class=\"meddralabel\" id=\"meddralabel-"+formatV+"-"+k+"\" for=\"meddraoption-"+formatV+"-"+k+"\">";
                            text +=v[0];
                            text +="</label>";
                            // if((typeof result[formatV]['primary']!='undefined')&&(v[1]==result[formatV]['primary'][0][0]))
                            // text +="<input id=\"primary-"+formatV+"\" type=\"hidden\" > ***";
                            text +="<input type=\"hidden\" id=\"meddracode-"+formatV+"-"+k+"\" value=\""+v[1]+"\" >";
                            // text +="<input onclick=\"searchMedDra("+meddraFieldId+",2)\" type=\"checkbox\" id=\"meddraoption-"+formatV+"-"+k+"\" >";
                            text +="</div>"
                        });
                        $('#field-'+formatV).html(text);
                    }else $('#field-'+formatV).html("");
                }
                $('[id^=meddradiv-'+formatV+']').each(function(){
                    var hasP = 0;
                    if($(this).hasClass('bg-primary')){
                        console.log($('#select-'+formatV.split('_')[0]+'-n'));
                        console.log($('#select-'+formatV.split('_')[0]+'-c'));
                        $('#select-'+formatV.split('_')[0]+'-n').val($(this).find('label').text());
                        $('#select-'+formatV.split('_')[0]+'-c').val($(this).find('[id^=meddracode]').val());
                        hasP = 1;
                        return false;
                    }
                    if(hasP) return true;
                    else if($(this).hasClass('bg-warning')){
                        $('#select-'+formatV.split('_')[0]+'-n').val($(this).find('label').text());
                        $('#select-'+formatV.split('_')[0]+'-c').val($(this).find('[id^=meddracode]').val());
                        return false;
                    }
                });
            });
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
    if(hasP) searchMedDra(meddraFieldId,2);
}
function selectMeddraButton(meddraFieldId){
    console.log($('#whodrug-code'+meddraFieldId));
    let descriptor = $('#descriptor_'+meddraFieldId).val().split(',');
    
    $.each(descriptor, function(k,fieldDetail){
        let mappedId = fieldDetail.split(':')[1];
        let mappedLabel = fieldDetail.split(':')[0];
        if(mappedLabel=="ver"){
            $('[id$='+mappedId+'][name$=\\[field_value\\]]').val('18.1');
            return true;
        } 
        $('[id$='+mappedId+'][name$=\\[field_value\\]]').val($('#select-'+mappedLabel.split('-')[0]+'-'+mappedLabel.split('-')[1]).val());
    });
    return false;
}
</script>