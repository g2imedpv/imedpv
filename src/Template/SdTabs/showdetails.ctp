<?php
//echo "I am here<br>";
//debug($sdTabs);
//foreach ($sdTabs as $eachTab){  debug($eachTab->tab_name);  }

// Call to use widget
echo $this->element('widget');
echo $this->element('generatepdf');
?>
<script type="text/javascript">
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
    var readonly =  <?php if($this->request->getQuery('readonly')!=1){$readonly = 0;}
                        else{$readonly = 1;};echo $readonly;?>;
    var caseNo = "<?= $caseNo ?>";
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    var version = <?= $version ?>;
    var distribution_id = <?php if(empty($distribution_id)) echo "null"; else echo $distribution_id;?>;
    var tabId = <?= $tabid?>;
    var section = <?php $sdSections;
    echo json_encode($sdSections)?>;
    var caseId = <?= $caseId ?>;
    jQuery(function($) {
        $(document).ready(function () {
            $("[id$=page_number-1]").css('font-weight', 'bold');
    });
    })
</script>
<title>Data Entry</title>
<head>
    <?= $this->Html->script('dataentry/dataEntryMain.js') ?>
    <?= $this->Html->script('dataentry/fieldLogic.js') ?>
</head>
<?php if($this->request->getQuery('readonly')!=1):?>
<!-- Data Entry Top Bar -->

<ul class="topbar nav justify-content-end pt-2 pb-2" id="topbar">

    <!-- "Case Number" Display -->
    <span class="caseNumber" id="caseNumber" title="Case Number">
        Full Data Entry - <b><?= $caseNo ?></b> [<?= $product_name?>]<b>(Version:<?= $version?>)</b>
    </span>

    <!-- "Search" Button -->
    <li class="nav-item">
        <button class="btn btn-outline-info" title="Search" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-search"></i> Search</button>
        </b>
        <div class="dropdown-menu p-3">
            <div class="form-group">
                <input type="text" class="form-control" id="searchFieldKey" placeholder="Search Field Here">
                <!-- <button type="submit" class="btn btn-primary mx-2">Search</button> -->
            </div>
            <div id="searchFieldResult"></div>
        </div>
    </li>

    <!-- "Version Switch" Dropdown Button -->
    <li class="nav-item">
        <a class="btn btn-outline-warning" href="#" title="Version Switch" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-code-branch"></i> Switch Version
        </a>
        <?php
        if(sizeof($case_versions->toList())>1){
            echo "<div class=\"dropdown-menu\">";
            foreach($case_versions as $case_version_detail){
                echo "<a class=\"dropdown-item\" href=\"/sd-tabs/showdetails/1?caseNo=".$caseNo."&version=".$case_version_detail['version_no']."\">".$case_version_detail['version_no']."</a>";
            }
        }
        echo "</div>";
        ?>
    </li>

    <!-- "Compare" Button -->
    <!-- <li class="nav-item">
        <a class="btn btn-outline-info" href="#" title="Version Compare"><i class="far fa-copy"></i> Compare</a>
    </li> -->

    <!-- "Documents" Button -->
    <li class="nav-item">
        <a class="btn btn-outline-info" href="/sd-documents/add_documents/<?= $caseId ?>" title="Documents Check" target="_blank"><i class="far fa-file-alt"></i> Documents</a>
    </li>

    <!-- "Print" Dropdown Button -->
    <li class="nav-item">
        <a class="btn btn-outline-info" href="#" title="Print Out" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-print"></i> Print Out
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" target="_blank" href="/sd-export/genCIOMS/<?php echo $caseId ?>">CIOMS</a>
            <a class="dropdown-item" target="_blank" href="/sd-export/genFDApdf/<?php echo $caseId ?>">FDA</a>
            <a class="dropdown-item" target="_blank" href="/sd-xml-structures/genXML/<?php echo $caseId ?>">XML</a>
            <!-- Add this if location had details
            <div role="separator" class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
            -->
        </div>
    </li>

    <!-- "Export" Dropdown Button -->
    <!-- <li class="nav-item">
        <a class="btn btn-outline-info" href="#" title="Export" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-file-export"></i> Export
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">CIOMS</a>
            <a class="dropdown-item" href="#">FDA</a>
            <a class="dropdown-item" href="#">XML</a>
            <div role="separator" class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
        </div>
    </li> -->

    <!-- "Save All" Button -->
    <li class="nav-item">
    <?php if($writePermission==1){
       echo "<button class=\"btn btn-light text-success mx-1\" title=\"Sign Off\" role=\"button\" data-toggle=\"modal\" data-target=\".signOff\" onclick=\"action(1)\"><i class=\"fas fa-share-square\"></i> Next Step</button>";
       echo "<button class=\"btn btn-light text-success mx-1\" title=\"Push Backward\" role=\"button\" data-toggle=\"modal\" data-target=\".signOff\" onclick=\"action(2)\"><i class=\"fab fa-pushed\"></i> Previous Step</button>";
    }?>
    </li>

    <?php endif;?>
    <div class="modal fade signOff" tabindex="-1" role="dialog" aria-labelledby="signOff" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div id="action-text-hint"></div>
            </div>
        </div>
    </div>
