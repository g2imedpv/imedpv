
var meddraFieldId = 0;
var formats = ['llt_name','pt_name','hlt_name','hlgt_name','soc_name'];
$(document).ready(function(){
    $('[id^=meddraBtn_]').click(function(){

        if(meddraFieldId!=$(this).attr('id').split('_')[1]){
            $(formats).each(function(formatK,formatV){
                $('#field-'+formatV).html("");
            });
        }
        meddraFieldId = $(this).attr('id').split('_')[1];
        console.log(meddraFieldId);
        if($('[id$=meddraResult-'+meddraFieldId+']').val()!=""){
            $.each($('[id$=meddraResult-'+meddraFieldId+']').val().split(','), function(k,fieldDetail){
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
        if($(this).val().length >=3 || ($(this).val().length >0 && !/^[a-zA-Z]+$/.test($(this).val())))
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
            };
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
        beforeSend:function () {
            $('.loadingSpinner').show();
            $('.pendingShow').hide();
        },
        success:function(response){
            console.log(response);
            var result = $.parseJSON(response);
            console.log(meddraFieldId);
            $('[id$=meddraResult-'+meddraFieldId+']').val(result['primary']);
            if(result['type']==3){
                let text = "<ul>";
                $.each(result['llt_name']['codes'],function(k,details){
                    text +="<li id=\"quickSearchResult-"+meddraFieldId+"-"+k+"\">"+details[0]+"---"+details[1]+"</li>";
                })
                text +="</ul>";
                $("#meddraQuickSearch_"+meddraFieldId).html(text);
                $('[id*=quickSearchResult]').click(function(){
                    let meddraFieldId = $(this).attr('id').split('-')[1];
                    searchMedDra(meddraFieldId, 4, $(this).text().split('---')[0]);
                    $('#meddraQuickSearch_'+meddraFieldId).hide();
                });
                return false;
            }
            if(result['type']==4){
                //click action
                let descriptor = $('#descriptor_'+meddraFieldId).val().split(',');
                $.each(descriptor, function(k,fieldDetail){
                    let mappedId = fieldDetail.split(':')[1];
                    let mappedLabel = fieldDetail.split(':')[0].split('-');
                    if(mappedLabel=="ver") {
                        $('[id$=meddrashow-'+mappedId+']').val('24.1');
                        return true;
                    }console.log(result);
                    switch(mappedLabel[0]){
                        case "llt":
                            if(mappedLabel[1] == "c") $('[id$=meddrashow-'+mappedId+']').val(result['primary'][0][1]);
                            else $('[id$=meddrashow-'+mappedId+']').val(result['primary'][0][0]).trigger('change');
                            return true;
                        case "pt":
                            if(mappedLabel[1] == "c") $('[id$=meddrashow-'+mappedId+']').val(result['primary'][0][3]);
                            else $('[id$=meddrashow-'+mappedId+']').val(result['primary'][0][2]).trigger('change');
                            return true;
                        case "hlt":
                            if(mappedLabel[1] == "c") $('[id$=meddrashow-'+mappedId+']').val(result['primary'][0][5]);
                            else $('[id$=meddrashow-'+mappedId+']').val(result['primary'][0][4]).trigger('change');
                            return true;
                        case "hlgt":
                            if(mappedLabel[1] == "c") $('[id$=meddrashow-'+mappedId+']').val(result['primary'][0][7]);
                            else $('[id$=meddrashow-'+mappedId+']').val(result['primary'][0][6]).trigger('change');
                            return true;
                        case "soc":
                            if(mappedLabel[1] == "c") $('[id$=meddrashow-'+mappedId+']').val(result['primary'][0][9]);
                            else $('[id$=meddrashow-'+mappedId+']').val(result['primary'][8][0]).trigger('change');
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
        complete: function () {
            $('.loadingSpinner').hide();
            $('.pendingShow').show();
        },
        error:function(response){
            console.log(response.responseText);
            $("#noMDmatch").html(i18n.gettext("Sorry, no case matches"));

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
function meddrabrowser(meddraFieldId){
    $("#meddraSelectBtn").attr("onclick","selectMeddraButton("+meddraFieldId+")");
}
function selectMeddraButton(meddraFieldId){
    console.log($('#whodrug-code'+meddraFieldId));
    let descriptor = $('#descriptor_'+meddraFieldId).val().split(',');

    $.each(descriptor, function(k,fieldDetail){
        let mappedId = fieldDetail.split(':')[1];
        let mappedLabel = fieldDetail.split(':')[0];
        if(mappedLabel=="ver"){
            $('[id$=meddrashow-'+mappedId+']').val('24.1');
            return true;
        }
        $('[id$=meddrashow-'+mappedId+']').val($('#select-'+mappedLabel.split('-')[0]+'-'+mappedLabel.split('-')[1]).val()).trigger('change');
    });
    return false;
}