</ul>
<div class="maintab container-fluid">
<?php
     $sectionNavCell = $this->cell('SectionNav',[$tabid,$caseNo,$readonly,$version]);
     echo $sectionNavCell;
?>

<!-- Data Entry Body -->
<div class="dataentry">
    <div><?= $sdSections[0]['section_name']?></div>
    <?= $this->Form->create($sdSections,['id'=> 'dataEntry']);?>
    <?php
        $setNo = "1";
        $exsitSectionNo = [];
        foreach($sdSections as $key => $sdSection){
            $exsitSectionNo[$key] = $sdSection['id'];
        }
        // for($i = 0; $i < sizeof($sdSections); $i++){
        //     $exsitSectionNo[$i] = $sdSections->id;
        //     next($sdSections);
        // }
        $result = displaySection($sdSections[0],$sdSections,$exsitSectionNo, $this, $activitySectionPermissions);
        print_r($result['field_Text']);
        print_r($result['child_Field_Text']);
        print_r($result['child_Div_Text']);
        // displaySectionInTabList($result['nav_Text']);
        // echo "<div class=\"tab-content\" id=\"nav-tabContent\">";
        // foreach($sdSections as $sdSection_detail){
        //     $exsitSectionNo = displaySectionOld($sdSection_detail, $exsitSectionNo, $sdSections, $setNo, $this, $activitySectionPermissions);
        // }
        // echo "</div>";

    ?>
    <?php if(($writePermission)&&($this->request->getQuery('readonly')!=1)):?>
    <div class="text-center">
        <button type="submit" class="completeBtn w-25 btn btn-success">Complete</button>
    </div>
    <?php endif;?>
    <hr class="d-inline-block w-100">
    <?= $this->Form->end() ?>
    <?php
    if($this->request->getQuery('readonly')!=1){
        if($tabid==1){
            $sectionTableCell = $this->cell('SectionTable::general', [$caseId]);
            echo $sectionTableCell;
        }elseif($tabid==2)
        {
            $sectionTableCell = $this->cell('SectionTable::reporter', [$caseId]);
            echo $sectionTableCell;
        }elseif($tabid==3)
        {
            $sectionTableCell = $this->cell('SectionTable::patient', [$caseId]);
            echo $sectionTableCell;
        }elseif($tabid==4)
        {
            $sectionTableCell = $this->cell('SectionTable::product', [$caseId]);
            echo $sectionTableCell;
        }elseif($tabid==5)
        {
            $sectionTableCell = $this->cell('SectionTable::event', [$caseId]);
            echo $sectionTableCell;
        }
    }
    ?>
</div>

<?php
/*
* Show sections in tab list
* Modified by Chloe Wang @ April 2, 2019
*/
function displaySectionInTabList($sections)
{
    echo "<nav><div class=\"nav nav-tabs\" id=\"nav-tab\" role=\"tablist\">";
    foreach ($sections as $eachSection)
    {
        if ($eachSection->section_level == 2)
        {
            echo "<a class=\"nav-item";
            if($eachSection->display_order ==10) echo" active";
            echo " nav-link\" id=\"nav-".$eachSection->id."-tab\" data-toggle=\"tab\" href=\"#secdiff-".$eachSection->id."\" role=\"tab\" aria-controls=\"secdiff-".$eachSection->id."\" aria-selected=\"true\">";
            echo $eachSection->section_name;
            echo "</a>";
        }
    }
    echo "</div>";
    echo "</nav>";
}
function displaySectionOld($section, $exsitSectionNo, $sdSections, $setNo, $html, $permission){
    if(empty($exsitSectionNo)) return null;
    if(in_array($section->id,$exsitSectionNo)){
        $sectionKey = array_search($section->id,$exsitSectionNo);
        if(!empty($section->child_section)) {
            echo"<div class=\"tab-pane";
            if($section->display_order ==10)echo" show active\"";else echo " fade\"";
            echo " aria-labelledby=\"nav-".$section->id."-tab\" role=\"tabpanel\" class=\"secdiff\" id=\"secdiff-".$section->id."\">";
        }
        displaySingleSection($section, $setNo, $sectionKey, $html, $permission);

        $exsitSectionNo[$sectionKey]= null;
        if(!empty($section->child_section)){
            $child_array = explode(",",$section->child_section);
            foreach($child_array as $Key => $sdSectionKey){
                if(array_search($sdSectionKey,$exsitSectionNo))
                $exsitSectionNo=displaySectionOld($sdSections[array_search($sdSectionKey,$exsitSectionNo)],$exsitSectionNo,$sdSections, $setNo, $html, $permission);
            }
            echo"</div>";
        }

        return $exsitSectionNo;

    }
    return $exsitSectionNo;
}

function displaySingleSection($section, $setNo, $sectionKey, $html, $permission){
    $i = 0;
    $text ="";
    if($permission == null) $permission = 1;
    else $permission = $permission[$section['id']];
    // if($section->section_level == 2){
    //     $text =$text. "<div id=\"section_label-".$section->id."\" class=\"subtabtitle col-md-12\">".$section->section_name."</div>";
    //     if(($section->is_addable == 1)&&($permission==1))
    //     {
    //         $text =$text. "<div id=\"pagination-l2-section-".$section->id."\">";
    //         $text =$text. "<input type=\"button\" id=\"delete_section-".$section->id."\"  class=\"float-right px-3 mx-3 btn btn-outline-danger\" onclick=\"l2deleteSection(".$section->id.")\" value=\"Delete\" style=\"display:none\">";
    //         $text =$text. "<input type=\"button\" id=\"child_section-";
    //         $child_array = explode(",",$section->child_section);
    //         foreach($child_array as $Key => $sdSectionKey) echo "[".$sdSectionKey."]";
    //         $text =$text. "-sectionKey-".$sectionKey."-setNo-1-section-".$section->id."\" onclick=\"level2setPageChange(".$section->id.",1,1)\" class=\"float-right px-3 mx-3 btn btn-info\" value=\"Add\">";
    //         $text =$text. "</div>";
    //         $text =$text. "<div class=\"showpagination\" id=\"showpagination-".$section->id."\"></div>";
    //     }
    // }elseif($section->section_level ==1 ){

        $max_set_No = 0;
        foreach($section->sd_section_structures as $sd_section_structureK =>$sd_section_structure_detail){
            foreach ($sd_section_structure_detail->sd_field->sd_field_values as $key_detail_field_values=>$value_detail_field_values){
                if($value_detail_field_values->set_number>=$max_set_No)
                    $max_set_No = $value_detail_field_values->set_number;
            }
        }
        $text =$text. "<div class=\"header-section\">";
        $text =$text. "<h3 id=\"section_label-".$section->id."\"class=\"secspace\">".$section->section_name;
        $text =$text. "<input id=\"save-btn".$section->id."-".$sectionKey."\" onclick=\"saveSection(".$section->id.")\" class=\"ml-3 px-5 btn btn-outline-primary\" type=\"button\" value=\"Save\" style=\"display:none\">";
        //echo"<a role=\"button\" id=\"save-btn".$section->id."-".$sectionKey."\" onclick=\"saveSection(".$section->id.")\" class=\"ml-3 px-5 btn btn-outline-secondary\" aria-pressed=\"true\" style=\"display:none\">Save</a>";        // Pagination
        $text =$text. "</h3>";
            if(($section->is_addable == 1)&&($permission==1))
            {
                $text =$text. "<div id=\"pagination-section-".$section->id."\" class=\"DEpagination\">";
                if($max_set_No != 0)
                //echo "<a role=\"button\" id=\"delete-btn".$section->id."-".$sectionKey."\" onclick=\"deleteSection(".$section->id.")\" class=\"ml-3 px-5 btn btn-outline-secondary\" aria-pressed=\"true\">delete</a>";
                $text =$text. "<input id=\"delete-btn".$section->id."-".$sectionKey."\" onclick=\"deleteSection(".$section->id.")\" class=\"ml-3 px-5 btn btn-outline-danger\" type=\"button\" value=\"Delete\">";

                $text =$text. "<input type=\"button\" id=\"add_set-".$section->id."-sectionKey-".$sectionKey."-setNo-".$max_set_No."\" onclick=\"setPageChange(".$section->id.",1,1)\" class=\"float-right px-3 mx-3 btn btn-info\" value=\"Add\"";
                if($max_set_No == 0) $text =$text. "style=\"display:none\">";
                $text =$text. "<nav class=\"float-right ml-3\" title=\"Pagination\" aria-label=\"Page navigation example\">";
                $text =$text. "<ul class=\"pagination mb-0 mx-2\">";
                $text =$text.    "<li class=\"page-item\" id=\"left_set-".$section->id."-sectionKey-".$sectionKey."-setNo-1\" onclick=\"setPageChange(".$section->id.",0)\">";
                $text =$text.    "<a class=\"page-link\" aria-label=\"Previous\">";
                $text =$text.        "<span aria-hidden=\"true\">&laquo;</span>";
                $text =$text.        "<span class=\"sr-only\">Previous</span>";
                $text =$text.    "</a>";
                $text =$text.    "</li>";
                if($max_set_No != 0){
                    for($pageNo = 1; $pageNo<=$max_set_No; $pageNo++ ){
                        $text =$text.    "<li class=\"page-item\" id=\"section-".$section->id."-page_number-".$pageNo."\" onclick=\"setPageChange(".$section->id.",".$pageNo.")\"><a class=\"page-link\">".$pageNo."</a></li>";
                    }
                }else{
                    $text =$text.    "<li class=\"page-item\" style=\"font-weight:bold\" id=\"section-".$section->id."-page_number-1\" onclick=\"setPageChange(".$section->id.",1)\"><a class=\"page-link\">1</a></li>";

                }
                $text =$text.    "<li class=\"page-item\" id=\"right_set-".$section->id."-sectionKey-".$sectionKey."-setNo-1\" onclick=\"setPageChange(".$section->id.",2)\">";
                $text =$text.    "<a class=\"page-link\" aria-label=\"Next\">";
                $text =$text.        "<span aria-hidden=\"true\">&raquo;</span>";
                $text =$text.        "<span class=\"sr-only\">Next</span>";
                $text =$text.    "</a>";
                $text =$text.    "</li>";
                $text =$text. "</ul>";
                $text =$text. "</nav>";
                $text =$text."</div>";
            }
            $text =$text. "<div id=\"addbtnalert-".$section->id."\" class=\"addbtnalert mx-3 alert alert-danger\" role=\"alert\" style=\"display:none;\">You are adding a new record</div>";
            $text =$text."</div>";
            $text =$text. "<div class=\"fieldInput\">";
            $text =$text. "<hr class=\"my-2\">";
        $length_taken = 0;
        $cur_row_no = 0;
        foreach($section->sd_section_structures as $sd_section_structureK =>$sd_section_structure_detail){
            if($i == 0){
                $length_taken = 0;
                $cur_row_no = $sd_section_structure_detail->row_no;
                $text =$text."<div class=\"form-row\">";
            }
            elseif($cur_row_no != $sd_section_structure_detail->row_no){
                $length_taken = 0;
                $cur_row_no = $sd_section_structure_detail->row_no;
                $text =$text."</div><div class=\"form-row\">";
            }
            $j = -1;
            $jflag = 0;
            foreach ($sd_section_structure_detail->sd_field->sd_field_values as $key_detail_field_values=>$value_detail_field_values){
                $jflag ++;
                if($value_detail_field_values->set_number==$setNo){
                    $j = $key_detail_field_values;
                }
            }
            if ($j==-1) $j = $jflag;
            $text =$text. "<div id=\"section-".$section->id."-field-".$sd_section_structure_detail->sd_field->id."\" class=\"form-group col-md-".$sd_section_structure_detail->field_length." offset-md-".($sd_section_structure_detail->field_start_at-$length_taken)."\">";
            $text =$text. "<label id= \"section-".$section->id."-field_label-".$sd_section_structure_detail->sd_field->id."\" >".$sd_section_structure_detail->sd_field->field_label."</label>";
            if(!empty($sd_section_structure_detail->sd_field->comment))
            $text =$text. " <a id=\"field_helper-".$sd_section_structure_detail->sd_field->id."\" tabindex=\"0\" role=\"button\" data-toggle=\"popover\" title=\"Field Helper\" data-content=\"<div>".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->comment)."</div>\"><i class=\"qco fas fa-info-circle\"></i></a>";
            // if(!empty($sd_section_structure_detail->sd_section_values)) print_r($section->sd_section_sets[0]->set_no);
                $id_idHolder = 'section-'.$section->id.'-sd_section_structures-'.$i.'-sd_field_values-'.$j.'-id';
                $id_nameHolder = 'sd_field_values['.$section->id.']['.$sd_section_structureK.'][id]';
                $field_value_nameHolder = 'sd_field_values['.$section->id.']['.$sd_section_structureK.'][field_value]';
                if($permission==1){
                    if((!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))){
                        $text =$text."<input id= ".$id_idHolder." name=".$id_nameHolder." value=".$sd_section_structure_detail->sd_field->sd_field_values[$j]->id." type=\"hidden\">";
                    }else{
                        $text =$text."<input id= ".$id_idHolder." name=".$id_nameHolder." value=\"\" type=\"hidden\">";
                    }
                    if($sd_section_structure_detail->is_required) $text =$text. "<i class=\"fas fa-asterisk reqField\"></i>" ;
                    $text =$text. "<input id= \"section-".$section->id."-is_required-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][is_required]\" value=";
                    if($sd_section_structure_detail->is_required) $text =$text. "1" ;else $text =$text. "0";
                    $text =$text. " type=\"hidden\">";
                    $text =$text. "<div id= \"section-".$section->id."-error_message-".$sd_section_structure_detail->sd_field->id."\" style=\"display:none\"></div>";
                    $text =$text. "<input id= \"section-".$section->id."-set_number-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][set_number]\" value=".$setNo." type=\"hidden\">";
                    $text =$text. "<input id= \"section-".$section->id."-sd_field_id-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][sd_field_id]\" value=".$sd_section_structure_detail->sd_field->id." type=\"hidden\">";
                }
                    switch($sd_section_structure_detail->sd_field->sd_element_type->type_name){
                        case 'select':
                        $text =$text. "<select class=\"form-control\" id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."\" name=".$field_value_nameHolder.">";
                        $text =$text."<option id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."-option-null\" value=\"null\" ></option>";
                                foreach($sd_section_structure_detail->sd_field->sd_field_value_look_ups as $option_no=>$option_detail){
                                    $text =$text. "<option id=\"section-".$section->id."-select-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" value=".$option_detail['value'];
                                    if($permission==2) $text =$text. " disabled";
                                    if(!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])&&$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value==$option_detail['value'])
                                    $text =$text. " selected=\"true\"";
                                    $text =$text. ">".$option_detail['caption']."</option>";
                                };
                                $text =$text."</select>";
                            continue;
                        case 'text':
                        $text =$text. "<input id=\"section-".$section->id."-field_rule-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][field_rule]\" value=".$sd_section_structure_detail->sd_field->field_length."-".$sd_section_structure_detail->sd_field->field_type." type=\"hidden\">";
                        $text =$text. "<input id=\"section-".$section->id."-text-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder." type=\"text\"";
                            if($permission==2) $text =$text. " disabled ";
                             (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text."value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":$text =$text.null;
                            $text =$text. ">";
                            continue;
                        case 'radio':
                        $text =$text. "<div id=\"section-".$section->id."-radio-".$sd_section_structure_detail->sd_field->id."\">";
                            foreach($sd_section_structure_detail->sd_field->sd_field_value_look_ups as $option_no=>$option_detail){
                                $text =$text. "<div class=\"custom-control custom-radio custom-control-inline\">";
                                $text =$text. "<input class=\"custom-control-input\" id=\"section-".$section->id."-radio-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" name=".$field_value_nameHolder." type=\"radio\" value=";
                                $text =$text. $option_detail['value'];
                                if($permission==2) $text =$text. " disabled";
                                if(!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])&&$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value==$option_detail['value'])
                                $text =$text. " checked=true";
                                $text =$text. "><label class=\"custom-control-label\" for=\"section-".$section->id."-radio-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" >".$option_detail['caption']."</label>";
                                $text =$text. "</div>";
                            }
                            $text =$text. "</div>";
                            continue;
                        case 'checkbox':
                        $text =$text. "<div class=\"form-row\" id=\"section-".$section->id."-checkbox-".$sd_section_structure_detail->sd_field->id."\">";
                            $valuesSet = array();
                            $valueCount = 0;
                            if(!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])) $valuesSet = $sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value;
                            foreach($sd_section_structure_detail->sd_field->sd_field_value_look_ups as $option_no=>$option_detail){
                                $text =$text. "<div class=\"custom-control custom-radio custom-control-inline col-md-3\">";
                                $text =$text. "<input id=\"section-".$section->id."-checkbox-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\" class=\"checkboxstyle\"  name=".$field_value_nameHolder." value=";
                                $text =$text. $option_detail['value'];
                                if($permission==2) $text =$text. " disabled";
                                if((!empty($valuesSet))&&(substr($valuesSet, $option_detail['value']-1,1)==1))
                                $text =$text. " checked=\"true\"";
                                $text =$text. " type=\"checkbox\" ><label class=\"form-check-label\" for=\"checkbox-".$sd_section_structure_detail->sd_field->id."-option-".$option_detail['value']."\">".$option_detail['caption']."</label>";
                                $text =$text. "</div>";
                                $valueCount ++;
                            }
                            if($permission==1){
                                $text =$text. "<input id=\"section-".$section->id."-checkbox-".$sd_section_structure_detail->sd_field->id."-".$valueCount."-final\" class=\"checkboxstyle\"  name=".$field_value_nameHolder." value=\"";
                                if (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j])){
                                    $text =$text. $sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value;
                                }
                                else { for($y = 0; $y<$valueCount;$y++)$text =$text. "0";
                                }
                                $text =$text. "\" type=\"hidden\">";
                            }
                            $text =$text."</div>";
                            continue;
                        case 'textarea':
                        $text =$text. "<input id=\"section-".$section->id."-field_rule-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][field_rule]\" value=".$sd_section_structure_detail->sd_field->field_length."-".$sd_section_structure_detail->sd_field->field_type." type=\"hidden\">";
                        $text =$text. "<textarea id=\"section-".$section->id."-textarea-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder;
                            if($permission==2) $text =$text. " disabled";
                            $text =$text. " rows=".$sd_section_structure_detail->field_height.">";
                             (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text.str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value):$text =$text.null;
                            $text =$text. "</textarea>";
                            continue;
                        case 'date':
                        $text =$text. "<input id=\"section-".$section->id."-field_rule-".$sd_section_structure_detail->sd_field->id."\" name=\"sd_field_values[".$section->id."][".$sd_section_structureK."][field_rule]\" value=".$sd_section_structure_detail->sd_field->field_length."-".$sd_section_structure_detail->sd_field->field_type." type=\"hidden\">";
                        $text =$text. "<input type=\"text\" class=\"form-control\" name=".$field_value_nameHolder." id=\"section-".$section->id."-date-".$sd_section_structure_detail->sd_field->id."\" ";
                            if($permission==2) $text =$text. " disabled ";
                            $text =$text. "value=\"";
                            (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text.$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value:$text =$text.null;
                            $text =$text. "\">";
                            continue;
                        case 'whodra browser':
                            if($permission==1){
                                $whoddCell = $html->cell('Whodd',[$sd_section_structure_detail->sd_field->id]);
                                $text =$text. $whoddCell;
                                $text =$text. "<input readonly=\"readonly\" style=\"float:left\" id=\"section-".$section->id."-whodracode-".$sd_section_structure_detail->sd_field->id."\" class=\"col-md-5 form-control\" name=".$field_value_nameHolder." type=\"text\"";
                                (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text."value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":$text =$text.null;
                                $text =$text. " >";
                            }
                            continue;
                        case 'whodra show':
                        $text =$text. "<input id=\"section-".$section->id."-whodraname-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder." type=\"text\"";
                        (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text."value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":$text =$text.null;
                            if($permission==2) $text =$text. " disabled ";
                            $text =$text. " readonly=\"readonly\">";
                            continue;
                        case 'Meddra browser':
                            $meddraCell = $html->cell('Meddra',[$sd_section_structure_detail->sd_field->id]);
                            $text =$text. $meddraCell;
                            continue;
                        case 'Meddra show':
                        $text =$text. "<input id=\"section-".$section->id."-".$sd_section_structure_detail->sd_field->descriptor."-".$sd_section_structure_detail->sd_field->id."\" class=\"form-control\" name=".$field_value_nameHolder." type=\"text\"";
                         (!empty($sd_section_structure_detail->sd_field->sd_field_values[$j]))?$text =$text."value=\"".str_replace("\"","&quot;",$sd_section_structure_detail->sd_field->sd_field_values[$j]->field_value)."\"":$text =$text.null;
                        $text =$text. "readonly=\"readonly\">";
                            continue;
                    }
                    $text =$text."</div>";
            $i++;
            $length_taken = $sd_section_structure_detail->field_length + $sd_section_structure_detail->field_start_at;
        }
        if($i!=0) $text =$text."</div>";
        $text =$text. "</div>";
    // }
    return $text;
}
function displaySection($sdSections, $allsdSections,$exsitSectionNo,$html,$permission){
    if(empty($exsitSectionNo)) return null;
    if(!in_array($sdSections->id,$exsitSectionNo)) return ["exsitSectionNo"=>$exsitSectionNo];
    $sectionKey = array_search($sdSections->id,$exsitSectionNo);
    $field_Text= "";
    $child_Field_Text = "";
    $child_Div_Text = "";
    $child_Nav_Text = "";
    $childchild_Field_Text ="";
    if(!empty($sdSections['sd_section_structures'])) $field_Text = displaySingleSection($sdSections, 1, $sectionKey, $html, $permission);//display single section's fields, iterater sectioin's strucutures
    if(empty($sdSections['child_section'])){
        $exsitSectionNo[$sectionKey]= null;
        $result = array("field_Text"=>$field_Text,
                        "exsitSectionNo"=>$exsitSectionNo);//add section label in "field";
        return $result;
    }
    $i = 0;
    $child_sections = explode(",",$sdSections['child_section']);
    foreach($child_sections as $key => $child_section){
        // $i++;if($i == 4) die();
        if(empty($exsitSectionNo)) break;
        $sectionKey = array_search($child_section,$exsitSectionNo);
        if($sectionKey === FALSE) continue;
        $result = displaySection($allsdSections[$sectionKey], $allsdSections,$exsitSectionNo,$html,$permission);
        if(!empty($result['nav_Text'])){
            $child_Nav_Text = $child_Nav_Text.$result['nav_Text'];
            $div_front ="";
            $div_front =  $div_front."<div class=\"tab-pane";
            if($allsdSections[$sectionKey]->display_order ==10)$div_front = $div_front." show active";
            $div_front =$div_front." fade\" aria-labelledby=\"nav-".$allsdSections[$sectionKey]->id."-tab\" role=\"tabpanel\" class=\"secdiff\" id=\"secdiff-".$allsdSections[$sectionKey]->id."\">";
            $child_Div_Text = $child_Div_Text.$div_front.$result['field_Text'].$result['child_Field_Text'].$result['child_Div_Text']."</div>";  //add label and div in "div"
        }else{
            $child_Field_Text = $child_Field_Text.$result['field_Text'];
        }
        $exsitSectionNo = $result['exsitSectionNo'];
    }
    $nav_Text = "<a class=\"nav-item";
    if($sdSections->display_order ==10) $nav_Text = $nav_Text ." active";
    $nav_Text = $nav_Text." nav-link\" id=\"nav-".$sdSections->id."-tab\" data-toggle=\"tab\" href=\"#secdiff-".$sdSections->id."\" role=\"tab\" aria-controls=\"secdiff-".$sdSections->id."\" aria-selected=\"true\">";
    $nav_Text = $nav_Text.$sdSections->section_name;
    $nav_Text = $nav_Text."</a>";
    // $nav_Text = "".$sdSections['id']."</div></nav>"; //add page style here
    if($child_Nav_Text!="") {
        $child_Nav_Text = "<nav class=\"my-3\"><div class=\"nav nav-tabs\" id=\"nav-tab\" role=\"tablist\">".$child_Nav_Text."</div></nav>"; //add navigation bar style here
        $child_Div_Text = "<div>".$child_Nav_Text."<div class=\"tab-content\" id=\"nav-tabContent\">".$child_Div_Text."</div></div>"; //pagination style
    }
    $exsitSectionNo[$sectionKey]= null;
    $result = [
        "child_Field_Text"=>$child_Field_Text,
        "field_Text"=>$field_Text,
        "nav_Text" => $nav_Text,
        "child_Div_Text" => $child_Div_Text,
        "exsitSectionNo"=>$exsitSectionNo
    ];
    return $result;
}
?>
